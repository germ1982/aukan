<?php

use app\models\Configuracion;
use app\models\Organismo;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;



$mysql_sectores = "SELECT DISTINCT
        d.iddispositivo,
        CONCAT('Decreto: ',decr.descripcion, ' - ', o.abreviatura, ' - ', d.descripcion) AS descripcion

    FROM organismo o

    JOIN organismo_dispositivo d
        ON d.idorganismo = o.idorganismo

    JOIN empleado e
        ON e.iddispositivo = d.iddispositivo

    JOIN organismo_org_dec od
        ON od.idorganismo = o.idorganismo
        
	JOIN organismo_decreto decr
        ON decr.iddecreto = od.iddecreto

    ORDER BY decr.periodo_inicio desc, o.abreviatura, d.descripcion
";
$mysql_funciones = "SELECT * from configuracion c
                    where c.id_configuracion in (select funcion from empleado)
                    order by c.descripcion";
return [

    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
    ], */
    [
        'attribute' => 'idpersona',
        'value' => function ($model) {
            $foto = Html::img(
                'img/empleados-fotos/' . $model->foto,
                [
                    'class' => 'imagen-avatar-grilla',
                    'width' => '25',
                    'height' => '25'
                ]
            );

            return $foto . ' ' .
                $model->persona->apellido . ' ' .
                $model->persona->nombre;
        },
        'filterInputOptions' => [
            'class' => 'form-control',
            'placeholder' => 'Buscar persona...'
        ],
        'format' => 'raw',
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
                //$dispositivo = OrganismoDispositivo::get_dispositivo_pro($id);
                $dispositivo = OrganismoDispositivo::get_dispositivo($id);
                if ($dispositivo != null) {
                    return $dispositivo->descripcion;
                }
                return "(sin dispositivo)";
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
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view} {update} ',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro de eliminar este item?'
        ],
    ],

];
