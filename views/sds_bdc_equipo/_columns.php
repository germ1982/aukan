<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'checkboxOptions' => function ($data) {
            return ['value' => $data->idequipo];
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idequipo',
        'value'=>function($model){
            return str_pad($model->idequipo,6,"0", STR_PAD_LEFT);
        },
        'label'=>'# Equipo',
        'width'=>'7%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo',
        'label'=>'Tipo Equipo',
        'value'=>function($model){
            $tipo=Sds_com_configuracion::findOne($model->tipo);
            if($tipo!=null){
                return $tipo->descripcion;
            }
            return "S/D";
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_TIPO_EQUIPO),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo Equipo...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'marca',
        'value'=>function($model){
            $marca=Sds_com_configuracion::findOne($model->marca);
            if($marca!=null){
                return $marca->descripcion;
            }
            return "S/D";
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_com_configuracion::findBySql('SELECT c.idconfiguracion, c.descripcion FROM sds_com_configuracion c
             JOIN sds_bdc_equipo e ON c.idconfiguracion=e.marca GROUP BY c.idconfiguracion')->all(),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Marca...'],
        'format' => 'raw',
        'width' => '9%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'matricula',
        'width' => '8%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idorganismo',
        'value'=>function($model){
            $sector=Mds_org_organismo::findOne($model->idorganismo);
            if($sector!=null){
                return $sector->abreviatura;
            }
            return "-SIN DATOS-";
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Mds_org_organismo::findBySql('SELECT o.idorganismo, o.abreviatura FROM mds_org_organismo o 
                JOIN sds_bdc_equipo e ON o.idorganismo=e.idorganismo GROUP BY o.idorganismo')->all(),
            'idorganismo',
            'abreviatura'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Sector...'],
        'format' => 'raw',
        'width' => '15%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'responsable',
        'value'=>function($model){
            $contacto=Mds_org_contacto::findOne($model->responsable);
            if($contacto!=null){
                $responsable=Sds_com_persona::findOne($contacto->idpersona);
                return $responsable->apellido.' '.$responsable->nombre;
            }
            return "-SIN DATOS-";
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_bdc_equipo::find()->groupBy('responsable')->all(),
            'responsable',
            function($model){
                if ($model->responsable != null) {
                    $contacto = Mds_org_contacto::findOne($model->responsable);
                    if ($contacto->idpersona != null) {
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        $aux = $persona->apellido.", ".$persona->nombre." - Leg.: ".$contacto->legajo;
                        return $aux;
                    }
                }
                return "";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
        'width'=>'20%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'usuario',
        'value'=>function($model){
            $contacto=Mds_org_contacto::findOne($model->usuario);
            if($contacto!=null){
                $usuario=Sds_com_persona::findOne($contacto->idpersona);
                return $usuario->apellido.' '.$usuario->nombre;
            }
            return "-SIN DATOS-";
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_bdc_equipo::find()->groupBy('usuario')->all(),
            'usuario',
            function($model){
                if ($model->usuario != null) {
                    $contacto = Mds_org_contacto::findOne($model->usuario);
                    if ($contacto->idpersona != null) {
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        $aux = $persona->apellido.", ".$persona->nombre." - Leg.: ".$contacto->legajo;
                        return $aux;
                    }
                }
                return "";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width'=>'20%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ip',
        'label'=>'IP',
        'value'=>function($model){
            if($model->ip!=null){
                return $model->ip;
            }
            return '-';
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'label'=>'Estado',
        'value'=>function($model){
            if($model->estado!=null){
                $estado=Sds_com_configuracion::findOne($model->estado);
                return $estado->descripcion;
            }
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_MOVIMIENTO_TIPO),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width'=>'20%'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update} {historico-movimientos}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'historico-movimientos' => function ($url, $model) {
                $url =  Url::to(['/sds_bdc_movimiento_equipo', 'equipo' => $model->idequipo]);
                return Html::a('<span class= "fas fa-history"></span>', $url, [
                    'title' => "Historico de Movimientos",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
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