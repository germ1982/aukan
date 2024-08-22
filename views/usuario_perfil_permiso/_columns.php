<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [


      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idperfil',
            'value' => function ($model) {
                  $id = $model->idperfil;
                  if ($id != null) {
                        $dato = Configuracion::findOne($id);
                        return "$dato->descripcion";
                  }
                  return "";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO), 'id_configuracion', 'descripcion'),
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Perfil...'],
            'format' => 'raw',
            'width' => '20%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idtipopermiso',
            'value' => function ($model) {
                  $id = $model->idtipopermiso;
                  if ($id != null) {
                        $dato = Configuracion::findOne($id);
                        return "$dato->descripcion";
                  }
                  return "";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO_TIPO_DE_PERMISO), 'id_configuracion', 'descripcion'),
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Tipo...'],
            'format' => 'raw',
            'width' => '20%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idacceso',
            'width' => '20%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'descripcion',
            'width' => '30%',
      ],
      [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'width' => '10%',
            'vAlign' => 'middle',
            'urlCreator' => function ($action, $model, $key, $index) {
                  return Url::to([$action, 'id' => $key]);
            },
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => [
                  'role' => 'modal-remote',
                  'title' => 'Delete',
                  'data-confirm' => false,
                  'data-method' => false, // for overide yii data api
                  'data-request-method' => 'post',
                  'data-toggle' => 'tooltip',
                  'data-confirm-title' => 'Are you sure?',
                  'data-confirm-message' => 'Are you sure want to delete this item'
            ],
      ],

];
