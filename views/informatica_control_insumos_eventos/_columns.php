<?php

use app\models\Empleado;
use app\models\InformaticaControlInsumosEventos;
use app\models\OrganismoDispositivo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;

$searchModel = $searchModel ?? null;

// Layout para el DatePicker Rango
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

// Consultas SQL para filtros
$empleados_sql = "SELECT e.idempleado, CONCAT(p.apellido,' ', p.nombre) as descripcion 
                  FROM empleado e
                  JOIN personas p ON p.idpersona = e.idpersona
                  WHERE e.idempleado IN (SELECT idsolicitante FROM informatica_control_insumos_eventos)
                  ORDER BY p.apellido, p.nombre";
$empleados = Empleado::findBySql($empleados_sql)->all();

$sectores_sql = "SELECT d.iddispositivo, CONCAT(e.descripcion_fija,' - ', eo.descripcion, ' - ', o.abreviatura, ' - ', d.descripcion) as descripcion
                 FROM organismo_dispositivo d
                 JOIN organismo o ON o.idorganismo = d.idorganismo
                 JOIN edificio_oficina eo ON eo.idoficina = d.idoficina
                 JOIN edificio e ON e.idedificio = eo.idedificio
                 WHERE d.iddispositivo IN (SELECT idsector_solicitante FROM informatica_control_insumos_eventos)
                 ORDER BY e.descripcion_fija, eo.descripcion, o.abreviatura, d.descripcion";
$sectores = OrganismoDispositivo::findBySql($sectores_sql)->all();

$responsables_sql = "SELECT e.idempleado, CONCAT(p.apellido,' ', p.nombre) as descripcion 
                     FROM empleado e
                     JOIN personas p ON p.idpersona = e.idpersona
                     WHERE e.idempleado IN (SELECT idresponsable FROM informatica_control_insumos_eventos)
                     ORDER BY p.apellido, p.nombre";
$responsables = Empleado::findBySql($responsables_sql)->all();

// Anchos de columna
$columna_fecha = '10%';
$columna_solicitante = '16%';
$columna_sector = '20%';
$columna_destino = '14%';
$columna_responsable = '15%';
$columna_asistentes = '10%';
$columna_estado = '10%';
$columna_acciones = '5%';

return [
    // 1. Fecha (Con filtro de rango desde/hasta)
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha',
        'width' => $columna_fecha,
        'value' => function ($model) {
            return $model->fecha ? date_format(date_create($model->fecha), 'd/m/Y') : '';
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
                'format' => 'dd/mm/yyyy',
                'autoclose' => true
            ]
        ])
    ],
    // 2. Solicitante
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idsolicitante',
        'width' => $columna_solicitante,
        'value' => function ($model) {
            if ($model->idsolicitante) {
                $empleado = Empleado::get_empleado($model->idsolicitante);
                return $empleado ? $empleado->descripcion : '';
            }
            return '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($empleados, 'idempleado', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Solicitante...'],
        'format' => 'raw',
    ],
    // 3. Sector
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idsector_solicitante',
        'width' => $columna_sector,
        'value' => function ($model) {
            if ($model->idsector_solicitante) {
                $dispositivo = OrganismoDispositivo::get_dispositivo_pro($model->idsector_solicitante);
                return $dispositivo ? $dispositivo->descripcion : '';
            }
            return '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($sectores, 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Sector...'],
        'format' => 'raw',
    ],
    // 4. Destino
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'destino_prestamo',
        'width' => $columna_destino,
    ],
    // 5. Responsable
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idresponsable',
        'width' => $columna_responsable,
        'value' => function ($model) {
            if ($model->idresponsable) {
                $empleado = Empleado::get_empleado($model->idresponsable);
                return $empleado ? $empleado->descripcion : '';
            }
            return '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($responsables, 'idempleado', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
    ],
    // 6. Asistentes (Avatares)
    // 6. Asistentes (Avatares estilizados)
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Asistentes',
        'format' => 'raw',
        'width' => $columna_asistentes,
        'value' => function ($model) {
            $sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                    FROM informatica_control_insumos_eventos_asistente a
                    JOIN empleado e ON a.idtecnico = e.idempleado
                    JOIN personas p ON p.idpersona = e.idpersona
                    WHERE a.identrega = {$model->identrega}";

            $asistentes = \Yii::$app->db->createCommand($sql)->queryAll();

            if (empty($asistentes)) return '<span style="color: #666;">-</span>';

            $html = '<div style="display:flex; gap:4px; flex-wrap:wrap;">';
            foreach ($asistentes as $a) {
                $src = $a['foto']
                    ? Url::base(true) . '/img/empleados-fotos/' . $a['foto']
                    : Url::base(true) . '/img/empleados-fotos/default.jpg';
                $urlView = Url::to(['empleado/view', 'id' => $a['idempleado']]);
                $html .= '<a href="' . $urlView . '" role="modal-remote" title="' . Html::encode($a['nombre']) . '">';
                $html .= '<img src="' . $src . '" class="imagen-avatar-grilla" style="width:24px; height:24px; border-radius:50%; object-fit:cover; border: 1px solid #00bfff; box-shadow: 0 0 5px rgba(0, 191, 255, 0.6); cursor:pointer;">';
                $html .= '</a>';
            }
            $html .= '</div>';
            return $html;
        },
    ],
    // 7. Estado
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => $columna_estado,
        'value' => function ($model) {
            return InformaticaControlInsumosEventos::getEstadoTexto($model->estado);
        },
        'filter' => InformaticaControlInsumosEventos::getEstadosMap(),
    ],
    // Acciones
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => $columna_acciones,
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'template' => '{view} {update} ',
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Está seguro?',
            'data-confirm-message' => '¿Está seguro que desea eliminar este ítem?'
        ],
    ],
];