<?php

use app\models\ConstantesGlobales;
use app\models\Empleado;
use app\models\Inventario;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\RegistroTecnicoIncidencia $model
 */

// Carga de datos principales de cabecera
$ingresante = Empleado::get_empleado($model->idingresante);
$dispositivo = $model->iddispositivo ? OrganismoDispositivo::get_dispositivo($model->iddispositivo) : null;
$retira = $model->idretira ? Empleado::get_empleado($model->idretira) : null;

// Obtención de movimientos ordenados cronológicamente
$movimientos = RegistroTecnicoIncidenciaMovimiento::find()
    ->where(['idincidencia' => $model->idincidencia])
    ->orderBy(['fecha' => SORT_ASC, 'hora' => SORT_ASC])
    ->all();

// Primer movimiento para el detalle del equipo (según lógica _columns.php)
$primerMovimiento = RegistroTecnicoIncidenciaMovimiento::find()
    ->where(['idincidencia' => $model->idincidencia])
    ->orderBy(['idmovimiento' => SORT_ASC])
    ->one();

$detalleEquipo = "";
if ($primerMovimiento) {
    $detalleEquipo = $primerMovimiento->descripcion;
    if (!empty($model->idinventario)) {
        $equipoObj = Inventario::get_item_inventario($model->idinventario);
        if ($equipoObj) {
            $detalleEquipo = "{$equipoObj->descripcion} {$primerMovimiento->descripcion}";
        }
    }
}

// Determinar estado actual basado en el último movimiento cargado
$ultimoMovimiento = end($movimientos);
$estadoActualId = $ultimoMovimiento ? $ultimoMovimiento->idmovimiento_nombre : 1;
$infoEstado = ConstantesGlobales::ESTADOS_INCIDENCIAS[$estadoActualId] ?? [
    'movimiento' => 'Sin Registrar',
    'color' => '#eee',
    'color_texto' => '#333'
];

$base = Yii::$app->request->baseUrl;
$this->title = 'Incidencia #' . str_pad($model->idincidencia, 5, '0', STR_PAD_LEFT);

?>

