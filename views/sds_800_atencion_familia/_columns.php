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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idllamada',
        'width' => '10%',
    ],
    [
        'attribute' => 'fecha_intervencion',
        //'header' => 'Organismo',
        'width' => '12%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_intervencion);
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
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'lugar_intervencion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'lugar_especificacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'defensora',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'edad',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idpersona_referente',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'parentezco',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'alojado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'hogar',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'dia_hora',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'operador',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'equipo_tecnico',
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
        // 'attribute'=>'establecimiento',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'trabaja',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tipo_trabajo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'atendido',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'institucion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'nombre_profesionales',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'beneficio_social',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'area_beneficio',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'centro_salud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'nombre_centro_salud',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'obra_social',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'nombre_obra_social',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tratamiento_medico',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tratamiento_institucion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'orientado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'intoxicado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'violentado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'plan_accion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_intervencion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idusuario',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update}',
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
    ],

];   