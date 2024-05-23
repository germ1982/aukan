<?php
use yii\helpers\Url;
use app\models\Sds_com_persona;
use app\models\Mds_rum_persona;
use app\models\Mds_seg_usuario_rol;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\Mds_rum_postulacion;
$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one();  
if ($un_rol_usuario == null)
{ 
    $template='{view} {datos_cuenta} {imprimir} {observacion}';
}
else // es un admin empresa
{
    $template='{view} {imprimir} {observacion}';
}
function calculaedad($fechanacimiento){  
    $data_birth = new DateTime($fechanacimiento); //Crea el objeto DateTime a partir de un string de fecha
    $data_hoy = new DateTime(); //devuelve la fecha actual
    $edad = $data_birth->diff($data_hoy); //Aplicamos la diferencia entre fechas
    $edad = $edad->y; 
    return $edad;
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona',
        'label' => 'Nombres y Apellidos',
        'value' => function ($model) {
            $una_com_persona=Sds_com_persona::findOne($model->id_com_persona);
            $cad=$una_com_persona->nombre.' '.$una_com_persona->apellido;
            return $cad;
        },         
        'format' => 'raw',
        'width' => '25%',    
         
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'documento', 
        'label' => 'DNI',        
        'value' => function ($model) {           
            $una_com_persona=Sds_com_persona::findOne($model->id_com_persona);
            $cad=$una_com_persona->documento;
            return $cad;
        }, 
        
        'format' => 'raw',
        'width' => '12%',   
        
    ],    
    [
        'attribute' => 'fecha_nacimiento',     
        'width' => '8%',
        'label' => 'Fecha Nacimiento',
        'value' => function ($model) {
            $una_com_persona=Sds_com_persona::findOne($model->id_com_persona);            
            $fc = date_create($una_com_persona->fecha_nacimiento);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },        
    ],
    [
        'attribute' => 'edad',     
        'width' => '8%',
        'label' => 'Edad',
        'value' => function ($model) {
            
            $una_com_persona=Sds_com_persona::findOne($model->id_com_persona);  
            $la_edad=calculaedad($una_com_persona->fecha_nacimiento);                      
            return $la_edad;
        },        
    ],
    [
        'attribute' => 'postulaciones',     
        'width' => '8%',
        'label' => '# Post.',
        'value' => function ($model) {
            
            $las_postulaciones=Mds_rum_postulacion::find()->where("id_persona=".$model->id)->all();
            $cant_postulaciones=count($las_postulaciones);
                                
            return $cant_postulaciones;
        },        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telcel',
        'label' => 'Telefono',
        'width' => '15%',
    ],

    [
        'attribute' => 'fechaalta',
        'width' => '10%',
        'label' => 'Fecha Alta',
        'value' => function ($model) {
            $fc = date_create($model->fechaalta);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },        
    ], 
    [
        'attribute' => 'horaalta',
        'width' => '10%',
        'label' => 'Hora Alta',
               
    ],                
    [
        'attribute' => 'last_date',
        'width' => '10%',
        'label' => 'Fecha último ingreso',
        'value' => function ($model) {
                if ($model->last_date!=null)
                {
                    $fc = date_create($model->last_date);
                    $fc = date_format($fc, 'd/m/Y');
                    return $fc;
                } else
                {
                    return '';
                }                   
        },        
    ],         
    [
        'attribute' => 'last_time',
        'width' => '10%',
        'label' => 'Hora último ingreso',
        'value' => function ($model) {
            if ($model->last_time!=null)
            {                
                return $model->last_time;
            } else
            {
                return '';
            }                   
    },      
               
    ],  
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' =>  $template  ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],        
        'buttons' => [      
            'datos_cuenta' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_seg_usuario/index_cuenta', 'id' => $model->id_seg_usuario, 'id_rum_persona' => $model->id,
                ]);
                return Html::a('<i class="fas fa-user-cog"></i>', $url, [
                    'role' => 'modal-remote',                    
                    'title' => 'Datos de la Cuenta',
                    'data-toggle' => 'tooltip',
                ]);
            },                           
            'imprimir' => function ($url,$model,$key) {

                $url =  Url::to("https://rumbo.neuquen.gov.ar/mi_cvsur.php?id=".$model->id_seg_usuario,true);
                return Html::a('<i class="far fa-file-pdf"></i>', $url, [
                                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                                
                                            ]);                                                    
                },                
                             
                'observacion' => function ($url, $model) {
                    $url =  Url::to(['/mds_rum_observacion/index', 'id' => $model->id]);
                    return Html::a('<span class= "fas fa-book"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Observaciones', 
                    ]);
                }                        
            
        ]
       
    ],

];   