<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnico;
use app\models\RegistroTecnicoAsistencia;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

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
                    where e.idempleado in (SELECT idsolicitante from registro_tecnico)
                    order by p.apellido, p.nombre";
$empleados = Empleado::findBySql($empleados_sql)->all();

$sectores_sql = "SELECT  d.iddispositivo,  CONCAT(e.descripcion_fija,' - ', eo.descripcion, ' - ', o.abreviatura, ' - ', d.descripcion) as descripcion
                from organismo_dispositivo d
                join organismo o on o.idorganismo = d.idorganismo
                join edificio_oficina eo on eo.idoficina = d.idoficina
                join edificio e on e.idedificio = eo.idedificio
                where d.iddispositivo in (SELECT iddispositivo from registro_tecnico)
                order by e.descripcion_fija, eo.descripcion, o.abreviatura, d.descripcion";
$sectores = OrganismoDispositivo::findBySql($sectores_sql)->all();


$columna_1 = '10%';
$columna_2 = '30%';
$columna_3 = '15%';
$columna_4 = '3%';
$columna_5 = '12%';
$columna_6 = '13%';
$columna_7 = '8%';
$columna_8 = '5%';
return [

    [
        'attribute' => 'fecha_solicitud',
        'width' => $columna_1,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_solicitud);
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => $columna_2,
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
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => $columna_2,
        'value' => function ($model) {
            if ($model->iddispositivo) {
                $dispositivo = OrganismoDispositivo::get_dispositivo($model->iddispositivo);
                return "$dispositivo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($sectores, 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Sector...'],
        'format' => 'raw',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idsolicitante',
        'width' => $columna_3,
        'value' => function ($model) {
            if ($model->idsolicitante) {
                $empleado = Empleado::get_empleado($model->idsolicitante);
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
        'attribute' => 'usuario_carga',
        'headerOptions' => [
            'style' => 'color: #87b867; ',
        ],
        'format' => 'raw',
        'value' => function ($model) {
            $modulo = ConstantesGlobales::REGISTRO_TECNICO_INFORMATICA;
            $accion = ConstantesGlobales::CREACION;
            $sql = "SELECT e.idempleado as idempleado, concat(p.apellido,' ',p.nombre) as nombre, e.foto as foto
                    FROM registro_tecnico r
                    join log_plataforma l on r.idregistro = l.idregistro
                    join usuarios u on l.idusuario = u.id
                    join empleado e on e.idpersona = u.idpersona
                    join personas p on p.idpersona = u.idpersona
                    where l.idmodulo = $modulo and r.idregistro = $model->idregistro and l.idaccion = $accion;";

            $iniciante = \Yii::$app->db->createCommand($sql)->queryOne();

            if (!$iniciante) return ''; // Corregido el empty para arrays planos

            $html = '<div style="display:flex; gap:4px; flex-wrap:wrap;">';

                $src = $iniciante['foto']
                    ? \yii\helpers\Url::base(true) . '/img/empleados-fotos/' . $iniciante['foto']
                    : \yii\helpers\Url::base(true) . '/img/empleados-fotos/default.jpg';
                // Creamos la URL hacia la vista del empleado
                $urlView = \yii\helpers\Url::to(['empleado/view', 'id' => $iniciante['idempleado']]);
                //$html .= '<img src="' . $src . '" title="' . $a['nombre'] . '" style="width:28px; height:28px; border-radius:50%; object-fit:cover; border:2px solid #ddd;">';
                //$html .= '<img src="' . $src . '" title="' . $a['nombre'] . '" class="imagen-avatar-grilla" style="width:20px; height:20px; border-radius:50%; object-fit:cover; ">';
                $html .= '<a href="' . $urlView . '" role="modal-remote" title="' . $iniciante['nombre'] . '">';
                $html .= '<img src="' . $src . '" class="imagen-avatar-grilla" style="width:22px; height:22px; border-radius:50%; object-fit:cover; border: 1px solid #ccc; cursor:pointer;">';
                $html .= '</a>';
          
            $html .= '</div>';
            return $html;
        },
        'filter' => false, // <-- ACÁ LE DECÍS QUE NO FILTRE
        'width' => $columna_4,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipo_registro',
        'width' => $columna_5,
        'value' => function ($model) {
            if ($model->idtipo_registro) {
                $tipo = Configuracion::findOne($model->idtipo_registro)->descripcion;
                return "$tipo";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_REGISTRO_TECNICO])->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Registro...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'asistentes_asignados',
        'headerOptions' => [
            'style' => 'color: #87b867; ',
        ],
        'format' => 'raw',
        'value' => function ($model) {
            $sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
            FROM registro_tecnico_asistencia a
            JOIN empleado e ON a.idtecnico = e.idempleado
            JOIN personas p ON p.idpersona = e.idpersona
            WHERE a.idregistro = {$model->idregistro}";

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
        'width' => $columna_6,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '150px',
        'format' => 'raw',
        'value' => function ($model) {
            $etiquetas = [
                RegistroTecnico::ESTADO_PENDIENTE   => '<span style="background:#FAEEDA;color:#633806;padding:2px 8px;border-radius:10px;font-size:11px;">Pendiente</span>',
                RegistroTecnico::ESTADO_ASISTENCIA  => '<span style="background:#E6F1FB;color:#0C447C;padding:2px 8px;border-radius:10px;font-size:11px;">En Asistencia</span>',
                RegistroTecnico::ESTADO_FINALIZADO  => '<span style="background:#EAF3DE;color:#27500A;padding:2px 8px;border-radius:10px;font-size:11px;">Finalizado</span>',
            ];
            return $etiquetas[$model->estado] ?? $model->estado;
        },

        // Pero la mejor opción manual es:
        'filter' => \yii\helpers\Html::activeCheckboxList($searchModel, 'estado', [
            RegistroTecnico::ESTADO_PENDIENTE   => 'Pendiente',
            RegistroTecnico::ESTADO_ASISTENCIA  => 'En Asistencia',
            RegistroTecnico::ESTADO_FINALIZADO  => 'Finalizado',
        ], ['style' => 'font-size:10px;']),
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => $columna_8,
        'template' => '{view} {update} ',
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
    ],

];
