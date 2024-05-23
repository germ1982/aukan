<?php

use app\models\Sds_com_configuracion;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'doc_tipo_num',
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'parentezco',
        'value' => function ($model) {
            return Sds_com_configuracion::findOne($model->parentezco)->descripcion;
        },
        'filter' => false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => ' {view} {update} {delete}',
        'vAlign' => 'middle',
        'buttons' => [
            'view' => function ($url, $searchModel) use ($oficial) {
                $url =  Url::to(['/sds_ris_persona/view', 'id' => $searchModel->idpersonarisneu, 'oficial' => $oficial]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $searchModel) use ($view, $oficial) {
                if (!$view) {
                    $url =  Url::to(['/sds_ris_persona/update', 'id' => $searchModel->idpersonarisneu, 'oficial' => $oficial]);
                    return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'id' => 'btn_update_persona'
                    ]);
                }
            },
            'delete' => function ($url, $searchModel) use ($view) {
                if (!$view) {
                    $url =  Url::to(['/sds_ris_persona/delete', 'id' => $searchModel->idpersonarisneu]);
                    $parentezco_jefe = Sds_com_configuracion::findBySql("(select idconfiguracion from sds_com_configuracion where idconfiguraciontipo=11 limit 1)")->one()->idconfiguracion;
                    return $searchModel->parentezco == $parentezco_jefe ? '' :
                        Html::a('<span class= "fas fa-ban"></span>', $url, [
                            'role' => 'modal-remote', 'title' => 'Eliminar',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => '¿Está segura/o?',
                            'data-confirm-message' => '¿Está segura/o de querer eliminar la persona?'
                        ]);
                }
            }
        ]
    ],

];
