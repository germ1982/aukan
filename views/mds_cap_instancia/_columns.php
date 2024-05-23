<?php

use app\models\Mds_cap_campania;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_instancia;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
$permiso_global = $permiso_global != null ? 1 : 0;

$el_usuario = Yii::$app->user->identity;
// analizando el rol
$permiso_cert = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GENERAR_CERTIF . ")")->one();
if ($permiso_cert == null) // no tiene permisos para  generar certificados
{
    $tiene_perm_cert = false;
} else // tiene permiso para generar certificados
{
    $tiene_perm_cert = true;
}

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
        'attribute' => 'idinstancia',
        'label' => 'Nro',
        'width' => '5%',
    ],

    [
        'attribute' => 'desde',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->desde);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde_desde',
            'attribute2' => 'fdesde_hasta',
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
        'attribute' => 'hasta',

        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->hasta);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fhasta_desde',
            'attribute2' => 'fhasta_hasta',
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
        'attribute' => 'idcapacitacion',
        'label' => 'Capacitación',
        'value' => function ($model) {
            $idcapacitacion = $model->idcapacitacion;
            if ($idcapacitacion != null) {
                $capacitacion = Mds_cap_capacitacion::findOne($idcapacitacion);
                return $capacitacion->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterCapacitaciones,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Capacitación...'],
        'format' => 'raw',
        'width' => '20%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'label' => 'Instancia',
        'width' => '15%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'capacidad',
        'filter' => false,
        'value' => function ($model) {
            $capacidad = $model->capacidad;
            if ($capacidad == 0) {
                $model->capacidad = "-";
            }
            return $model->capacidad;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'inscriptos',
        'value' => function ($model) {
            return Mds_cap_inscripcion::find()->where(["idcapinstancia" => $model->idinstancia])->count();
        },
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'presencial',
        'value' => function ($model) {
            $presencial = $model->presencial;
            switch ($presencial) {
                case 0:
                    return "Presencial";
                    break;
                case 1:
                    return "Virtual";
                    break;
                case 2:
                    return "Dual";
                    break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(Mds_cap_capacitacion::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idcapacitacion', 'descripcion'),
        'filter' => ['0' => 'Presencial', '1' => 'Virtual', '2' => 'Dual'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Modalidad...'],
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcampania',
        'label' => 'Campaña',
        'value' => function ($model) {
            $idcampania = $model->idcampania;
            if ($idcampania != null) {
                $campania = Mds_cap_campania::findOne($idcampania);                
                return $campania->descripcion;
            }
            return "";
        },

        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_cap_campania::find()->where("idcampania in (select idcampania from mds_cap_campania)")
                ->orderBy(['descripcion' => SORT_ASC])->all(), 'idcampania', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Campania...'],
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => ($idusuario==98 ? '{migrar}' : '').'{view} ' . ($permiso_edicion == 1 ? '{update}' : '') . ' {cumbre} ' .  ($tiene_perm_cert ? ' {certificado}  {imprimir_cert} {vista_previa} {cert_docentes}' : ''),
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        //https://cumbre.neuquen.gov.ar/curso/<alias>
        'viewOptions' => ['role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'post', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro de eliminar este item?'
        ],
        'buttons' => [
            'cumbre' => function ($url, $model) {
                $url =  'https://cumbre.neuquen.gov.ar/curso/' . $model->alias;
                return Html::a('<img src="img/cumbre_link.png"  style="cursor: pointer;width:18px;">', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'title' => 'Cumbre',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'certificado' => function ($url, $model) {
                if ((Mds_cap_inscripcion::find()->where(["idcapinstancia" => $model->idinstancia])->count())>0)
                {
                    $url =  Url::to(['/mds_cap_instancia/certificados', 'id' => $model->idinstancia]);
                    return Html::a('<span class= "fas fa-file-download"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Generar los certificados',
                        'data-confirm' => false, 'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',

                    ]);
                }
                else
                {
                    return '';
                }
            },
            'imprimir_cert' => function ($url, $model) {
                if ((Mds_cap_inscripcion::find()->where(["idcapinstancia" => $model->idinstancia])->count())>0)
                {
                    $url =  Url::to(['/mds_cap_instancia/descargar_certificados', 'id' => $model->idinstancia]);
                    return Html::a('<span class= "far fa-file-pdf"></span>', $url, [
                        'title' => 'Descargar los certificados',
                        'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '_blank',
                        'data-confirm' => false, 'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',

                    ]);
                }
                else
                {
                    return '';
                }
            },
            'migrar' => function ($url, $model) {
                $url =  Url::to(['/mds_cap_instancia/migrar']);
                return Html::a('<span class= "fab fa-angellist"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'migrar',
                    'data-confirm' => false, 'data-method' => false,
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',

                ]);
            },
            'vista_previa' => function ($url, $model) {     

                if ((Mds_cap_inscripcion::find()->where(["idcapinstancia" => $model->idinstancia])->count())>0)
                {
                    $url =  Url::to(['/mds_cap_instancia/preview_certificado', 'id' => $model->idinstancia]);

                    return Html::a('<span class= "fas fa-binoculars"></span>', $url, [
                        'title' => 'Vista Previa del Certificado',
                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                        'data-toggle' => 'tooltip',
                    ]);
                } else

                {
                    return '';
                }

                
            },
            'cert_docentes' => function ($url, $model) {
                if ((Mds_cap_inscripcion::find()->where(["idcapinstancia" => $model->idinstancia])->count())>0)
                {
                    $url =  Url::to(['/mds_cap_instancia/cert_docentes', 'id' => $model->idinstancia]);

                    return Html::a('<span class= "fas fa-chalkboard-teacher"></span>', $url, [
                        'title' => 'Certificados de Docentes',
                        'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '_blank',
                        'data-confirm' => false, 'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                    ]);
                }
                else
                {
                    return '';
                }
            }

        ],
    ],

];
