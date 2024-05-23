<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
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
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return $model->solicitante != null ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'attribute'=>'fecha_hora',
        'width'=>'12%',
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
        'attribute'=>'solicitante',
        'value'=>function($model){
            $contacto=Mds_org_contacto::findOne($model->solicitante);
            $persona=Sds_com_persona::findOne($contacto->idpersona);
            return $persona->apellido.' '.$persona->nombre;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_bdc_movimiento::find()->groupBy('solicitante')->all(),
            'solicitante',
            function($model){
                if ($model->solicitante != null) {
                    $contacto = Mds_org_contacto::findOne($model->solicitante);
                    if ($contacto->idpersona != null) {
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        $aux = $persona->apellido.", ".$persona->nombre." - Leg.: ".$contacto->legajo;
                        return $aux;
                    }
                }
                return "";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Solicitante...'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo',
        'value'=>function($model){
            $tipo=Sds_com_configuracion::findOne($model->tipo);
            return $tipo->descripcion;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_com_configuracion::findBySql('SELECT c.idconfiguracion, c.descripcion FROM mdsyt.sds_com_configuracion c 
            JOIN sds_bdc_movimiento m ON c.idconfiguracion=m.tipo GROUP BY c.idconfiguracion')->all(),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo Movimiento...'],
        'width'=>'15%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observaciones',
        'width' => '30%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{delete} &nbsp;&nbsp; {reporte}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta a punto de eliminar el movimiento',
                          'data-confirm-message'=>'<span class="text-warning">¿Está seguro de continuar?</span>'],
        'buttons' => [
            'reporte'=>function ($url, $model) {
                $url =  Url::to(['/sds_bdc_movimiento/reporte', 'movimiento' => $model->idmovimiento]);
                if($model->tipo!=Sds_bdc_movimiento::MOV_CAM_IP){
                    return Html::a('<span class="far fa-clipboard"></span>', $url, [
                        'title' => "Reporte",
                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                        'data-toggle' => 'tooltip',
                    ]);
                }
                return'&nbsp;&nbsp;&nbsp;';
            },
            /* 'qr'=>function ($url, $model) {
                $url =  Url::to(['/sds_bdc_equipo/reporte_qr', 'format' => 'movimiento', 'id_mov' => $model->idmovimiento]);
                if($model->tipo==Sds_bdc_movimiento::MOV_ENT_REPARACION){
                    return Html::a('<span class="fas fa-qrcode"></span>', $url, [
                        'title' => "Imprimir QR",
                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                        'data-toggle' => 'tooltip',
                    ]);
                }
                return'&nbsp;&nbsp;&nbsp;';
            }, */
        ],
    ],
];   