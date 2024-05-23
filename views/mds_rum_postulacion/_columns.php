<?php
use yii\helpers\Url;
use app\models\Mds_rum_persona;
use app\models\Sds_com_persona;
use app\models\Mds_rum_oferta_laboral;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;

use app\models\Mds_seg_usuario_rol;
use app\components\AccessRule;
use app\models\Mds_rum_postulacion;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;



$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one();  
if ($un_rol_usuario == null)
{ 
    $template='{view} {update} {delete} {imprimir}';
}
else // es un admin empresa
{
    $template='{view} {update} {imprimir}';
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
        'label' => 'Persona',
        'value' => function ($model) {
            $idpersona = $model->id_persona;  
            
            $una_persona=Mds_rum_persona::findOne($idpersona);            
            $una_com_persona=Sds_com_persona::findOne($una_persona->id_com_persona);
            $cad=$una_com_persona->nombre.' '.$una_com_persona->apellido;            
            return $cad;
        }, 

        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::find()->where("idpersona in (select mds_rum_persona.id_com_persona from mds_rum_persona,mds_rum_postulacion where mds_rum_postulacion.id_persona= mds_rum_persona.id  )")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(), 
            'idpersona', 
            function ($model) {
                return $model->nombre . " " . $model->apellido;
            }
        ),
        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',
        'width' => '25%',    
         
        
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'label' => 'DNI',
        'value' => function ($model) {
            $idpersona = $model->id_persona;  
            $una_persona=Mds_rum_persona::findOne($idpersona);
            $una_com_persona=Sds_com_persona::findOne($una_persona->id_com_persona);
            $cad=$una_com_persona->documento;            
            return $cad;
        },
        'width' => '10%',
        
    ],    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'titulo_oferta',
        'label' => 'Oferta Laboral',
        'value' => function ($model) {
            $idoferta = $model->id_oferta;  
            $una_oferta=Mds_rum_oferta_laboral::findOne($idoferta);
            $cad=$una_oferta->titulo;
            return $cad;
        },        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(               
            Mds_rum_oferta_laboral::find()->where("id in (select mds_rum_postulacion.id_oferta from mds_rum_postulacion)")->all(), 
            'id', 
            function ($model) {
                return $model->titulo;
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Oferta...'],
        'format' => 'raw',
        'width' => '20%',   
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estadoOferta',
        'label' => 'Estado Oferta',
        'value' => function ($model) {
            $idoferta = $model->id_oferta;  
            $una_oferta=Mds_rum_oferta_laboral::findOne($idoferta);            
            $fecha_fin_pub=$una_oferta->fecha_publicacionfin;        
            $hora_fin_pub=$una_oferta->hora_publicacionfin;  
            $fecha_publicacion=$una_oferta->fecha_publicacion;        
            $hora_publicacion=$una_oferta->hora_publicacion;           
            $fecha_actual= date('Y-m-d');
            $hora_actual=  date('H:i:s'); 
           
            $activa=$una_oferta->activo == 1 ? 'Activa' : 'No Activa';
            $cad=$activa; 
            if (($fecha_actual>$fecha_fin_pub) || (($fecha_actual==$fecha_fin_pub) && ($hora_actual >= $hora_fin_pub)))
            {
                $cad.=" | Finalizada";
            }     
            if (($fecha_actual>$fecha_publicacion) || (($fecha_actual==$fecha_publicacion) && ($hora_actual >= $hora_publicacion)))
            {
                $cad.=" | Publicada";
            }                
            return  $cad;


        },
        'width' => '10%', 
    ],
    [
        'attribute' => 'fecha_post',
        'width' => '10%',
        'label' => 'Fecha Postulación',
        'value' => function ($model) {
            $fc = date_create($model->fecha_post);
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
        'attribute' => 'estado',
        'label' => 'Estado Postulación',
        'value' => function ($model) {
            return $model->estado;            
        }, 
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
           [
                ['id' => 'descartado', 'name' => 'descartado'],
                ['id' => 'postulado', 'name' => 'postulado'],
                ['id' => 'elegido', 'name' => 'elegido'],
                ['id' => 'seleccionado', 'name' => 'seleccionado'],
                ['id' => 'finalista', 'name' => 'finalista'],
           ], 'id','name'),

        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => '15%',        
    ],
    /*
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '14%',
        'value' => function ($model) {
            switch ($model->estado) {
                case 0: return "descartado";
                case 1: return "postulado";
                case 2: return "elegido";
                case 3: return "seleccionado";
                case 4: return "finalista";
            }
        },
        
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_rum_postulacion::find()->orderBy(['estado' => SORT_ASC])->all(), 
            'estado', 
            /*function ($model) {
                switch ($model->estado) {
                    case 0: return "descartado";
                    case 1: return "postulado";
                    case 2: return "elegido";
                    case 3: return "seleccionado";
                    case 4: return "finalista";
                }
            }
        ),        
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => 'Estado...'
        ],
    ],*/
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $template ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
                          'buttons' => [      
                                       
                            'imprimir' => function ($url,$model,$key) {
                                $id_rum_persona=$model->id_persona;
                                $una_persona=Mds_rum_persona::findOne($id_rum_persona);
                                $id_seg_usuario2=$una_persona->id_seg_usuario;
                
                                $url =  Url::to("https://rumbo.neuquen.gov.ar/mi_cvsur.php?id=".$id_seg_usuario2,true);
                                return Html::a('<i class="far fa-file-pdf"></i>', $url, [
                                                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                                                
                                                            ]);
                                
                                 
                                    
                                },
                            
                        ]
                       
    ],

];   