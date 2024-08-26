<?php

use app\models\Empleado;
use app\models\Persona;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$mysql_personas = "SELECT p.idpersona, concat(p.apellido,' ', p.nombre) as nombre from personas p 
                    where p.idpersona in (select idpersona from usuarios) order by p.apellido, p.nombre";

return [

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idwebempleado',
            'width' => '5%',
      ],

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idempleado',
            'value' => function ($model) {
                  $empleado = Empleado::findOne($model->idempleado);
                  $id = $empleado->idpersona;
                  if ($id != null) {
                        $persona = Persona::findOne($id);
                        $foto = 'img/empleados-fotos/' . $empleado->foto;
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
            'attribute' => 'orden',
            'width' => '10%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'activo',
            'value' => function ($model) {
                  return $model->activo == 1 ? 'Si' : 'No';
            },
            'filter' => ['0' => 'No', '1' => ' Si'],
            'width' => '10%',
      ],

      [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
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
            'width' => '10%',
      ],

];
