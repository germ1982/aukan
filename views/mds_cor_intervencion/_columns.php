<?php

use app\models\Mds_cor_intervencion_usuario;
use app\models\Mds_org_contacto;
use app\models\Sds_800_llamada;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idintervencion',
        'label' => '#',
        'width' => '5%'
    ],
    [
        'attribute' => 'fecha_hora',
        'width' => '15%',
        'label' => 'Fecha Última Actualización',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni_beneficiario',
        'label' => 'DNI',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $persona = Sds_com_persona::findOne($persona);
                return $persona->documento;
            }
            return "";
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Nombre y Apellido',
        'attribute' => 'idpersona_intervencion',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $persona = Sds_com_persona::findOne($persona);
                return mb_strtoupper($persona->nombre) . " " . mb_strtoupper($persona->apellido);
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $personaFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'profesional',
        'value' => function ($model) {
            $idprofesional = $model->profesional;
            if ($idprofesional != null) {
                $profesional = Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                join sds_com_persona p on p.idpersona=c.idpersona
                where idcontacto=" . $idprofesional)->one();
                return mb_strtoupper($profesional->nombre) . " " . mb_strtoupper($profesional->apellido);
            }
            return "";
        }, // falta ver desde que tabla toma los usuarios
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $profesionalFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Profesional...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'label' => 'Tipo de Intervención',
        'value' => function ($model) {
            $idconfiguracion = $model->tipo;
            if ($idconfiguracion != null) {
                $tipo = Sds_com_configuracion::findOne($idconfiguracion);
                return $tipo->descripcion;
            }
            return "";
        }, // falta ver desde que tabla toma los usuarios
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '7%',
        'visible' => ($hasRolAdminGeneral),
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => 'Si'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $stringButtonsIndex,
        'vAlign' => 'middle',
        'width' => '100px',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'update' => function ($url, $model) use ($hasRolAdminGeneral) {
                $url =  Url::to(['/mds_cor_intervencion/update', 'id' => $model->idintervencion]);
                return  Yii::$app->user->identity->idusuario != $model->idusuario && !Mds_cor_intervencion_usuario::findOne(['idintervencion' => $model->idintervencion, 'idusuario' => Yii::$app->user->identity->idusuario, 'editar' => 1]) && !$hasRolAdminGeneral ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'post', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'compartir' => function ($url, $model) use ($hasRolAdminGeneral) {
                $url =  Url::to(['/mds_cor_intervencion_usuario/index', 'id' => $model->idintervencion]);
                return  Yii::$app->user->identity->idusuario != $model->idusuario && !Mds_cor_intervencion_usuario::findOne(['idintervencion' => $model->idintervencion, 'idusuario' => Yii::$app->user->identity->idusuario, 'editar' => 1]) && !$hasRolAdminGeneral ? '' : Html::a('<i class="fas fa-share-alt"></i>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Compartir', 'data-toggle' => 'tooltip']);
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/mds_cor_intervencion/reporte_intervencion', 'idintervencion' => $model->idintervencion]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => 'Imprimir Informe'
                ]);
            },
            'imprimirRisneu' => function ($url, $model) {
                $documento = $model->idpersona0->documento;
                if($documento != null){
                    $idRisneu= \app\models\Sds_ris_risneu::getIdRisneuByPersonaDni($documento);
                    if($idRisneu != null){
                        $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $idRisneu]);
                        return Html::a('<span class= "fas fa-users"></span>', $url, [
                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Imprimir RISNeu'
                        ]);
                    }
                }
            },
            'previo' => function ($url, $model) {
                if ($model->idllamada != null) {
                    $model_llamada = Sds_800_llamada::findOne($model->idllamada);

                    $url =  Url::to(['/sds_800_llamada/reporte_llamada', 'idllamada' => $model->idllamada, 'area' => $model_llamada->area]);
                }
                return $model->idllamada == null ? '' : Html::a('<i class="fas fa-file-alt"></i></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver Atención Previa'
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                $usuarioAuth = Yii::$app->user->identity;
                $url =  Url::to(['/mds_cor_intervencion/delete', 'id' => $model->idintervencion]);
                if (($hasRolAdminGeneral || $model->idusuario === $usuarioAuth->idusuario) && !$model->deleted_at) {
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class="fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'reactivate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if ($model->deleted_at && ($hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_cor_intervencion/reactivate', 'id' => $model->idintervencion]);
                    return  Html::a(
                        '<span style="margin-left:0.5rem" class= "fas fa-check"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Re-activar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea re-activar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
        ],
    ],
];
