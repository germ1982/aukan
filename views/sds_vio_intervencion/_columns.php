<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Sds_800_llamada;
use app\models\Sds_vio_intervencion;

//GERM: esto siguiente lo robe de por ahi
$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '2%',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idintervencion',
        'label' => '#',
        'width' => '4%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idllamada',
        'width' => '4%',
        'value' => function ($model) use ($hasRolAdminGeneral) {
            $value = "";
            if ($model && isset($model->idllamada)) {
                $estado = $model->llamada0->estado0;
                $value = $model->idllamada . " (" . $estado;
                if ($hasRolAdminGeneral) {
                    $idintervencion = Sds_800_llamada::intervencionActiva($model->idllamada);
                    $value .= $idintervencion ? " - Intevención " . $idintervencion : "";
                }
                $value .= ")";
            }
            return $value;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idrisneu',
        'width' => '4%',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->idrisneu)) {
                $value = $model->idrisneu;
            }
            return $value;
        },
    ],
    [
        'attribute' => 'fecha',
        'width' => '8%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
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
        'attribute' => 'dni',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombrecompuesto',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->tipo0)) {
                $value = $model->tipo0->descripcion;
            }
            return $value;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoIntervencionFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'derivacion',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->derivacion0)) {
                $value = $model->derivacion0->descripcion;
            }
            return $value;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $derivacionFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Oficina...'],
        'format' => 'raw',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'label' => 'Responsable Carga',
        'value' => function ($model) {
            $username = "";
            if ($model && isset($model->idusuario0)) {
                $username = mb_strtoupper($model->idusuario0->apellido) . ", " . mb_strtoupper($model->idusuario0->nombre);
            }
            return $username;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $usuarioCargaFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Agente...'],
        'format' => 'raw',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'entidad',
        'filter' => false,
        /* 'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Entidad...'],
        'format' => 'raw',*/
        'width' => '8%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'detalle',
        'width' => '15%',
        'format' => 'html',
        'value' => function ($model) {
            $detalle = $model->detalle;
            if (strlen($detalle) > 100) {
                return substr($detalle, 0, 100) . "...";
            }
            return $detalle;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '5%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return $model->deleted_at === NULL ? 'Si' : 'No';
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $stringButtonsIndex,
        'vAlign' => 'middle',
        'width' => '10%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'hijos' => function ($url, $model) {
                $url =  Url::to(['/sds_com_persona/index_hijos', 'idpadre' => $model->idpersona, 'idllamada' => $model->idllamada]);
                return Html::a('<i class="fas fa-child"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Asignar Hijos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'agresores' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/index_interv_agresor', 'idintervencion' => $model->idintervencion]);
                return Html::a('<i class="far fa-angry"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Asignar Agresores',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'movimiento' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion_movimiento/index', 'idintervencion' => $model->idintervencion]);
                return Html::a('<i class="fas fa-exchange-alt"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Asignar Movimientos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'imprimir' => function ($url, $model) {
                $llamada = $model->idllamada;
                if ($llamada != null) {
                    $url =  Url::to(['/sds_800_llamada/reporte_llamada', 'idllamada' => $model->idllamada, 'area' => Sds_800_llamada::AREA_VIOLENCIA]);
                } else {
                    $url =  Url::to(['/sds_vio_intervencion/reporte_intervencion', 'idintervencion' => $model->idintervencion]);
                }
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjaxn' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => 'Imprimir Informe'
                ]);
            },
            'imprimirRisneu' => function ($url, $model) {
                $documento = $model->dni;
                if ($documento != null) {
                    $idRisneu = \app\models\Sds_ris_risneu::getIdRisneuByPersonaDni($documento);
                    if ($idRisneu != null) {
                        $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $idRisneu]);
                        return Html::a('<span class= "fas fa-users"></span>', $url, [
                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Imprimir RISNeu'
                        ]);
                    }
                }
            },
            'update' => function ($url, $model, $key) {
                $url = Url::to(['sds_vio_intervencion/update', 'id' => $model->idintervencion, 'idllamada' => $model->idllamada]);
                return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Actualizar'
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion/delete', 'id' =>  $model->idintervencion, 'idllamada' =>  $model->idllamada]);
                return Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Confirmar',
                    'data-confirm-message' => 'La intervención #' . $model->idintervencion . ' con <b>DNI N°' . $model->dni . '</b> será <b>ELIMINADA</b> <br>¿Desea continuar?'
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion/reactivate', 'id' => $model['idintervencion'], 'idllamada' => $model['idllamada']]);
                return  Html::a('<span style="margin-left:0.5rem" class= "fas fa-check"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => ('Re-activar'),
                    'data-confirm' => false,
                    'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Confirmar',
                    'data-confirm-message' => 'La intervención #' . $model->idintervencion . ' con <b>DNI N°' . $model->dni . '</b> será <b>REACTIVADA</b> <br>¿Desea continuar?'
                ]);
            }
        ],
        'visibleButtons' => [
            'view' => function ($model) use ($hasRolAdminGeneral) {
                return (is_null($model->deleted_at)) || $hasRolAdminGeneral;
            },
            'update' => function ($model) use ($hasRolAdminGeneral) {
                $estaAtendida = Sds_vio_intervencion::estaAtendida($model->idintervencion);
                return (is_null($model->deleted_at) && $estaAtendida) || $hasRolAdminGeneral;
            },
            'delete' => function ($model) use ($hasRolAdminGeneral, $idusuario) {
                $data_hoy = date('Y-m-d');
                $estaAtendida = Sds_vio_intervencion::estaAtendida($model->idintervencion);
                return (
                    (
                        ($model->idusuario ===  $idusuario)
                        && ($model->fecha == $data_hoy)
                        && is_null($model->deleted_at)
                        && $estaAtendida
                    )
                    ||
                    ($hasRolAdminGeneral
                        && is_null($model->deleted_at)
                    )
                ); //solo puede eliminar las creadas el mismo dia
            },
            'reactivate' => function ($model)  use ($hasRolAdminGeneral) {
                return ($hasRolAdminGeneral && !is_null($model->deleted_at));
            },
            'imprimir' => function ($model)  use ($hasRolAdminGeneral) {
                return ($hasRolAdminGeneral || is_null($model->deleted_at));
            },
        ],
        'viewOptions' => ['role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip'],

        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Está Seguro?',
            'data-confirm-message' => 'El item seleccionado procederá a eliminarse'
        ],
    ],

];
