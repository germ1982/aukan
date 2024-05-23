<?php

use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use Symfony\Component\CssSelector\Node\FunctionNode;
use yii\helpers\ArrayHelper;
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
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '1px',
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
        'attribute'=>'fecha_hora',
        'width'=>'15%',
        'value'=>function($model){
            $date=date_create($model->fecha_hora);
            return date_format($date, 'd/m/Y H:i');
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idmovimiento',
        'label'=>'Tipo',
        'value'=>function($model){
            $movimiento=Sds_bdc_movimiento::findOne($model->idmovimiento);
            $tipo=Sds_com_configuracion::findOne($movimiento->tipo);
            return $tipo->descripcion;
        },
        'filter'=>false,
        'width'=>'20%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Solicitante',
        'value'=>function($model){
            $movimiento=Sds_bdc_movimiento::findOne($model->idmovimiento);
            $solicitante=Sds_com_persona::findOne($movimiento->solicitante0->idpersona);
            return $solicitante->apellido.', '.$solicitante->nombre;
        },
        'width'=>'29%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Observaciones',
        'value' => function($model){
            $movimiento=Sds_bdc_movimiento::findOne($model->idmovimiento);
            return $movimiento->observaciones;
        },
        'width' => '35%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{reporte}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],
        'buttons' => [
          'reporte'=>function ($url, $model) {
              $url =  Url::to(['/sds_bdc_movimiento/reporte', 'movimiento' => $model->idmovimiento]);
              return Html::a('<span class="far fa-clipboard"></span>', $url, [
                  'title' => "Reporte",
                  'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                  'data-toggle' => 'tooltip',
              ]);
          },
        ],
    ],
];   