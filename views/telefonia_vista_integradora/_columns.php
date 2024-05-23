<?php

use app\controllers\Sds_cel_movimientoController;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\grid\GridView;

function crear_celda($label, $contenido, $ancho)
{
    echo "
    <div class='col-xs-$ancho'>  
        <h6><b>$label</b></h6>
        <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                $contenido
        </p>
    </div>";
}

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
            return $model->empresa != null ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'lineanro',
        'label'=>'Linea inicial',
    ], */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'linea',
        'value'=> function ($model)
                        {
                            $dato = Sds_cel_movimientoController::actionGet_dato_actual($model->lineanro,"numero");
                            if($dato=='')
                                {$dato = $model->lineanro;}
                            return $dato;
                        },
        'label'=>'Numero Actual',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cuenta',
        'filter' => Yii::$app->user->identity->celular_cuenta == null
    ],
    [
        'attribute' => 'ultimo_movimiento',

        'width' => '10%',
        'value' => function ($model) {
            $dato = Sds_cel_movimientoController::actionGet_dato_actual($model->lineanro,"fecha");
            if($dato=='')
                { $dato = date_create($model->ultimo_movimiento);}
            else
                { $dato = date_create($dato);}
            $dato = date_format($dato, 'd/m/Y');
            return $dato;
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
        'attribute'=>'organismo',
        'value'=> function ($model)
                        {
                            $dato = Sds_cel_movimientoController::actionGet_dato_actual($model->lineanro,"organismo");
                             if(!($dato==''))
                                {
                                    $organismo = Sds_com_configuracion::findOne($dato);
                                    $dato= $organismo->descripcion;
                                }

                            return $dato;
                        },
        'label'=>'Organismo',
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'dependecia',
         'label'=>'Dependencia',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'responsable',
     ],

     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'imei',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'plan',
     ],
     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'baja',
        'value' => function ($model) {
            return $model->baja == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
     ],
     [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {movimientos}',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
        'buttons' => [
                'movimientos' => function ($url, $model) {
                $url =  Url::to(['/sds_cel_movimiento/index', 'lineanro' => $model->lineanro]);

                return Html::a('<span class="fas fa-hand-holding"></span>', $url, [
                    'title' => "Movimientos",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        //'width' => '10%',
    ],

];   