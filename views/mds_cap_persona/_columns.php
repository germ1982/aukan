<?php

use app\models\Mds_cap_instancia;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_persona;

$idcontacto  = Yii::$app->user->identity->idcontacto;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $persona = Sds_com_persona::findOne($persona);
                return $persona->documento;
            }
            return "";
        }
    ],

    /*  [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombrecompuesto',

    ],*/

    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Nombre y Apellido',
        'attribute' => 'nombrecompuesto',
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro de eliminar este item?'
        ],
    ],

];
