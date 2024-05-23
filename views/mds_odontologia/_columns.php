<?php

use app\controllers\Mds_odontologiaController;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;

function calculaedad($fechanacimiento)
{
    $data_birth = new DateTime($fechanacimiento); //Crea el objeto DateTime a partir de un string de fecha
    $data_hoy = new DateTime(); //devuelve la fecha actual
    $edad = $data_birth->diff($data_hoy); //Aplicamos la diferencia entre fechas
    $edad = $edad->y;
    return $edad;
}
$layoutDate = <<< HTML
    {input1}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idodontologia',
        'label' => '#',
        'value' => function ($model) {
            return $model['idodontologia'];
        },
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'label' => 'Persona',
        'value' => function ($model) {
            if ($model->persona) {
                $data = mb_strtoupper($model->persona['apellido']) . ", " . mb_strtoupper($model->persona['nombre']) . " ({$model->persona['documento']})";
            } else {
                $data = "";
            }
            return $data;
        }
    ],
    [
        'attribute' => 'edad',
        'label' => 'Edad actual',
        'value' => function ($model) {
            if ($model->persona) {
                $una_com_persona = Sds_com_persona::findOne($model->persona->idpersona);
                $la_edad = calculaedad($una_com_persona->fecha_nacimiento);
                $la_edad == 1 ? $date = $la_edad . ' año' : $date = $la_edad . ' años';
            } else {
                $date = '';
            }
            return $date;
        },
    ],
    [
        'attribute' => 'fecha_atencion',
        'value' => function ($model) {
            if ($model->fecha_atencion) {
                $date = date_create($model->fecha_atencion);
                $date = date_format($date, 'd-m-Y');
            } else {
                $date = '';
            }
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_atencion',
            'options' => ['placeholder' => 'Fecha atención'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipointervencion',
        'label' => 'Tipo intervención',
        'value' => function ($model) {
            if ($model['idtipointervencion']) {
                $data = $model->tipointervencion['descripcion'];
            } else {
                $data = '';
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoIntervencionFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'label' => 'Dispositivo',
        'value' => function ($model) {
            if ($model['iddispositivo'] && $model->dispositivo) {
                $data = mb_strtoupper($model->dispositivo->descripcion);
            } else {
                $data = '';
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoDispositivoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idescolaridad',
        'label' => 'Escolaridad',
        'value' => function ($model) {
            if ($model['idescolaridad']) {
                $data = $model->escolaridad['descripcion'];
            } else {
                $data = '';
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoEscolaridadFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '8%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'buttons' =>
        [
            'update' => function ($url, $model, $key) use ($hasRolAdmin, $hasRolAdminGeneral, $hasPermissionUpdate, $usuarioAuth) {
                $idodontologia = $model['idodontologia'];
                $url = Url::to(['mds_odontologia/update', 'id' => $idodontologia]);
                if ($hasRolAdmin || $hasRolAdminGeneral || ($hasPermissionUpdate && $model['idusuario_carga'] === $usuarioAuth->idusuario)) {
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_odontologia/detalle_registro', 'id' => $model->idodontologia]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolAdmin, $hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_odontologia/delete', 'id' => $model->idodontologia]);
                if (!$model->deleted_at && ($hasRolAdmin || $hasRolAdminGeneral || ($model['idusuario_carga'] === $usuarioAuth->idusuario))) {

                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class="fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este registro?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'print_history' => function ($url, $model) {
                $url =  Url::to(['/mds_odontologia/historial_odontologico', 'id' => $model->persona->idpersona]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-download"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Historia Odontológica')
                ]);
            },
            'reactivate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if ($model['deleted_at'] && $hasRolAdminGeneral) {
                    $url =  Url::to(['/mds_odontologia/reactivate', 'id' => $model->idodontologia]);
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
        ]
    ],
];
