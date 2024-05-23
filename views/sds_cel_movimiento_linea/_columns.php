<?php

use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;
use app\models\Sds_cel_movimiento_linea;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_hora',
        'value'=>function ($model) {
            if ($model->fecha_hora != null) {
                return date_format(date_create($model->fecha_hora), 'd/m/Y H:i:s');
            }
            return "";
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
                'format' => 'dd/mm/yyyy',
                'autoclose' => true
            ]
            ]),
        'width' => '13%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'solicitante',
        'value'=>function($model){
            $solicitante=Mds_org_contacto::getAyN($model->solicitante);
            return ($solicitante!=null?$solicitante:'- SIN DATOS -');
        },
        'width' => '25%',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_cel_movimiento_linea::find()->groupBy('solicitante')->all(),
            'solicitante',
            function($model){
                if ($model->solicitante != null) {
                    $contacto = Mds_org_contacto::findOne($model->solicitante);
                    if ($contacto->idpersona != null) {
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return $contacto->legajo." - ".$persona->apellido.", ".$persona->nombre;
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
            $tipo=Sds_com_configuracion::getDescripcion($model->tipo);
            return ($tipo!=null?$tipo:'- SIN DATOS -');
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_com_configuracion::findBySql('SELECT c.idconfiguracion, c.descripcion FROM sds_com_configuracion c 
            JOIN sds_cel_movimiento_linea m ON c.idconfiguracion=m.tipo GROUP BY c.idconfiguracion')->all(),
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
        'value'=>function($model){
            return ($model->observaciones!=null?$model->observaciones:'');
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver Movimiento','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Está a punto de eliminar este movimiento',
                          'data-confirm-message'=>'<div class="alert alert-danger" style="text-align:center;">
                            ¿Está seguro de continuar? Haga click en <b>OK</b> para continuar</div>'], 
    ],

];   