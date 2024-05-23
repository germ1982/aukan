<?php

use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\helpers\Html;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;

$permiso_situaciones = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and iditem=" . Mds_seg_item::MODULO_ORG_SITUACION)->one();

$permiso_situaciones = $permiso_situaciones != null ? 1 : 0;

return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return ($model->telefono != null && strlen($model->telefono) > 0
                && $model->mail != null && strlen($model->mail) > 0) ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
        'filter' => true,
        'value' => function ($model) {
            return $model->legajo != null ? $model->legajo : "";
        },
        'format' => 'raw',
        'width' => '7%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'label' => 'DNI',
        'filter' => true,
        'format' => 'raw',
        'width' => '8%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'label' => 'Persona',
        'filter' => true,
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $iddispositivo = $model->iddispositivo;
            if ($iddispositivo != null) {
                $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                
                return $dispositivo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_org_dispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'iddispositivo',
            function ($model) {
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                return $model->descripcion . " - " . $organismo->descripcion;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Dispositivo...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'servicio',
        'value' => function($model){
            $servicio = Sds_com_configuracion::findOne($model->servicio);
            return empty($servicio) ? '-' : $servicio->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Sds_com_configuracion::findBySql("SELECT conf.* FROM mds_org_contacto c
            JOIN sds_com_configuracion conf ON c.servicio=conf.idconfiguracion
            ORDER BY conf.descripcion ASC")->all(),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Dispositivo...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'actividad',
        'value' => function ($model) {
            $idconfiguracion = $model->actividad;
            if ($idconfiguracion != null) {
                $configuracion = Sds_com_configuracion::findOne($idconfiguracion);
                return $configuracion->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ORG_ACTIVIDAD),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ' Seleccione Actividad...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'categoria',
        'value' => function ($model) {
            $idconfiguracion = $model->categoria;
            if ($idconfiguracion != null) {
                $configuracion = Sds_com_configuracion::findOne($idconfiguracion);
                return $configuracion->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONVENIO),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Categoria...'],
        'format' => 'raw',
        'width' => '8%',
    ],
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'categoria',
        //'label' => 'Acomp.',
        'value' => function ($model) {            
            return $model->categoria ? $model->categoria : '';        },
        'width' => '5%',
        //'filter' => ['0' => 'No', '1' => ' Si'],
    ], */
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'acompaniante',
        //'label' => 'Acomp.',
        'value' => function ($model) {
            return $model->acompaniante == 1 ? 'Si' : 'No';
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1' => ' Si'],
    ], */

    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_contratacion',
        //'label' => 'Event.',
        'value' => function ($model) {
            $aux = '';

            switch($model->tipo_contratacion)
                {
                    case 0:
                        $aux = "Planta Politica";
                        break;
                    case 1:
                        $aux = "Planta Permanente";
                        break;
                    case 2:
                        $aux = "Eventuales";
                        break;
                    case 3:
                        $aux = "Contrato";
                        break;
                }
            return $aux;
        },
        'width' => '5%',
        'filter' => ['0' => 'Planta Politica', '1' => 'Planta Permanente','2' => 'Eventuales', '3' => ' Contrato'],
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'eventual',
        //'label' => 'Event.',
        'value' => function ($model) {
            return $model->eventual == 1 ? 'Si' : 'No';
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1' => ' Si'],
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ubicacion_fisica',
        //'label' => 'Ubic.Fís.',
        'value' => function ($model) {
            return $model->ubicacion_fisica!=null? $model->ubicacion_fisica:"";
        },
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'norma_legal',
        'value' => function ($model) {
            return $model->norma_legal!=null? $model->norma_legal:"";
        },
        'width' => '5%',
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'interno',
        'label' => 'Int.',
        'value' => function ($model) {
            return $model->interno == 1 ? 'Si' : 'No';
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1' => ' Si'],
    ], */
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rotativo',
        'label' => 'Sem. no cal.',
        'value' => function ($model) {
            return $model->rotativo == 1 ? 'Si' : 'No';
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1' => ' Si'],
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => '5%',
    ],
    
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Dom.',
        'value' => function ($model) {
            $idlocalidad = $model->idlocalidad;
            if($idlocalidad!=null){
                $localidad = Sds_com_localidad::findOne($idlocalidad);
                return $localidad->idprovincia==58 ? "DP":"FP";
            }
            return "No";
        },
        'width' => '5%',
        'filter' => ['0' => 'No', '1'=>'DP','2'=>'FP'],
    ], */

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'value' => function ($model) {
            $idorganismo = $model->idorganismo;
            if ($idorganismo != null) {
                $organismo = Mds_org_organismo::findOne($idorganismo);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'idorganismo',
            function ($model) {
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                return $organismo->descripcion;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Organismo...'],
        'format' => 'raw',
        'width' => '7%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} ' . ($permiso_contactos->modifica ? '{update} ' : '')
                                . ($permiso_situaciones ? ' {situaciones}' : '')
                                . ' {imprimir} {domicilio} {remanente} {certificacion}'. 
                                ($permiso_contactos->baja ? ' {delete}' : ''),
        'vAlign' => 'middle',
        'width' => '15%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Consultar', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'El contacto se eliminará del sistema',
            'data-confirm-message' => '¿Está seguro de querer eliminar el registro?'
        ],
        'buttons' => [
            'situaciones' => function ($url, $model) {
                $url =  $url =  Url::to(['/mds_org_situacion/index', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class= "glyphicon glyphicon-calendar"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'title' => 'Situaciones',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/mds_org_contacto/reporte_credencial', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'title' => 'Imprimir Credencial',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'domicilio' => function ($url, $model) {
                $url =  Url::to(['/mds_org_contacto/domicilio', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class= "far fa-address-book"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Datos de Domicilio',
                    'data-toggle' => 'tooltip',
                ]);
            },
            /* 'foto_dni' => function ($url, $model) {
                $url =  Url::to(['/mds_org_contacto/foto_dni', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class= "far fa-address-card"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Generar Foto DNI',
                    'data-toggle' => 'tooltip',
                ]);
            }, */
            'remanente' => function ($url, $model) {
                $url =  Url::to(['/mds_hor_remanente/index', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class="fas fa-plane-departure"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'title' => 'Remanente',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'certificacion' => function ($url, $model) {
                $url =  Url::to(['certificacion_laboral', 'idcontacto' => $model->idcontacto]);
                return Html::a('<span class="fas fa-clipboard-check"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'title' => 'Certificación Laboral',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
    ],

];
