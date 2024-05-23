<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_documento;
use app\models\Sds_gis_capa_item;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'attribute' => 'inicio',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->inicio);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'filter' => false
    ],
    [
        'attribute' => 'fin',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fin);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapaitem',
        'value' => function ($model) {
            $idcapaitem = $model->idcapaitem;
            if ($idcapaitem != null) {
                $edificio = Sds_gis_capa_item::findOne($idcapaitem);
                return $edificio->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_gis_capa_item::find()->where("idcapa=12")->orderBy(['descripcion' => SORT_ASC])->all(), 'idcapaitem', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Edificio...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddocumento',
        'hAlign' => 'center',
        'value' => function ($model) {
            $iddocumento = $model->iddocumento;
            if ($iddocumento != null) {
                $documento = Mds_org_documento::findOne($iddocumento);
                //uploads/contactos/<legajo_apellido_nombre>/<idtipo_nombre_fecha>
                /*  $ruta = '@web/uploads/contactos/' . $contacto->legajo . '_' . $model->apellido 
                        . '_' . $persona->nombre . '/' . $documento['path'];                */
                return '<a href="' . '@web/' . $documento['path'] . '" title="Documento Adjunto" role="post" 
                data-pjax="0" target="_blank" data-toggle="tooltip" data-original-title="Documento Adjunto">Visualizar Documento Adjunto</a>';
                //return Html::a($documento['nombre'], Url::to('@web/'.$documento['path'], true), ['target' => '_blank']);
            }
            return "";
        },
        'filter' => false,
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Consultar', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
