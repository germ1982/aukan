<?php

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
        'attribute' => 'idrisneu',
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'beneficiarios',
        'label' => 'Grupo Conviviente',
        'filter' => true,
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $familiaString = '<ul>';
            $familiares = $model->getFamiliares();
            if (count($familiares) > 0) {
                foreach ($familiares as $index => $familiar) {
                    $apellidoMuscula = mb_strtoupper($familiar->persona->apellido);
                    $nombreMayuscula = mb_strtoupper($familiar->persona->nombre);
                    $dni = $familiar->persona->documento;
                    $tipoDocumento = $familiar->persona->documentoTipo->descripcion;
                    $tipoDocumentoPointStart = strpos($tipoDocumento, ".") ? strpos($tipoDocumento, ".") + 1 : 0;
                    $tipoDocumento = substr($tipoDocumento, $tipoDocumentoPointStart);
                    $parentesco = $familiar->parentezco0->descripcion;
                    $parentescoPointStart = strpos($parentesco, ".") ? strpos($parentesco, ".") + 1 : 0;
                    $parentesco = substr($parentesco, $parentescoPointStart);
                    $familiaString .= "<li>{$apellidoMuscula}, {$nombreMayuscula} - <b>$tipoDocumento:</b> $dni - <b>$parentesco</b></li>";
                }
                $familiaString .= '</ul>';
            } else {
                $familiaString = $model->dni ? "<ul><li><b>DNI:</b>  $model->dni</li></ul> El grupo conviviente aún no fue cargado" : 'El grupo conviviente aún no fue cargado';
            }
            return $familiaString;
        },

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idencuestador',
        'value' => function ($model) {
            if ($model->encuestador == null) {
                return "(Datos Incompletos)";
            }
            return $model->encuestador0->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $encuestadoresFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Encuestador...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Localidad',
        'value' => function ($model) {
            if ($model->idbarrio0) {
                return $model->idbarrio0->localidad->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $localidades,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Localidad...'],
        'format' => 'raw',
        'width' => '18%',
    ],
    [
        'attribute' => 'fecha',
        //'header' => 'Organismo',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
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
        'attribute' => 'estado',
        'value' => function ($model) {
            return $model->activo ? ($model->estado == 1 ? 'Completo' : 'Incompleto') : "Inactivo";
        },
        'width' => '10%',
        'filter' => ['-1' => 'Todos', '0' => 'Incompletos', '1' => 'Completos', '2' => 'Inactivos']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'oficial',
        'label' => 'Oficial',
        'value' => function ($model) {
            if ($model->oficial == 0) {
                return 'No oficial';
            } else {
                return 'Oficial';
            }
        },
        'filter' => false,
        'format' => 'raw',
        'width' => '10%'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => $stringButtonsIndex,
        'width' => '7%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to([
                    '/sds_ris_risneu/update', 'id' => $model->idrisneu, 'finalizar' => false,
                    'dni' => $model->dni_beneficiario
                ]);
                return Html::a('<span  style="margin-left: 0.5rem;" class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'imprimirRisneu' => function ($url, $model) {
                $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $model->idrisneu]);
                return Html::a('<span  style="margin-left: 0.5rem;" class= "fas fa-users"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => 'Imprimir RISNeu'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                if ($model['activo'] == 1) {
                    $url =  Url::to(['/sds_ris_risneu/delete', 'id' => $model->idrisneu]);
                    return  Html::a(
                        '<span style="margin-left:0.5rem" class= "fas fa-trash"></span>',
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
            'reactivate' => function ($url, $model, $key) {
                if ($model['activo'] == 0) {
                    $url =  Url::to(['/sds_ris_risneu/reactivate', 'id' => $model->idrisneu]);
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
