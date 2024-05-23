<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Sds_com_configuracion;
use app\models\Mds_rum_oferta_laboral;
use app\models\Mds_rum_postulacion;
use app\models\Mds_rum_empleador;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\app;
date_default_timezone_set('America/Argentina/Buenos_Aires');
//Yii::app()
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
   /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'titulo_of',
        'label' => 'Titulo',
        'value' => function ($model) {
            return $model->titulo;            
        }, 
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_rum_oferta_laboral::find()            
            ->orderBy(['titulo' => SORT_ASC])
            ->all(), 'titulo', 'titulo'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Titulo...'],
        'format' => 'raw',
        'width' => '20%',        
    ],  
    [
        'attribute' => 'fecha_publicacion',
        'width' => '12%',
        'label' => 'Fecha Publicación',
        'value' => function ($model) {
            $fc = date_create($model->fecha_publicacion);
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
        'attribute' => 'id_categoria2',
        'label' => 'Categoria',
        'value' => function ($model) {
            $categoria = Sds_com_configuracion::findOne($model->id_categoria);
            return $categoria->descripcion;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Sds_com_configuracion::find()->where("idconfiguraciontipo=53")->all(), 
            'idconfiguracion', 
            function ($model) {
                return $model->descripcion;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Categoria...'],
        'format' => 'raw',
        'width' => '15%',   
    ],
 
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero2',
        'label' => 'Genero',
        'value' => function ($model) {
            if ($model->genero=='M')
            {
                return 'Masculino';
            }
            else
            {
                if ($model->genero=='F')
                {
                    return 'Femenino';
                }
                else
                {
                    if ($model->genero=='I')
                    {
                        return 'Otros';
                    }
                }
            }
                       
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            [['id'=>'M','cad'=>'Masculino'],['id'=>'F','cad'=>'Femenino'],['id'=>'I','cad'=>'Otros'] ],
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_dur_trabajo2',
        'label' => 'Dur. Trabajo',
        'value' => function ($model) {
            $durtrab = Sds_com_configuracion::findOne($model->id_dur_trabajo);
            return $durtrab->descripcion;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Sds_com_configuracion::find()->where("idconfiguraciontipo=52")->all(), 
            'idconfiguracion', 
            function ($model) {
                return $model->descripcion;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Dur. Trabajo...'],
        'format' => 'raw',
        'width' => '10%',   
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'empresa',
        'label' => 'Empresa',
        'value' => function ($model) {
            $una_empresa = Mds_rum_empleador::findOne($model->id_empleador);
            return $una_empresa->nombre;                        
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Mds_rum_empleador::find()->all(), 
            'id', 
            function ($model) {
                return $model->nombre;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empresa...'],
        'format' => 'raw',
        'width' => '15%',   
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'num_visto2',
        'label'=>'Visto',
        'width' => '5%',
        'value' => function ($model) {                       
            return $model->num_visto;                        
        }, 
    ],    
    [
        'attribute' => 'postulaciones',     
        'width' => '5%',
        'label' => '# Post.',
       
        'value' => function ($model) {            
            $las_postulaciones=Mds_rum_postulacion::find()->where("id_oferta=".$model->id)->all();
            $cant_postulaciones=count($las_postulaciones);                                
            return $cant_postulaciones;
        },        
    ],
    /*
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fechaalta',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'horaalta',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fechamodificacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'horamodificacion',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_publicacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'hora_publicacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'salario',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_nivel_ocupacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_experiencia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'genero',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_categoria',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_cualificacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'descripcion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'competencia',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_tipo_trabajo',
    // ],
    
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'email1',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'email2',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'telefono1',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'telefono2',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'imagen',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ubicacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_localidad',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_empleador',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fin_dias',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fin_horas',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fin_min',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fin_seg',
    // ],
   
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estadoOferta',
        'label' => 'Estado Oferta',
        'value' => function ($model) {
                    
            $fecha_fin_pub=$model->fecha_publicacionfin;        
            $hora_fin_pub=$model->hora_publicacionfin;  
            $fecha_publicacion=$model->fecha_publicacion;        
            $hora_publicacion=$model->hora_publicacion;           
            $fecha_actual= date('Y-m-d');
            $hora_actual=  date('H:i:s'); 
           
            $activa=$model->activo == 1 ? 'Activa' : 'No Activa';
            $cad=$activa; 
            if (($fecha_actual>$fecha_fin_pub) || (($fecha_actual==$fecha_fin_pub) && ($hora_actual >= $hora_fin_pub)))
            {
                $cad.=" | Finalizada";
            }   
            else
            {
                $cad.=" | No Finalizada";
            }  
            if (($fecha_actual>$fecha_publicacion) || (($fecha_actual==$fecha_publicacion) && ($hora_actual >= $hora_publicacion)))
            {
                $cad.=" | Publicada";
            } 
            else
            {
                $cad.=" | No Publicada";
            }               
            return  $cad;


        },
        'width' => '10%', 
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {postular} {update} {delete}' ,
        'vAlign'=>'middle',        
        'width' => '7%',  
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },

        'buttons' => [
            'postular' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_rum_postulacion/index_postulados', 'id_oferta' => $model->id, 
                ]);
                return Html::a('<i class="fas fa-user-tie"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Postulaciones de esta Oferta Laboral',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],   
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Está seguro?',
                          'data-confirm-message'=>'Seguro que desea eliminar esta Categoria?'], 
    ], 

];   
