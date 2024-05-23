<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_planilla;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;


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
            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    /*[
        'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idplanilla',
            'label' => 'ID',
            
        ],
    [
            'class' => '\kartik\grid\DataColumn',
                'attribute' => 'activo',
                'label' => 'Activo',
                
        ],*/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->idorganismo);
            return $tipo->descripcion;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::R_ORGANISMO, 'activo'=>'1'])->all(), 
            'idconfiguracion', 
            function ($model) {
                return $model->descripcion;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
        'width' => '40%',  

    ],
    [
    'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idplantilla',
        'label' => 'Plantilla',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->idplantilla);
            return $tipo->descripcion;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::R_TIPO_PLANTILLA, 'activo'=>'1'])->all(), 
            'idconfiguracion', 
            function ($model) {
                return $model->descripcion;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
        'width' => '25%',  
    ],
    [

        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'mes',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_r_planilla::find()            
            ->orderBy(['mes' => SORT_ASC])
            ->all(), 'mes', 'mes'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Mes...'],
        'format' => 'raw',
        'width' => '10%',  
    ],
    [        

        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'anio',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_r_planilla::find()            
            ->orderBy(['anio' => SORT_ASC])
            ->all(), 'anio', 'anio'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Año...'],
        'format' => 'raw',
        'width' => '8%',  
    ],
    [
        'attribute' => 'fechacarga',
        'label' => 'Fecha de Registro',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fechacarga);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_desde',
            'attribute2' => 'fecha_hasta',
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
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idplantilla',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo',
        'label' => 'Periodo',
        'value' => function ($model) {
            if ($model->periodo==0)
            {
                return 'Mensual';
            }
            else
            {
                if ($model->periodo==1)
                {
                    return 'Trimestral';
                }
                else
                {
                    if ($model->periodo==2)
                    {
                        return 'Semestral';
                    }
                    else
                    {
                        if ($model->periodo==3)
                        {
                            return 'Anual';
                        }
                        else
                        {
                            return 'Desconocido';
                        }
                    }
                }
            }
                       
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            [['id'=>0,'cad'=>'Mensual'],['id'=>1,'cad'=>'Trimestral'],['id'=>2,'cad'=>'Semestral'],['id'=>3,'cad'=>'Anual'] ],
            'id', 'cad'            
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Categoria...'],
        'format' => 'raw',
        'width' => '9%',   
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        //'template' => '{view} {update}  {diagnosticos} {delete}' ,
        'template' => '{view} {update} {print} {delete}' ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_r_planilla/exportarplanilla', 'idplanilla' => $model->idplanilla]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
        ], 
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar planilla', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar Planilla',
                          'data-confirm-message'=>'Esta seguro que desea eliminar esta planilla?'], 
    ],

];   