<?php

use app\models\Configuracion;
use app\models\Organismo;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;



$mysql_personas = "SELECT p.idpersona, concat(p.apellido,' ', p.nombre) as nombre from personas p 
                    where p.idpersona in (select idpersona from usuarios) order by p.apellido, p.nombre";

$mysql_sectores = "SELECT d.iddispositivo as iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
                    from organismo o 
                    join organismo_dispositivo d on d.idorganismo = o.idorganismo
                    where d.iddispositivo in (select iddispositivo from empleado) order by o.abreviatura, d.descripcion";

$mysql_funciones = "SELECT * from configuracion c
                    where c.id_configuracion in (select funcion from empleado)
                    order by c.descripcion";
return [

    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'value' => function ($model) {
            $id = $model->idpersona;
            if ($id != null) {
                $persona = Persona::findOne($id);
                $foto = 'img/empleados-fotos/' . $model->foto;
                $foto = Html::img($foto, ['alt' => 'foto', 'class' => 'imagen-avatar-grilla', 'width' => '25', 'height' => '25']);
                return "$foto   $persona->apellido $persona->nombre";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Persona::findBySql($mysql_personas)->all(), 'idpersona', 'nombre'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $id = $model->iddispositivo;
            if ($id != null) {
                $dispositivo = OrganismoDispositivo::findOne($id);
                $organismo = Organismo::findOne($dispositivo->idorganismo);
                return "$organismo->abreviatura - $dispositivo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(OrganismoDispositivo::findBySql($mysql_sectores)->all(), 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => '30%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'funcion',
        'value' => function ($model) {
            $id = $model->funcion;
            if ($id != null) {
                $funcion = Configuracion::findOne($id);
                return "$funcion->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::findBySql($mysql_funciones)->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => '20%',
    ],
   /*  [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idempleado',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddispositivo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'legajo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
    ], */
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'telefono',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'foto',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'activo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'categoria',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'antiguedad_legal',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'antiguedad_total',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ingreso_real',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'ingreso_administrativo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'contratacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'cuil',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'funcion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fichado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'afiliacion',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{view} {update} ',
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