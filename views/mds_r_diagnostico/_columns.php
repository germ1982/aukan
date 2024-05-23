<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Mds_r_diagnostico;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_ejidos;
use app\models\Sds_gis_capa_item;
use yii\helpers\Html;



$una_dimension= Mds_r_variable_dimension::find()
        ->where(['idvardimension' => $idvardimension])        
        ->one();
return [
   
    [       
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'label' => 'Dispositivo',
        'value' => function ($model) {
            $una_dimension= Mds_r_variable_dimension::find()
            ->where(['idvardimension' => $model->idvardimension])        
            ->one();
            $result="";
            if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_LOCALIDADES)
            {  // BUSCAR EL EJIDO
                $result=( Mds_r_ejidos::findOne($model->idejido))->ejido;                         
            }
            else
            {
                if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_DISPOSITIVO)
                {
                    $una_gis_capa=Sds_gis_capa_item::findOne($model->iddispositivo);
                    if (isset($una_gis_capa))
                    {
                        $result=$una_gis_capa->descripcion;
                    }
                       else $result=$model->iddispositivo;                     
                }
            }

            return $result;                        
        },        
        
    ], 
    
    [

        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idvardimension',
        'label' => 'Dimensión',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->valor_dimension);
            return $tipo->descripcion;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => $una_dimension->iddimension, 'activo'=>'1'])->all(), 
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'valor',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_r_diagnostico::find()            
            ->orderBy(['valor' => SORT_ASC])
            ->all(), 'valor', 'valor'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Valor...'],
        'format' => 'raw',
        'width' => '12%',  
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{ver} {actualizar}  {borrar}' ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
    
            'ver' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_diagnostico/view', 'id' => $model->iddiagnostico,  'idvardimension' => $model->idvardimension 
                ]);
                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Ver Dimensión',
                    'data-toggle' => 'tooltip',
                ]);
    
            },
            'actualizar' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_diagnostico/update', 'id' => $model->iddiagnostico, 'idvardimension' => $model->idvardimension,
                ]);
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Editar Dimensión',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'borrar' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_diagnostico/delete2', 'id' => $model->iddiagnostico, 'idvardimension' => $model->idvardimension,
                ]);
                return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [   
                    'role' => 'modal-remote',                 
                    'title' => 'Eliminar Diagnóstico',
                    'data-toggle' => 'tooltip',                 
                ]);
               
            },
        ],
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