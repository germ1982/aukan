<?php
use yii\helpers\Url;
use app\models\Mds_org_organismo;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_orden_compra;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;



$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;


//$columna1 = '0%';//idordencompra
$columna2 = '10%';//fecha_emision
$columna3 = '10%';//vencimiento
$columna4 = '10%';//numero
$columna5 = '10%';//expediente
/* $columna6 = '12%';//tipo_norma_legal
$columna7 = '10%';//norma_legal */
$columna8 = '20%';//proveedor
/* $columna9 = '10%';//importe_total */
//$columna10 = '0%';//idorganismo
$columna11 = '25%';//detalle
$columna12 = '15%';//actions





return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idordencompra',
        'width' => $columna1,

    ], */
    [
        'attribute' => 'fecha_emision',
        'width' => $columna2,
        'label' => 'Fecha de Emision',
        'value' => function ($model) {
            $fc = date_create($model->fecha_emision);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fedesde',
            'attribute2' => 'fehasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => false,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),

    ],
    [
        'attribute' => 'vencimiento',
        'width' => $columna3,
        'label' => 'Fecha de Vencimiento',
        'value' => function ($model) {
            $fc = date_create($model->vencimiento);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fvdesde',
            'attribute2' => 'fvhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => false,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),

    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'numero',
        'value' => function ($model) {
            $aux = $model->numero;
            if($model->nn)
            {
                $aux = "NN- $aux";
            }
            return $aux;
        },
        'width' => $columna4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expediente',
        'width' => $columna5,
    ],
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_norma_legal',
        'label' => 'Tipo Norma Legal',
        'value' => function ($model) {
            if ($model->tipo_norma_legal != null) {
                $config = Sds_com_configuracion::findOne($model->tipo_norma_legal);
                return $config->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::find()->where("idconfiguracion IN (SELECT sds_stk_orden_compra.tipo_norma_legal from sds_stk_orden_compra)")->orderBy(['descripcion' => SORT_ASC])->all(), 
            'idconfiguracion','descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo Norma Legal...'],
        'format' => 'raw',
        'width' => $columna6,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'norma_legal',
        'width' => $columna7,
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'proveedor',
        'label' => 'proveedor',
        'value' => function ($model) {
            if ($model->proveedor != null) {
                $config = Sds_com_configuracion::findOne($model->proveedor);
                return $config->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::find()->where("idconfiguracion IN (SELECT sds_stk_orden_compra.proveedor from sds_stk_orden_compra)")->orderBy(['descripcion' => SORT_ASC])->all(), 
            'idconfiguracion','descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Proveedor...'],
        'format' => 'raw',
        'width' => $columna8,
    ],
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'importe_total',
        'width' => $columna9,
    ], */
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            if ($model->idorganismo != null) {
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_organismo::find()->where("idorganismo IN (SELECT sds_stk_orden_compra.idorganismo from sds_stk_orden_compra)")->orderBy(['descripcion' => SORT_ASC])->all(), 
            'idorganismo','descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => $columna10,
        'label' => 'Organismo'
    ], */

    [
        'attribute' => 'detalle_items',
        'width' => $columna11,
        'label' => 'Detalle',
        'format' => 'html',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna12,
        'dropdown' => false,
        'template' => "{view} {update} {items} {entregas} {generarpi} {delete}",
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        /* 'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,//for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta seguro?',
                          'data-confirm-message'=>'Se va a eliminar esta orden de compra, esta seguro?'],  */
            'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_orden_compra/update', 'id'=>$model->idordencompra]);
                return  $model->generada ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'items' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_orden_compra/update', 'id'=>$model->idordencompra, 'items'=>1]);
                return $model->generada ? '' : Html::a('<span class= "fas fa-cubes"></span>', $url, [
                    'title' => "Ver Solo Items ",
                    'role' => 'modal-remote', 'data-pjax' => 0, 
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_orden_compra/delete', 'id' => $model->idordencompra
            ]);
                return  $model->generada ? '' : Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => '¿Está seguro de que quiere eliminar este item?'
                ]);
            },
            'entregas' => function ($url, $model) {
                //$aux_fecha='';asa
                $url =  Url::to([
                    '/sds_stk_entrega/imprimir_reporte_entregas',
                    'id_orden_compra'=>$model->idordencompra
                ]);

                $consulta_oc_entregas= "SELECT *
                                        FROM sds_stk_orden_compra oc
                                        JOIN sds_stk_orden_compra_item oci on oc.idordencompra = oci.idordencompra
                                        JOIN sds_stk_recepcion_item ri on ri.idordencompraitem = oci.idordencompraitem
                                        JOIN sds_stk_entrega_item ei on ri.idrecepcionitem = ei.recepcion_item
                                        WHERE oc.idordencompra = $model->idordencompra";
                $oc = Sds_stk_orden_compra::findBySql($consulta_oc_entregas)->all(); 
                return $oc ? Html::a('<span class= "fas fa-people-carry"></span>', $url, [
                    'title' => "Entregas",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]) : '';
            },

            'generarpi' => function ($url, $model) {
                $ban = 1;
                if($model->fecha_orden_compra == null)
                    {$ban = 0;}
            
                if($model->nn == 1)
                    {$ban = 0;}

                if($model->generada == 1)
                    {$ban = 0;}

                $url =  Url::to(['/sds_stk_orden_compra/generar_pi', 'id_oc'=>$model->idordencompra]);

                return $ban==1 ? Html::a('<span class= "glyphicon glyphicon-arrow-right"></span>', $url, [
                    'title' => "Generar PI",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => 'Se va a generar el primer ingreso, esta de acuerdo?'
                ]) : '';
            },
        ],
    ],

];   