<?php

use yii\helpers\Url;
use app\models\Sds_com_localidad;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Mds_ts_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtspersona',
        'label' => 'Id'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_beneficiario',
        'label' => 'Beneficiario',
        'value' => function ($model) {
            return $model->tipo_beneficiario ? '<i class="fas fa-building"></i> &nbsp; Institución' : '<i class="fas fa-user"></i> &nbsp; Persona';
        },
        'filter' => [0 => "Persona", 1 => "Institución"],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => ''
        ],
        'format' => 'raw',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'campania',
        'label' => 'Campaña',
        'value' => function ($model) {
            return Sds_com_configuracion::findOne($model->campania)->descripcion;
        },
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_TS_CAMPANIA), 'idconfiguracion', 'descripcion'),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => ''
        ],
        'width' => '7%',
    ],
    /*********** CAMPOS OCULTOS, SE DESCARGAN EN EL CSV   *********/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nro_persona',
        'label' => 'Nro Persona',
        'width' => '8%',
        'value' => function ($model) {
            return $model->nro_persona ? $model->nro_persona : '';
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_hora',
        'hidden' => true,
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
        'hidden' => true,
        'label' => 'Email'
    ],
    /***************************************************************/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'Dni',
        'width' => '9%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'label' => 'Nombres',
        'width' => '12%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'label' => 'Apellidos',
        'width' => '12%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'domicilio',
        'hidden' => true
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
        'width' => '8%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_institucion',
        'width' => '15%',
        'value' => function ($model) {
            return $model->nombre_institucion ? $model->nombre_institucion : '';
        },
    ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'idlocalidad',
    //     'label' => 'Localidad',
    //     'value' => function ($model) {                             
    //         $una_localidad=Sds_com_localidad::findOne($model->idlocalidad);           
    //         $cad=$una_localidad->descripcion;
    //         return $cad;        
    //     }, 
    //     'filterType' => GridView::FILTER_SELECT2,
    //     'filter' => ArrayHelper::map(
    //         Sds_com_localidad::find()->where(['idprovincia' => 58])        
    //         ->orderBy(['descripcion' => SORT_ASC])
    //         ->all(), 'idlocalidad', 'descripcion'),
    //     'filterWidgetOptions' => [
    //         'pluginOptions' => ['allowClear' => true],
    //     ],
    //     'filterInputOptions' => ['placeholder' => 'Localidad...'],
    //     'format' => 'raw',
    //     'width' => '15%',        
    // ],    
    [

        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '32%',
        'value' => function ($model) {
            switch ($model->estado) {
                case Mds_ts_persona::SOLICITUD:
                    return "Pendiente de Evaluación";
                case Mds_ts_persona::ACEPTADA:
                    return "Aceptada";
                case Mds_ts_persona::RECHAZADA:
                    return "Rechazada";
            }
        },
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Mds_ts_persona::SOLICITUD => "Pendiente de Evaluación",
            Mds_ts_persona::ACEPTADA => "Aceptada",
            Mds_ts_persona::RECHAZADA => "Rechazada",

        ],
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => ''
        ],
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
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Eliminar Registro',
            'data-confirm-message' => 'Esta seguro de eliminar este registro de tarifa social?'
        ],
    ],

];