<style>
    .inc-wrap {
        padding: 8px 0 16px;
    }

    /* Grilla modular flexible */
    .inc-row-head {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: stretch;
    }

    .inc-head-box {
        flex: 0 0 25%;
        max-width: 25%;
    }

    .inc-sector-box {
        flex: 1;
    }

    .inc-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 10px;
    }

    .inc-full {
        grid-column: 1 / -1;
        margin-bottom: 10px;
    }

    /* Estilo unificado de cards */
    .inc-header-card {
        background: #fff;
        border: 0.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 16px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }

    .card {
        background: #fff;
        border: 0.5px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }

    .card-title {
        font-size: 12px;
        font-weight: 500;
        padding: 7px 14px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .card-body {
        padding: 10px 14px;
    }

    /* Temas de títulos */
    .t-blue {
        background: #B5D4F4;
        color: #0C447C;
    }

    .t-teal {
        background: #9FE1CB;
        color: #085041;
    }

    .t-amber {
        background: #FAC775;
        color: #633806;
    }

    .t-green {
        background: #C0DD97;
        color: #27500A;
    }

    .t-purple {
        background: #CECBF6;
        color: #26215C;
    }

    .t-gray {
        background: #E0E0E0;
        color: #424242;
    }

    .badge-estado {
        font-size: 11px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }

    .avatar-persona {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        color: #1e40af;
        flex-shrink: 0;
    }

    .persona-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Tabla de movimientos */
    .table-informe-mov {
        font-size: 12px;
        margin: 0;
    }

    .table-informe-mov th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        padding: 8px !important;
    }

    .table-informe-mov td {
        vertical-align: middle !important;
        padding: 8px !important;
    }

    .asistente-foto-sm {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #ccc;
    }

    /* Estilos específicos para la impresión / guardado en PDF */
    @media print {

        /* 1. Ocultar todo el contenido del sitio de fondo y elementos UI */
        body * {
            visibility: hidden;
        }

        /* 2. Mostrar únicamente el contenedor de la incidencia y sus elementos */
        .inc-wrap,
        .inc-wrap * {
            visibility: visible;
        }

        /* 3. Posicionar la vista al principio de la página impresa */
        .inc-wrap {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* 4. Ocultar botones de acción específicos e interfaces sobrantes */
        .no-print,
        .modal-header,
        .modal-footer,
        .breadcrumb,
        .navbar {
            display: none !important;
        }

        /* 5. Estilo de bordes para la impresión */
        .card,
        .inc-header-card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }

        /* 6. Limpiar márgenes de página */
        @page {
            margin: 1cm;
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="inc-wrap">

    <!-- LÍNEA 1: CABECERA (25%) + SECTOR (resto) -->
    <div class="inc-row-head">
        <div class="inc-head-box">
            <div class="inc-header-card">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #E6F1FB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa fa-desktop" style="font-size: 16px; color: #0C447C;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 14px; font-weight: 600; color: #222; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= Html::encode($this->title) ?></p>
                        <p style="font-size: 11px; color: #888; margin: 0;">
                            <?= $movimientos ? date('d/m/Y', strtotime($movimientos[0]->fecha)) . ' ' . substr($movimientos[0]->hora, 0, 5) : 'Sin fecha' ?>
                        </p>
                    </div>
                </div>
                <div style="margin-top: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span class="badge-estado" style="background: <?= $infoEstado['color'] ?? '#eee' ?>; color: <?= $infoEstado['color_texto'] ?? '#333' ?>;">
                        <?= Html::encode($infoEstado['movimiento'] ?? 'Sin Estado') ?>
                    </span>
                    <!-- BOTÓN GENERAR PDF -->
                    <a href="<?= Url::to(['pdf', 'id' => $model->idincidencia]) ?>" target="_blank" class="btn btn-danger btn-xs no-print" title="Generar PDF">
                        <i class="fa fa-file-pdf-o"></i> Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="inc-sector-box">
            <div class="card">
                <div class="card-title t-amber">
                    <i class="fa fa-building" style="font-size: 12px;"></i> Sector / Ubicación
                </div>
                <div class="card-body">
                    <?php if ($dispositivo): ?>
                        <div class="persona-wrap">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: #FAEEDA; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fa fa-sitemap" style="font-size: 15px; color: #633806;"></i>
                            </div>
                            <div>
                                <p style="font-size: 13px; font-weight: 500; color: #333; margin: 0;"><?= Html::encode($dispositivo->descripcion) ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <span style="color: #aaa; font-style: italic; font-size: 12px;">No especificado</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- LÍNEA 2: INGRESÓ Y RETIRÓ -->
    <div class="inc-grid-2col">
        <!-- QUIEN TRAJO EL EQUIPO -->
        <div class="card">
            <div class="card-title t-blue">
                <i class="fa fa-user" style="font-size: 12px;"></i> Ingresado Por / Solicitante
            </div>
            <div class="card-body">
                <?php if ($ingresante): ?>
                    <div class="persona-wrap">
                        <?php
                        $partes = explode(' ', $ingresante->descripcion);
                        $iniciales = strtoupper(substr($partes[0] ?? '', 0, 1) . substr($partes[1] ?? '', 0, 1));
                        ?>
                        <div class="avatar-persona"><?= $iniciales ?></div>
                        <div>
                            <p style="font-size: 13px; font-weight: 500; color: #222; margin: 0;"><?= Html::encode($ingresante->descripcion) ?></p>
                            <p style="font-size: 11px; color: #888; margin: 0;">Legajo <?= $model->idingresante ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <span style="color: #aaa; font-style: italic; font-size: 12px;">No especificado</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- QUIEN RETIRO EL EQUIPO -->
        <div class="card">
            <div class="card-title <?= $retira ? 't-green' : 't-gray' ?>">
                <i class="fa fa-check-circle" style="font-size: 12px;"></i> Retirado Por / Entrega
            </div>
            <div class="card-body">
                <?php if ($retira): ?>
                    <div class="persona-wrap">
                        <?php
                        $partesRet = explode(' ', $retira->descripcion);
                        $inicialesRet = strtoupper(substr($partesRet[0] ?? '', 0, 1) . substr($partesRet[1] ?? '', 0, 1));
                        ?>
                        <div class="avatar-persona" style="background: #EAF3DE; color: #27500A;"><?= $inicialesRet ?></div>
                        <div>
                            <p style="font-size: 13px; font-weight: 600; color: #222; margin: 0;"><?= Html::encode($retira->descripcion) ?></p>
                            <p style="font-size: 11px; color: #666; margin: 0;">Legajo <?= $model->idretira ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <span style="color: #888; font-style: italic; font-size: 12px;">
                        <i class="fa fa-clock-o" style="margin-right: 4px;"></i> Pendiente de retiro / Equipo en taller
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- LÍNEA 3: CARD DEL EQUIPO EN CUESTIÓN -->
    <div class="inc-full">
        <div class="card">
            <div class="card-title t-teal">
                <i class="fa fa-microchip" style="font-size: 12px;"></i> Equipo / Detalle Inicial
            </div>
            <div class="card-body">
                <p style="font-size: 13px; font-weight: 500; color: #222; margin: 0;">
                    <?= !empty($detalleEquipo) ? Html::encode($detalleEquipo) : '<span style="color: #aaa; font-style: italic;">Sin detalle registrado</span>' ?>
                </p>
            </div>
        </div>
    </div>

    <!-- LÍNEA 4: GRILLA HISTÓRICA DE MOVIMIENTOS -->
    <div class="inc-full">
        <div class="card">
            <div class="card-title t-purple">
                <i class="fa fa-list-alt" style="font-size: 12px;"></i> Historial de Movimientos e Intervenciones
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if (empty($movimientos)): ?>
                    <p style="padding: 15px; color: #aaa; font-style: italic; margin: 0; font-size: 12px;">Sin movimientos registrados.</p>
                <?php else: ?>
                    <table class="table table-striped table-bordered table-informe-mov text-center">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 15%;">Fecha / Hora</th>
                                <th class="text-center" style="width: 20%;">Tipo</th>
                                <th class="text-left">Descripción / Detalle</th>
                                <th class="text-center" style="width: 18%;">Técnicos Intervinientes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($movimientos as $mov): ?>
                                <?php
                                $idTipo = $mov->idmovimiento_nombre;
                                $tipoMov = ConstantesGlobales::ESTADOS_INCIDENCIAS[$idTipo] ?? [
                                    'movimiento' => 'Sin Especificar',
                                    'color' => '#eee',
                                    'color_texto' => '#333'
                                ];

                                // Consulta de asistentes para cada movimiento
                                $asistentesSql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                                                  FROM registro_tecnico_incidencia_movimiento_asistencia a
                                                  JOIN empleado e ON a.idtecnico = e.idempleado
                                                  JOIN personas p ON p.idpersona = e.idpersona
                                                  WHERE a.idmovimiento = :idmov";
                                $asistentesMov = Yii::$app->db->createCommand($asistentesSql, [':idmov' => $mov->idmovimiento])->queryAll();
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= date('d/m/Y', strtotime($mov->fecha)) ?></strong><br>
                                        <small class="text-muted"><?= substr($mov->hora, 0, 5) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge-estado" style="background: <?= $tipoMov['color'] ?>; color: <?= $tipoMov['color_texto'] ?>;">
                                            <?= Html::encode($tipoMov['movimiento']) ?>
                                        </span>
                                    </td>
                                    <td class="text-left">
                                        <?= nl2br(Html::encode($mov->descripcion)) ?>
                                    </td>
                                    <td>
                                        <?php if (empty($asistentesMov)): ?>
                                            <span class="text-muted" style="font-style: italic; font-size: 11px;">Sin datos</span>
                                        <?php else: ?>
                                            <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: center; align-items: center;">
                                                <?php foreach ($asistentesMov as $tec): ?>
                                                    <?php
                                                    $fotoNombre = !empty($tec['foto']) ? $tec['foto'] : 'default.jpg';
                                                    $srcFoto = $base . '/img/empleados-fotos/' . $fotoNombre;
                                                    $urlView = Url::to(['empleado/view', 'id' => $tec['idempleado']]);
                                                    ?>
                                                    <a href="<?= $urlView ?>" role="modal-remote" title="<?= Html::encode($tec['nombre']) ?>">
                                                        <img src="<?= $srcFoto ?>" class="asistente-foto-sm">
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>