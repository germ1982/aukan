<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$permiso_persona = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::COM_PERSONA . ")")->one();
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'width' => '7%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nacionalidad',
        'value' => function ($model) {
            $pais = Sds_com_configuracion::findOne($model->nacionalidad);
            if ($pais != null) {
                return $pais->descripcion;
            }
            return 'SIN DATOS';
        },
        'width' => '5%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
        'value' => function ($model) {
            $genero = Sds_com_configuracion::findOne($model->genero);
            if ($genero != null) {
                return $genero->descripcion;
            }
            return 'SIN DATOS';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::findBySql(
                "SELECT * FROM sds_com_configuracion WHERE idconfiguracion IN (81,82)"
            )->all(),
            'idconfiguracion',
            'descripcion',
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Género...'],
        'width' => '5%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_nacimiento',
        'value' => function ($model) {
            return date('d/m/Y', strtotime($model->fecha_nacimiento));
        },
        'width' => '11%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'domicilio_calle',
        'value' => function ($model) {
            if ($model->domicilio_calle && $model->domicilio_numero != null) {
                return $model->domicilio_calle . ' - ' . $model->domicilio_numero;
            } else {
                return 'SIN DATOS';
            }
        },
        'label' => 'Domicilio'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'localidad',
        'label' => 'Localidad',
        'value' => function($model){
            if ($model->localidad!=null){
                return $model->localidad;
            }
            return 'SIN DATOS';
        }
    ],
    [
        'class' => '\kartik\grid\BooleanColumn',
        'trueLabel' => 'Si',
        'falseLabel' => 'No',
        'attribute' => 'georeferencia',
        'useSelect2Filter' => 'Georeferencia...',
        'value' => function ($model) {
            if ($model->latitud  != null && $model->longitud != null) {
                return true;
            } else {
                return false;
            }
        },
        'width' => '70px',
        'filterInputOptions' => ['placeholder' => 'Geo Referencia'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{create} {view}'.($permiso_persona->modifica?' {geo}':''),
        'buttons' => [
            'view' =>function ($url, $model) {
                $url =  Url::to([
                    'view', 'id' => $model->idpersona, 'current_url' => Url::current()
                ]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'title' => "Ver",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'geo' => function ($url, $model) {
                $url =  Url::to([
                    'georeferencia', 'id' => $model->idpersona, 'current_url' => Url::current()
                ]);
                return Html::a('<span class= "glyphicon glyphicon-globe"></span>', $url, [
                    'title' => "Ver Georeferencia",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'width' => '5%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        // 'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
    ],

];
