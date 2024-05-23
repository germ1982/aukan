<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Sds_stk_deposito;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

use yii\helpers\Url;
use kartik\date\DatePicker;
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
$columna1 = '10%';//fecha_hora
$columna2 = '20%';
$columna3 = '20%';
$columna4 = '40%';
$columna5 = '10%';

return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idinventario',
    ], */
    [//fecha_hora
        'attribute' => 'fecha_hora',
        'width' => $columna1,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
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
    [//idusuario//Usuario
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = Mds_seg_usuario::findOne($model->idusuario);
            return Mds_org_contacto::getAyN($usuario->idcontacto);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            mds_seg_usuario::findBySql("SELECT * from mds_seg_usuario u
                                        join mds_org_contacto c on u.idcontacto = c.idcontacto
                                        join sds_com_persona p on p.idpersona = c.idpersona
                                        where u.idusuario in (SELECT idusuario from sds_stk_inventario)
                                        order by p.apellido, p.nombre")->all(),
            'idusuario',
            function ($model) {
                $usuario = Mds_seg_usuario::findOne($model->idusuario);
                return Mds_org_contacto::getAyN($usuario->idcontacto);
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width' => $columna2,
        'label' => 'Usuario',
    ],
    [//idcontacto//responsable
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddeposito',
        'value' => function ($model) {
            return Sds_stk_deposito::findOne($model->iddeposito)->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_stk_deposito::findBySql("SELECT * from sds_stk_deposito d
                                        where d.iddeposito in (SELECT iddeposito from sds_stk_inventario)
                                        order by d.descripcion")->all(),
            'iddeposito','descripcion',
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Deposito...'],
        'format' => 'raw',
        'width' => $columna3,
        'label' => 'Deposito',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle_items',
        'width' => $columna4,
        'format' => 'html',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => $columna5,
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   