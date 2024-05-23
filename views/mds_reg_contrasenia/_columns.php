
<?php

use app\models\Mds_org_organismo;
use app\models\Mds_reg_contrasenia;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'checkboxOptions' => function ($model) {
            return ['value' => $model->idcontrasenia];
        },
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
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ASIGNACION_IP),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Dispositivo....'],
        'width'=>'15%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ip',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'usuario',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contrasenia',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ubicacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observaciones',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=> '{view} {update} {delete} {logs}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'logs' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_sys_log/index', 'id' => $model->idcontrasenia,
                    'modulos' => "mds_reg_contrasenia"
                ]);
                return Html::a('<span class= "fas fa-history"></span>', $url, [
                    'title' => "Logs de registro",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Estas a punto de eliminar este registro ',
                          'data-confirm-message'=>'<span class="text-danger">Seguro que desea continuar?</span>'], 
    ],

];   