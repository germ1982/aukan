<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\ConstantesGlobales;
use app\models\Empleado;
use app\models\Inventario;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoIncidencia;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use yii\helpers\Url;

/** @var mixed $searchModel */
$searchModel = $searchModel ?? null; // Asegúrate de que $searchModel esté definido
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$empleados_sql = "SELECT e.idempleado, CONCAT(p.apellido,' ', p.nombre) as descripcion FROM empleado e
                    join personas p on p.idpersona = e.idpersona
                    where e.idempleado in (SELECT idingresante from registro_tecnico_incidencia)
                    order by p.apellido, p.nombre";
$empleados = Empleado::findBySql($empleados_sql)->all();

$sectores_sql = "SELECT  d.iddispositivo,  CONCAT(e.descripcion_fija,' - ', eo.descripcion, ' - ', o.abreviatura, ' - ', d.descripcion) as descripcion
                from organismo_dispositivo d
                join organismo o on o.idorganismo = d.idorganismo
                join edificio_oficina eo on eo.idoficina = d.idoficina
                join edificio e on e.idedificio = eo.idedificio
                where d.iddispositivo in (SELECT iddispositivo from registro_tecnico_incidencia)
                order by e.descripcion_fija, eo.descripcion, o.abreviatura, d.descripcion";
$sectores = OrganismoDispositivo::findBySql($sectores_sql)->all();

$columna_1 = '10%';
$columna_2 = '26%';
$columna_3 = '10%';
$columna_4 = '33%';
$columna_5 = '8%';
$columna_6 = '8%';
$columna_7 = '5%';

return [

    // 1. Fecha de Ingreso (Primer Movimiento) con Rango de Fechas
    [
        'attribute' => 'fecha_ingreso',
        'label' => 'Fecha Ingreso',
        'width' => $columna_1,
        'value' => function ($model) {

            $primerMovimiento = RegistroTecnicoIncidenciaMovimiento::find()->where(['idincidencia' => $model->idincidencia])->orderBy(['idmovimiento' => SORT_ASC])->one();

            $fc = date_create($primerMovimiento->fecha);
            $fc = date_format($fc, 'd/m/Y');
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
                'format' => 'dd/mm/yyyy',
                'autoclose' => true
            ]
        ])

    ],

    [
        'attribute' => 'idinventario',
        'label' => 'Equipo',
        'width' => $columna_2,
        'value' => function ($model) {
            $value = "";
            $primerMovimiento = RegistroTecnicoIncidenciaMovimiento::find()->where(['idincidencia' => $model->idincidencia])->orderBy(['idmovimiento' => SORT_ASC])->one();
            $value = $primerMovimiento->descripcion;
            if (!empty($model->idinventario)) {
                $equipo = Inventario::get_item_inventario($model->idinventario);
                $value = "$equipo->descripcion $primerMovimiento->descripcion";
            }

            return $value;
        },
        'format' => 'raw',

    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idingresante',
        'width' => $columna_3,
        'value' => function ($model) {
            if ($model->idingresante) {
                $empleado = Empleado::get_empleado($model->idingresante);
                return "$empleado->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($empleados, 'idempleado', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Solicitante...'],
        'format' => 'raw',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => $columna_4,
        'format' => 'raw',
        'value' => function ($model) {
            if ($model->iddispositivo) {
                $dispositivo = OrganismoDispositivo::get_dispositivo_pro($model->iddispositivo);
                $url = \yii\helpers\Url::to(['organismo_dispositivo/view', 'id' => $model->iddispositivo]);
                return '<a href="' . $url . '" role="modal-remote" title="Ver sector">' . $dispositivo->descripcion . '</a>';
            }
            return '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($sectores, 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Sector...'],
    ],


    // 5. Estado (Leyendo estrictamente de ConstantesGlobales::ESTADOS_INCIDENCIAS)
    [
        'attribute' => 'idestado',
        'label' => 'Estado',
        'width' => $columna_5,
        'value' => function ($model) {
            return ConstantesGlobales::ESTADOS_INCIDENCIAS[$model->idestado]['nombre'] ?? '(Sin estado)';
        },
        'filter' => ArrayHelper::map(ConstantesGlobales::ESTADOS_INCIDENCIAS, 'id', 'nombre'),
        'headerOptions' => ['style' => 'width: 140px; text-align: center;'],
        'contentOptions' => ['style' => 'text-align: center;'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'asistencia',
        'width' => $columna_6,
        'headerOptions' => [
            'style' => 'color: #87b867; ',
        ],
        'format' => 'raw',
        'value' => function ($model) {
            $sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                                FROM registro_tecnico_incidencia_movimiento_asistencia a
                                JOIN empleado e ON a.idtecnico = e.idempleado
                                JOIN personas p ON p.idpersona = e.idpersona
                                JOIN registro_tecnico_incidencia_movimiento m on m.idmovimiento = a.idmovimiento
                    WHERE m.idincidencia = $model->idincidencia
                    group by a.idtecnico
                    order by p.apellido, p.nombre";

            $asistentes = \Yii::$app->db->createCommand($sql)->queryAll();

            if (empty($asistentes)) return '-';

            $html = '<div style="display:flex; gap:4px; flex-wrap:wrap;">';
            foreach ($asistentes as $a) {
                $src = $a['foto']
                    ? \yii\helpers\Url::base(true) . '/img/empleados-fotos/' . $a['foto']
                    : \yii\helpers\Url::base(true) . '/img/empleados-fotos/default.jpg';
                // Creamos la URL hacia la vista del empleado
                $urlView = \yii\helpers\Url::to(['empleado/view', 'id' => $a['idempleado']]);
                //$html .= '<img src="' . $src . '" title="' . $a['nombre'] . '" style="width:28px; height:28px; border-radius:50%; object-fit:cover; border:2px solid #ddd;">';
                //$html .= '<img src="' . $src . '" title="' . $a['nombre'] . '" class="imagen-avatar-grilla" style="width:20px; height:20px; border-radius:50%; object-fit:cover; ">';
                $html .= '<a href="' . $urlView . '" role="modal-remote" title="' . $a['nombre'] . '">';
                $html .= '<img src="' . $src . '" class="imagen-avatar-grilla" style="width:22px; height:22px; border-radius:50%; object-fit:cover; border: 1px solid #ccc; cursor:pointer;">';
                $html .= '</a>';
            }
            $html .= '</div>';
            return $html;
        },

    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna_7,
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{update} {view}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Confirmación',
            'data-confirm-message' => '¿Está seguro de eliminar este registro?',
        ],
    ],
];
