<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_oficina;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_bdc_equipo;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_plan;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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
        'attribute' => 'numero',
        'width' => '7%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'organismo_padre',
        'value' => function ($model) {
            $idorg = $model->organismo_padre;
            if ($idorg != null) {
                $organismo = Mds_org_organismo::findOne($idorg);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo::find()
                ->where('idorganismo in (select organismo_padre from sds_cel_linea)')
                ->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '20%'
    ],
    /*
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ultimo_importe',
        'label' => 'Última Factura',
        'value' => function ($model) {
            $ultimo_importe = Sds_cel_linea::findBySql(
                "SELECT SUM(i.cantidad) AS ultimo_importe 
                FROM sds_cel_factura f 
                INNER JOIN sds_cel_factura_item i ON f.idfactura = i.idfactura 
                WHERE f.fecha_carga = (SELECT max(fecha_carga) FROM sds_cel_factura) AND i.linea = $model->numero")->all();
            return $ultimo_importe[0]->ultimo_importe;
        },
        'width' => '10%'
    ],
    */
    /*
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idplan',
        'value' => function ($model) {
            $idplan = $model->idplan;
            if ($idplan != null) {
                $plan = Sds_cel_plan::findOne($idplan);
                return $plan->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_cel_plan::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idplan', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Plan...'],
        'format' => 'raw',
        'width' => '20%'
    ],
    */
    /*
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'equipo_tipo',
        'value' => function ($model) {
            switch ($model->equipo_tipo) {
                case Sds_cel_linea::TIPO_DESCONOCIDO:
                    return "Desconocido";
                case Sds_cel_linea::TIPO_OBSOLETO:
                    return "Obsoleto";
                case Sds_cel_linea::TIPO_GAMA_BAJA:
                    return "Gama Baja";
                case Sds_cel_linea::TIPO_GAMA_MEDIA:
                    return "Gama Media";
                case Sds_cel_linea::TIPO_GAMA_ALTA:
                    return "Gama Alta";
                case Sds_cel_linea::TIPO_SIN_EQUIPO:
                    return "Sin Equipo";
                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Sds_cel_linea::TIPO_DESCONOCIDO => "Desconocido",
            Sds_cel_linea::TIPO_OBSOLETO => "Obsoleto",
            Sds_cel_linea::TIPO_GAMA_BAJA => "Gama Baja",
            Sds_cel_linea::TIPO_GAMA_MEDIA => "Gama Media",
            Sds_cel_linea::TIPO_GAMA_ALTA => "Gama Alta",
            Sds_cel_linea::TIPO_SIN_EQUIPO => "Sin Equipo",
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Equipo...'],
        'label'=>'Gama Equipo',
        'width' => '10%'
    ],
    */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcontacto',
        'value' => function ($model) {
            $equipo=Sds_bdc_equipo::findOne($model->idequipo);
            if (isset($equipo->responsable) && $equipo->responsable!= null) {
                $contacto = Mds_org_contacto::findBySql(
                    "SELECT * FROM mds_org_contacto c
                    JOIN sds_com_persona p ON p.idpersona=c.idpersona
                    WHERE idcontacto=".$equipo->responsable)->one();
                return $contacto->nombre . " " . $contacto->apellido;
            }else{
                if($model->idcontacto!=null){
                    $contacto = Mds_org_contacto::findBySql(
                        "SELECT * FROM mds_org_contacto c
                        JOIN sds_com_persona p ON p.idpersona=c.idpersona
                        WHERE idcontacto=".$model->idcontacto)->one();
                    return $contacto->nombre . " " . $contacto->apellido;
                }
            }
            return "- SIN DATOS -";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_contacto::findBySql(
                "SELECT 0 AS idcontacto, '- SIN DATOS ' AS nombre, '' AS apellido, '' AS legajo
                UNION
                (SELECT c.idcontacto, p.nombre, p.apellido, c.legajo
                    FROM sds_cel_linea l
                    LEFT JOIN sds_bdc_equipo e ON l.idequipo=e.idequipo
                    LEFT JOIN  mds_org_contacto c ON e.responsable=c.idcontacto
                    LEFT JOIN sds_com_persona p ON c.idpersona=p.idpersona
                    WHERE l.idequipo IS NOT NULL
                    ORDER BY nombre ASC, apellido ASC)
                UNION
                (SELECT c.idcontacto, p.nombre, p.apellido, c.legajo
                FROM sds_cel_linea l
                    LEFT JOIN  mds_org_contacto c ON l.idcontacto=c.idcontacto
                    LEFT JOIN sds_com_persona p ON c.idpersona=p.idpersona
                    ORDER BY nombre ASC, apellido ASC)")
                    ->all(),
                // ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
            'idcontacto',
            function ($model) {
                return $model->nombre.' '.$model->apellido." - ".$model->legajo;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
        'width' => '25%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'value' => function ($model) {
            $equipo = Sds_bdc_equipo::findOne($model->idequipo);
            if ($equipo != null) {
                $organismo = Mds_org_organismo::findOne($equipo->idorganismo);
                if($organismo!=null){
                    return $organismo->descripcion;
                }
            }else{
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                if($organismo!=null){
                    return $organismo->descripcion;
                }
            }
            return "- SIN DATOS -";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '25%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $equipo = Sds_bdc_equipo::findOne($model->idequipo);
            if ($equipo != null) {
                $contacto = Mds_org_contacto::findOne($equipo->responsable);
            }else{
                $contacto = Mds_org_contacto::findOne($model->idcontacto);
            }
            if($contacto!=null){
                $dispositivo=Mds_org_dispositivo::findOne($contacto->iddispositivo);
                $organismo=Mds_org_organismo::findOne($dispositivo->idorganismo);
                if($dispositivo!=null && $organismo!=null){
                    return "$dispositivo->descripcion ($organismo->abreviatura)";
                }
            }
            return "- SIN DATOS -";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_dispositivo::findBySql(
                "SELECT d.* 
                FROM sds_cel_linea l
                JOIN sds_bdc_equipo e ON l.idequipo=e.idequipo
                JOIN mds_org_contacto c ON e.responsable=c.idcontacto
                JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                WHERE NOT isnull(l.idequipo)
                
                UNION
                
                SELECT d.* 
                FROM sds_cel_linea l
                JOIN mds_org_contacto c ON l.idcontacto=c.idcontacto
                JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                WHERE isnull(l.idequipo)
                "
            )->all(),
            'iddispositivo',
            function($model){
                $organismo=Mds_org_organismo::findOne($model->idorganismo);
                if($organismo!=null){
                    return "$model->descripcion ($organismo->abreviatura)";
                }
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Dispositivo...'],
        'format' => 'raw',
        'width' => '25%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_ultimo_movimiento',
        'label' => 'Estado',
        'value' => function($model){
            if($model->ultimo_movimiento!=null){
                return $model->ultimo_movimiento;
            }else{
                return "S/MOVIMIENTOS";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterEstado,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => '25%'
    ],
    /*
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            switch ($model->activo) {
                case Sds_cel_linea::ACTIVO_ACTIVO;
                    return "Activo";
                case Sds_cel_linea::ACTIVO_BAJA:
                    return "Baja";
                case Sds_cel_linea::ACTIVO_SUSPENSION_POR_ROBO:
                    return "Susp.Robo";
                case Sds_cel_linea::ACTIVO_SUSPENSION_DESCONOCIDO:
                    return "Susp.Desc.";
                case Sds_cel_linea::ACTIVO_LINEA_DISPONIBLE:
                    return "Línea Disponible";
                    break;
                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Sds_cel_linea::ACTIVO_ACTIVO => "Activo",
            Sds_cel_linea::ACTIVO_BAJA => "Baja",
            Sds_cel_linea::ACTIVO_SUSPENSION_POR_ROBO => "Susp.Robo",
            Sds_cel_linea::ACTIVO_SUSPENSION_DESCONOCIDO => "Susp.Desc.",
            Sds_cel_linea::ACTIVO_LINEA_DISPONIBLE => "Línea Disponible",
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => '7%',
    ],
    */
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {reporte}',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Está a punto de eliminar el registro',
            'data-confirm-message' => '¿Está seguro de esto?',
        ],
        'buttons' => [
            'reporte'=>function ($url, $model) {
                $url =  Url::to(['/sds_cel_linea/reporte_entrega_equipo', 'idlinea' => $model->idlinea]);
                return Html::a('<span class="far fa-clipboard"></span>', $url, [
                    'title' => "Reporte de entrega",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'width' => '5%',
    ],
];
