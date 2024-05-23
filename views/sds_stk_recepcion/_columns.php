<?php
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_stk_orden_compra;
use kartik\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_recepcion_item;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$id_organismo = $usuario->organismo_stock;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idrecepcion',
    ], */
    [
        'attribute' => 'fecha',
        'width' => '11%',
        'label' => 'Fecha',
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
        'label' => 'Proveedor',
        'attribute' => 'proveedor',
        'value' => function ($model) {
            $idproveedor = $model->proveedor;
            if ($idproveedor != null) {
                $proveedor = Sds_com_configuracion::findOne($idproveedor);
                return $proveedor->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PROVEEDOR), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Proveedor...'],
        'format' => 'raw',
        'width' => '20%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expediente',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Orden de Compra',
        'attribute' => 'idordencompra',
        'value' => function ($model) {
            if ($model->idordencompra != null) {
                $configuracion = Sds_stk_orden_compra::findOne($model->idordencompra);
                return "$configuracion->numero";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_stk_orden_compra::find()->where("idorganismo = $id_organismo")->orderBy(['numero' => SORT_ASC])->all(), 'idordencompra', 'numero'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Proveedor...'],
        'format' => 'raw',
        'width' => '20%'

    ],
    [
        'attribute' => 'detalle_items',

        'label' => 'Detalle',
        'format' => 'html',
    ],
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo',
    ], */
    [
        'attribute' => 'mostrar',
        'format' => 'html',
        'visible' => false,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update} {items} {generarei} {imprimir_reporte_entregas} {delete}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta Seguro?',
                          'data-confirm-message'=>'Esta seguro que quiere eliminar esta recepcion?'], 
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_recepcion/update', 'id'=>$model->idrecepcion]);
                return  $model->generada ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'items' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_recepcion/update', 'id'=>$model->idrecepcion, false,'items'=>1]);
                return $model->generada ? '' : Html::a('<span class= "fas fa-cubes"></span>', $url, [
                    'title' => "Ver Solo Items ",
                    'role' => 'modal-remote', 'data-pjax' => 0, 
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_recepcion/delete', 'id' => $model->idrecepcion
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
            
            'generarei' => function ($url, $model) {
                $ban = 1;

                if($model->idordencompra == null)
                    {$ban = 0;}
                else
                    {
                        $oc = Sds_stk_orden_compra::findOne($model->idordencompra);
                        if($oc->generada==0)
                            {$ban = 0;}
                    }
                
                if($model->generada == 1)
                    {$ban = 0;}

                $url =  Url::to(['/sds_stk_recepcion/generar_ei', 'idrecepcion'=>$model->idrecepcion]);

                return $ban==1 ? Html::a('<span class= "glyphicon glyphicon-arrow-right"></span>', $url, [
                    'title' => "Generar EI",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => 'Se va a generar la entrega intermedia, esta de acuerdo?'
                ]) : '';
            },
            'imprimir_reporte_entregas' => function ($url, $model) {
                //$url =  Url::to(['/sds_stk_entrega/imprimir_acta_entrega', 'identrega' => $model->identrega]);
                $consulta = "SELECT * FROM 
                                sds_stk_entrega_item ei
                                JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                                WHERE ri.idrecepcion = $model->idrecepcion";

                $items = Sds_stk_recepcion_item::findBySql($consulta)->all();
                $url = Url::to([
                    '/sds_stk_entrega/imprimir_reporte_entregas',
                    'idrecepcion' => $model->idrecepcion,
            
                ]);
                return $items>0 ? Html::a('<span class="fas fa-people-carry"></span>', $url, [
                    'title' => "Entregas ",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]):'';

            },
        ],
    ],

];   