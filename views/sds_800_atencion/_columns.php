<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_800_llamada;
use app\models\Sds_800_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
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
        'attribute' => 'idllamada',
        'width' => '10%',
    ],
    [
        'attribute' => 'fecha_hora',
        //'header' => 'Organismo',
        'width' => '12%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
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
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = $model->idusuario;
            if ($usuario != null) {
                $user = Mds_seg_usuario::findOne($usuario);
                return $user->user;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_seg_usuario::find()->where("idusuario in (select idusuario from sds_800_atencion)")->orderBy(['user' => SORT_ASC])->all(), 'idusuario', 'user'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Nombre Persona Afectada',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $pers_800 = Sds_800_persona::findOne($persona);
                $pers = Sds_com_persona::findOne($pers_800->idpersona);
                return $pers->nombre;
            }
            return "";
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Documento Persona Afectada',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $pers_800 = Sds_800_persona::findOne($persona);
                $pers = Sds_com_persona::findOne($pers_800->idpersona);
                $tipo_doc = Sds_com_configuracion::findOne($pers->documento_tipo);

                return $tipo_doc->descripcion.' '.$pers->documento;
            }
            return "";
        },
    ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'edad',
    //     'width' => '12%'
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'beneficio',
    //     'value' => function ($model) {
    //         $value = null;
    //         switch ($model->beneficio) {
    //             case 0:
    //                 $value = "No";
    //                 break;
    //             case 1:
    //                 $value = "Si";
    //                 break;
    //             default:
    //                 $value = "Sin Datos";
    //                 break;
    //         }
    //         return $value;
    //     },
    //     'filterType' => GridView::FILTER_SELECT2,
    //     'filter' => ["No","Si","Sin Datos"],
    //     'filterWidgetOptions' => [
    //         'pluginOptions' => ['allowClear' => true],
    //     ],
    //     'filterInputOptions' => ['placeholder' => 'Beneficio...'],
    //     'format' => 'raw',
    //     'width' => '20%'
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'causa_situacion',
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'trabajo',
    //     'width' => '12%'
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'sabe_leer',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'nivel_estudio',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'trabajo_detalle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'antiguedad',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'ubicacion_anterior',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'ubicacion_anterior_detalle',
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'atencion_anterior'
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'atencion_anterior_institucion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'atencion_anterior_profesional',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'asistencia_estado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'asistencia_estado_detalle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'familia',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'sentimiento',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'orientado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'evaluacion_funcional',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'evaluacion_funcional_detalle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'intoxicado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'alucinaciones',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'violentado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'expresar',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'tratamiento',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'tratamiento_institucion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'tratamiento_profesional',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'observaciones',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'persona_datos',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'idusuario',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => [ 'data-pjax' => 0, 'role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => [ 'data-pjax' => 0, 'role' => 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Borrar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Está Seguro?',
            'data-confirm-message' => '¿Está seguro que desea eliminar este elemento?'
        ],
    ],

];
