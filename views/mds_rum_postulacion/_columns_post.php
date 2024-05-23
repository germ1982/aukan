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
    $template='{view} {update} {delete}';
}
else // es un admin empresa
{
    $template='{view}';

}

return [
    /*[
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
        'attribute' => 'id_persona5',
        'header' => 'Persona',
        'value' => function ($model) {
            $idpersona = $model->id_persona;  
            $una_persona=Mds_rum_persona::findOne($idpersona); 
            $una_com_persona=Sds_com_persona::findOne($una_persona->id_com_persona);
            $cad=$una_com_persona->nombre.' '.$una_com_persona->apellido;
            return $cad;
        }, 
         
        
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_persona6',
        'header' => 'DNI',
        'value' => function ($model) {
            $idpersona = $model->id_persona;  
            $una_persona=Mds_rum_persona::findOne($idpersona);
            $una_com_persona=Sds_com_persona::findOne($una_persona->id_com_persona);

           //$una_configuracion=Mds_rum_configuracion::findOne($una_com_persona->documento_tipo);
            $cad=$una_com_persona->documento;
            return $cad;
        },
        'width' => '10%',
         
    ],    
    
    [
        'attribute' => 'fecha_post5',     
        'width' => '15%',
        'label' =>'Fecha Postulación',
        'value' => function ($model) {
            $fc = date_create($model->fecha_post);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'hora_post5',
        'width' => '15%',
        'label' =>'Hora Postulación',
        'value' => function ($model) {           
            return $model->hora_post;
        },   
    ],
    [

        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $template ,
        'vAlign'=>'middle',        
        'width' => '7%',  
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },

        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_rum_postulacion/view3', 'id' => $model->id, 'id_oferta' => $model->id_oferta,
                ]);
                return Html::a('<i class="far fa-eye"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Postulaciones de esta Oferta Laboral',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_rum_postulacion/update2', 'id' => $model->id, 'id_oferta' => $model->id_oferta,
                ]);
                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Postulaciones de esta Oferta Laboral',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],   
        /*'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },*/
        //'viewOptions'=>['role'=>'modal-remote','title'=>'Ver Postulacion','data-toggle'=>'tooltip'],
        //'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar Postulacion', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar Postulacion', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta Seguro?',
                          'data-confirm-message'=>'Seguro que desea eliminar?'], 
    ],

];   