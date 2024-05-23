<?php
use yii\helpers\Url;
use app\models\Mds_rum_domicilio;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;

use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_rum_empleador;
use app\models\Mds_rum_oferta_laboral;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;


$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one();  
if ($un_rol_usuario == null) // es administrador
{ 
    $template='{view} {update} {delete}';

    return [  
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nombre_emp',
            'label' => 'Nombre Empleador',
            'value' => function ($model) {
                return $model->nombre;            
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Mds_rum_empleador::find()            
                ->orderBy(['nombre' => SORT_ASC])
                ->all(), 'id', 'nombre'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Empleador...'],
            'format' => 'raw',
            'width' => '15%',        
        ],    
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddomicilio2',
            'label' => 'Localidad',
            'value' => function ($model) {
                $iddomicilio = $model->iddomicilio;  
               
                $un_domicilio=Mds_rum_domicilio::findOne($iddomicilio);                   
                $una_localidad=Sds_com_localidad::findOne($un_domicilio->idlocalidad);
    
               //$una_configuracion=Mds_rum_configuracion::findOne($una_com_persona->documento_tipo);
                $cad=$una_localidad->descripcion;
                return $cad;        
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Sds_com_localidad::find()            
                ->orderBy(['descripcion' => SORT_ASC])
                ->all(), 'idlocalidad', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Localidad...'],
            'format' => 'raw',
            'width' => '15%',        
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'email2',
            'label' => 'Email',
            'value' => function ($model) {
                return $model->email;            
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Mds_rum_empleador::find()            
                ->orderBy(['email' => SORT_ASC])
                ->all(), 'email', 'email'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Email...'],
            'format' => 'raw',
            'width' => '15%',        
        ],  
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'telefono1',
            'label' => 'Telefono Principal',
            'width' => '12%',
        ], 
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'id_categoria2',
            'label' => 'Categoria',
            'value' => function ($model) {
    
                $id_categoria = $model->id_categoria; 
                $una_categoria=Sds_com_configuracion::findOne($id_categoria);                        
               //$una_configuracion=Mds_rum_configuracion::findOne($una_com_persona->documento_tipo);
                $cad=$una_categoria->descripcion;
                return $cad;                       
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Sds_com_configuracion::find()   
                ->where(["activo" => 1])              
                ->andWhere(["idconfiguraciontipo"=> 53] )       
                ->orderBy(['descripcion' => SORT_ASC])
                ->all(), 'idconfiguracion', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Categoría...'],
            'format' => 'raw',
            'width' => '15%',        
        ],  
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'activo2',
            'label' => 'Activo',
            'value' => function ($model) {
                return $model->activo == 1 ? 'Si' : 'No';
            },
            'width' => '6%',
            'filter' => ['0' => 'No', '1' => ' Si']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'estado2',
            'label' => 'Estado',
            'value' => function ($model) {
                if ($model->estado==1)
                {
                    return "Espera Validación";
                }
                else
                {
                    if ($model->estado==2)
                    {
                        return "Pendiente de Aprobación";
                    }
                    else
                    {
                        if ($model->estado==3)
                        {
                            return "Aceptada";
                        } 
                        else
                        {
                            return "Rechazada";
                        }
                    }
                }            
                           
            },        
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(               
                [['id'=>'1','cad'=>'Espera Validación'],['id'=>'2','cad'=>'Pendiente de Aprobación'],['id'=>'3','cad'=>'Aceptada'],['id'=>'4','cad'=>'Rechazada']],
                'id', 'cad'            
            ),        
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Estado...'],
            'format' => 'raw',
            'width' => '11%',   
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'id_of2',
            'label' => '# Ofert. Labs.',
            'value' => function ($model) {               
                $id_emp = $model->id;                 
                $las_ofertas=Mds_rum_oferta_laboral::find()->where("id_empleador=".$id_emp)->all();                      
                $cant_of=count($las_ofertas);  
               
                return $cant_of;                       
            }, 
            'width' => '14%',        
        ],  
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => $template,
            
            'dropdown' => false,
            'vAlign'=>'middle',
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





}
else // es un admin empresa
{
    $template='{view} {update}';
    return [  
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nombre_emp',
            'label' => 'Nombre Empleador',
            'value' => function ($model) {
                return $model->nombre;            
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Mds_rum_empleador::find()            
                ->orderBy(['nombre' => SORT_ASC])
                ->all(), 'id', 'nombre'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Empleador...'],
            'format' => 'raw',
            'width' => '15%',        
        ],    
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddomicilio2',
            'label' => 'Localidad',
            'value' => function ($model) {
                $iddomicilio = $model->iddomicilio;  
               
                $un_domicilio=Mds_rum_domicilio::findOne($iddomicilio);                   
                $una_localidad=Sds_com_localidad::findOne($un_domicilio->idlocalidad);
    
               //$una_configuracion=Mds_rum_configuracion::findOne($una_com_persona->documento_tipo);
                $cad=$una_localidad->descripcion;
                return $cad;        
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Sds_com_localidad::find()            
                ->orderBy(['descripcion' => SORT_ASC])
                ->all(), 'idlocalidad', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Localidad...'],
            'format' => 'raw',
            'width' => '15%',        
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'email2',
            'label' => 'Email',
            'value' => function ($model) {
                return $model->email;            
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Mds_rum_empleador::find()            
                ->orderBy(['email' => SORT_ASC])
                ->all(), 'email', 'email'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Email...'],
            'format' => 'raw',
            'width' => '15%',        
        ],  
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'telefono1',
            'label' => 'Telefono Principal',
            'width' => '14%',
        ], 
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'id_categoria2',
            'label' => 'Categoria',
            'value' => function ($model) {
    
                $id_categoria = $model->id_categoria; 
                $una_categoria=Sds_com_configuracion::findOne($id_categoria);                        
               //$una_configuracion=Mds_rum_configuracion::findOne($una_com_persona->documento_tipo);
                $cad=$una_categoria->descripcion;
                return $cad;                       
            }, 
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Sds_com_configuracion::find()   
                ->where(["activo" => 1])              
                ->andWhere(["idconfiguraciontipo"=> 53] )       
                ->orderBy(['descripcion' => SORT_ASC])
                ->all(), 'idconfiguracion', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Categoría...'],
            'format' => 'raw',
            'width' => '15%',        
        ], 
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => $template,
            
            'dropdown' => false,
            'vAlign'=>'middle',
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
}
