<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'value' => function ($model) {
            return $model->nombre . " " . $model->apellido;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'attribute' => 'fecha_nacimiento',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_nacimiento);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nacionalidad',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->nacionalidad0)) {
                $value = $model->nacionalidad0->descripcion;
            }
            return $value;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->genero0)) {
                $value = $model->genero0->descripcion;
            }
            return $value;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_com_persona/view_hijo', 'id' => $model->idpersona]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Consultar Datos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_com_persona/delete_hijo', 'id' => $model->idpersona]);
                return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Eliminar Hijo',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-pjax' => 1,
                    'title' => ('Borrar'),
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este elemento?',
                        'method' => 'post',
                    ],
                ]);
            },
        ],
        'visibleButtons' => [
            'update' => function ($model) use ($hasRolAdminGeneral, $estaAtendida) {
                return ($estaAtendida || $hasRolAdminGeneral);
            },
            'delete' => function ($model) use ($hasRolAdminGeneral, $estaAtendida) {
                return ($estaAtendida || $hasRolAdminGeneral);
            },
        ],
    ],

];
