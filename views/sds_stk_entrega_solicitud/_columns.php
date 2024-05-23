<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use app\models\Sds_stk_entrega_solicitud;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use Mpdf\Language\ScriptToLanguage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$model = new Sds_stk_entrega_solicitud();
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
            return date('d/m/Y H:i:s', strtotime($model->fecha_hora));
        },
        'width' => '13%',
        'attribute' => 'fecha_hora',
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
        'attribute' => 'idcontacto',
        'value' => function ($model) {
            $responsable = Mds_org_contacto::findBySql(
                "SELECT c.*, CONCAT(p.apellido,', ', p.nombre) nombre FROM mds_org_contacto c
                JOIN sds_com_persona p ON c.idpersona=p.idpersona
                WHERE c.idcontacto=" . $model->idcontacto
            )->one();
            return $responsable->nombre;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_contacto::findBySql(
                "SELECT c.*, CONCAT(p.apellido,', ', p.nombre) nombre FROM sds_stk_entrega_solicitud es
                JOIN mds_org_contacto c ON es.idcontacto=c.idcontacto
                JOIN sds_com_persona p ON c.idpersona=p.idpersona
                WHERE c.idcontacto IN (SELECT idcontacto FROM sds_stk_entrega_solicitud) GROUP BY c.idcontacto"
            )->all(),
            'idcontacto',
            function ($contacto) {
                return $contacto->nombre;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'value' => function ($model) {
            $responsable = Sds_com_persona::findOne($model->idpersona);
            if ($responsable != null) {
                return $responsable->nombre . ', ' . $responsable->apellido;
            }
            return '- SIN DATOS -';
        },
        'label' => 'Destinatario',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            $persona = Sds_com_persona::findBySql("SELECT * FROM sds_stk_entrega_solicitud WHERE idpersona GROUP BY idpersona")->all(),
            'idpersona',
            function ($persona) {
                // if (isset($persona_datos) && ($persona != $persona_datos)) {
                //     $destinatario = Sds_com_persona::findOne($persona_datos->idpersona);
                //     if ($destinatario != null) {
                //         return $destinatario->nombre . ', ' . $destinatario->apellido;
                //     }
                // }
                $destinatario = Sds_com_persona::findOne($persona->idpersona);
                if ($destinatario != null) {
                    return $destinatario->nombre . ', ' . $destinatario->apellido;
                }
                return '- SIN DATOS -';
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Destinatario...'],
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\BooleanColumn',
        'trueLabel' => 'Entregado',
        'falseLabel' => 'Pendiente',
        'attribute' => 'identrega',
        'useSelect2Filter' => 'Estado...',
        //'filterType'=>GridView::FILTER_SELECT2,
        'value' => function ($model) {
            if ($model->identrega != null) {
                return true;
            } else {
                return false;
            }
        },
        'label' => 'Estado',
        'width' => '70px',
        'filterInputOptions' => ['placeholder' => 'Estado...'],
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'observaciones',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'dni',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'identrega',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{create} {view} {update} ',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => '5%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'create' => function ($url, $model) {
                $url =  Url::to([
                    'sds_stk_entrega_solicitud/manage_items',
                    'idsolicitud' => $model->identregasolicitud
                ]);
                return Html::a('<span class= "fas fa-clipboard-list"></span>', $url, [
                    'title' => "Añadir Item",
                    'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]); 
            },
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver solicitudes', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
    ],
];
?>
<script>
    BooleanColumn.getattributes
</script>