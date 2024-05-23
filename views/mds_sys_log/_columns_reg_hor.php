<?php

use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_hora',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),
        'width' => '18%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $idusuario = $model->idusuario;
            $usuario = Mds_seg_usuario::findOne($idusuario);
            return $usuario->user;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_seg_usuario::findBySql('SELECT u.* FROM mds_sys_log l JOIN mds_seg_usuario u ON u.idusuario=l.idusuario 
                GROUP BY l.idusuario ORDER BY u.user ASC')->all(),
            'idusuario',
            function ($model) {
                return $model->user;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Usuario...'],
        'format' => 'raw',
        'width' => '12%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'accion',
        'value' => function ($model) {
            switch ($model->accion) {
                case Mds_sys_log::ACCION_NUEVO:
                    return "Nuevo";
                case Mds_sys_log::ACCION_EDITAR:
                    return "Editar";
                case Mds_sys_log::ACCION_ELIMINAR:
                    return "Eliminar";
                case Mds_sys_log::ACCION_CONSULTA:
                    return "Consulta";
            }
        },
        'filter' => [
            Mds_sys_log::ACCION_NUEVO => 'Nuevo',
            Mds_sys_log::ACCION_EDITAR => 'Editar',
            Mds_sys_log::ACCION_CONSULTA => 'Consulta',
            Mds_sys_log::ACCION_ELIMINAR => 'Eliminar'
        ],
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'datos',
        'value' => function ($model) {
            $datos = json_decode($model->datos);
            $html_datos = "<div class=\"col-md-12\">";
            if (!empty($datos)) {
                $datos = is_array($datos) ? $datos : get_object_vars($datos);
                $parametros = array_keys($datos);
                foreach ($parametros as $param) {
                    $datos_param = $datos[$param];
                    if (is_array($datos_param)) {
                        $datos_param = implode(', ', $datos_param);
                    }
                    $html_datos = $html_datos . "<b>" . $param . ": </b>" . $datos_param . "<br>";
                }
                if (strlen($html_datos) > 250) {
                    $html_datos = substr($html_datos, 0, 250) . " (...)";
                }
            }
            return $html_datos."</div>";
        },
        'format' => 'html'
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    /*  [
        'class' => 'kartik\grid\ActionColumn',
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
    ], */

];
