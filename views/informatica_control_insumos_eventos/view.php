<?php

use app\models\Empleado;
use app\models\InformaticaControlInsumosEventos;
use app\models\OrganismoDispositivo;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaControlInsumosEventos */

$this->title = 'Entrega N° ' . str_pad($model->identrega, 5, '0', STR_PAD_LEFT);

// Solicitante, Responsable y Sector
$solicitante = $model->idsolicitante ? Empleado::get_empleado($model->idsolicitante) : null;
$responsable = $model->idresponsable ? Empleado::get_empleado($model->idresponsable) : null;
$sector = $model->idsector_solicitante ? OrganismoDispositivo::get_dispositivo_pro($model->idsector_solicitante) : null;

// Consulta de Asistentes
$asistentes_sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                   FROM informatica_control_insumos_eventos_asistente a
                   JOIN empleado e ON a.idtecnico = e.idempleado
                   JOIN personas p ON p.idpersona = e.idpersona
                   WHERE a.identrega = {$model->identrega}";
$asistentes = Yii::$app->db->createCommand($asistentes_sql)->queryAll();

// Estado con método del modelo
$estadoTexto = InformaticaControlInsumosEventos::getEstadoTexto($model->estado);

// Mapeo de estilos según la constante del modelo
switch ((int)$model->estado) {
    case InformaticaControlInsumosEventos::ESTADO_DEVUELTO:
        $estadoBg = '#EAF3DE';
        $estadoColor = '#27500A';
        break;
    case InformaticaControlInsumosEventos::ESTADO_DEVUELTO_OBSERVACIONES:
        $estadoBg = '#FEF3D6';
        $estadoColor = '#8F4B00';
        break;
    case InformaticaControlInsumosEventos::ESTADO_EN_PRESTAMO:
        $estadoBg = '#E6F1FB';
        $estadoColor = '#0C447C';
        break;
    case InformaticaControlInsumosEventos::ESTADO_CANCELADO:
        $estadoBg = '#FCE8E6';
        $estadoColor = '#A8071A';
        break;
    case InformaticaControlInsumosEventos::ESTADO_SOLICITADO:
    default:
        $estadoBg = '#F3F4F6';
        $estadoColor = '#374151';
        break;
}

$base = Yii::$app->request->baseUrl;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.ice-wrap { padding: 8px 0 16px; }
.ice-header { display:flex; align-items:center; gap:12px; padding:12px 16px; background:#fff; border:0.5px solid #e0e0e0; border-radius:10px; margin-bottom:10px; }
.ice-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.card { background:#fff; border:0.5px solid #e0e0e0; border-radius:10px; overflow:hidden; }
.card-title { font-size:12px; font-weight:600; padding:7px 14px; display:flex; align-items:center; gap:7px; text-transform: uppercase; letter-spacing:0.3px; }
.card-body { padding:10px 14px; }

/* Paleta pastel */
.t-blue   { background:#B5D4F4; color:#0C447C; }
.t-teal   { background:#9FE1CB; color:#085041; }
.t-amber  { background:#FAC775; color:#633806; }
.t-purple { background:#CECBF6; color:#26215C; }

.pv-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:0.5px solid #f0f0f0; gap:12px; font-size:12px; }
.pv-row:last-child { border-bottom:none; }
.pv-label { color:#777; white-space:nowrap; }
.pv-value { color:#333; text-align:right; font-weight:500; }
.pv-value.muted { color:#aaa; font-style:italic; font-weight:normal; }

.badge-custom { font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
.person-wrap { display:flex; align-items:center; gap:10px; padding:4px 0; }
.avatar { width:38px; height:38px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; color:#1e40af; flex-shrink:0; }

.asistentes-wrap { display:flex; flex-wrap:wrap; gap:10px; padding:4px 0; }
.asistente-item { display:flex; flex-direction:column; align-items:center; gap:4px; }
.asistente-foto { width:38px; height:38px; border-radius:50%; object-fit:cover; border:1.5px solid #e0e0e0; }
.asistente-nombre { font-size:10px; color:#666; text-align:center; max-width:65px; line-height:1.2; }

.text-box { font-size:12px; color:#333; line-height:1.6; margin:0; }
</style>

<div class="ice-wrap">

    <!-- HEADER SUPERIOR -->
    <div class="ice-header">
        <div style="width:42px;height:42px;border-radius:8px;background:#E6F1FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa fa-boxes-packing" style="font-size:18px;color:#0C447C;"></i>
        </div>
        <div style="flex:1;">
            <p style="font-size:15px;font-weight:600;color:#222;margin:0;"><?= Html::encode($this->title) ?></p>
            <p style="font-size:12px;color:#888;margin:2px 0 0 0;">
                <i class="fa fa-calendar-day" style="font-size:11px;margin-right:3px;"></i>
                Fecha: <?= $model->fecha ? date('d/m/Y', strtotime($model->fecha)) : 'No registrada' ?>
                <?= $model->hora ? ' - ' . substr($model->hora, 0, 5) . ' hs' : '' ?>
            </p>
        </div>
        <div>
            <span class="badge-custom" style="background:<?= $estadoBg ?>;color:<?= $estadoColor ?>;">
                <?= Html::encode($estadoTexto) ?>
            </span>
        </div>
    </div>

    <!-- GRID PRINCIPAL -->
    <div class="ice-grid">

        <!-- 1. SOLICITANTE Y SECTOR -->
        <div class="card">
            <div class="card-title t-blue"><i class="fa fa-user" style="font-size:12px;"></i> Solicitante y Sector</div>
            <div class="card-body">
                <?php if ($solicitante): ?>
                    <div class="person-wrap">
                        <?php
                        $partes = explode(' ', $solicitante->descripcion);
                        $iniciales = strtoupper(substr($partes[0] ?? '', 0, 1) . substr($partes[1] ?? '', 0, 1));
                        ?>
                        <div class="avatar"><?= $iniciales ?></div>
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#222;margin:0;"><?= Html::encode($solicitante->descripcion) ?></p>
                            <p style="font-size:11px;color:#888;margin:0;">Legajo/ID: <?= $model->idsolicitante ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="pv-value muted">Solicitante no especificado</span>
                <?php endif; ?>

                <div class="pv-row" style="margin-top:8px; padding-top:8px; border-top:0.5px solid #f0f0f0;">
                    <span class="pv-label"><i class="fa fa-building" style="font-size:10px;margin-right:4px;"></i> Sector:</span>
                    <span class="pv-value">
                        <?= $sector ? Html::encode($sector->descripcion) : '<span class="pv-value muted">No definido</span>' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. RESPONSABLE Y DESTINO -->
        <div class="card">
            <div class="card-title t-purple"><i class="fa fa-user-shield" style="font-size:12px;"></i> Responsable y Destino</div>
            <div class="card-body">
                <?php if ($responsable): ?>
                    <div class="person-wrap">
                        <?php
                        $partesR = explode(' ', $responsable->descripcion);
                        $inicialesR = strtoupper(substr($partesR[0] ?? '', 0, 1) . substr($partesR[1] ?? '', 0, 1));
                        ?>
                        <div class="avatar" style="background:#f3e8ff; color:#6b21a8;"><?= $inicialesR ?></div>
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#222;margin:0;"><?= Html::encode($responsable->descripcion) ?></p>
                            <p style="font-size:11px;color:#888;margin:0;">Responsable a cargo</p>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="pv-value muted">Sin responsable asignado</span>
                <?php endif; ?>

                <div class="pv-row" style="margin-top:8px; padding-top:8px; border-top:0.5px solid #f0f0f0;">
                    <span class="pv-label"><i class="fa fa-location-dot" style="font-size:10px;margin-right:4px;"></i> Destino Préstamo:</span>
                    <span class="pv-value">
                        <?= $model->destino_prestamo ? Html::encode($model->destino_prestamo) : '<span class="pv-value muted">Sin destino</span>' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. ASISTENTES (AVATARES Y NOMBRES) -->
        <div class="card">
            <div class="card-title t-blue"><i class="fa fa-users" style="font-size:12px;"></i> Asistencia en Destino</div>
            <div class="card-body">
                <?php if (empty($asistentes)): ?>
                    <span class="pv-value muted">Sin asistentes asignados</span>
                <?php else: ?>
                    <div class="asistentes-wrap">
                        <?php foreach ($asistentes as $a): ?>
                            <?php
                            $src = $a['foto']
                                ? $base . '/img/empleados-fotos/' . $a['foto']
                                : $base . '/img/empleados-fotos/default.jpg';
                            $urlEmpleado = Url::to(['empleado/view', 'id' => $a['idempleado']]);
                            ?>
                            <div class="asistente-item">
                                <a href="<?= $urlEmpleado ?>" role="modal-remote" title="<?= Html::encode($a['nombre']) ?>">
                                    <img src="<?= $src ?>" class="asistente-foto">
                                </a>
                                <span class="asistente-nombre"><?= Html::encode($a['nombre']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. DESCRIPCIÓN DE INSUMOS -->
        <div class="card">
            <div class="card-title t-amber"><i class="fa fa-list-check" style="font-size:12px;"></i> Descripción de Insumos</div>
            <div class="card-body">
                <p class="text-box">
                    <?= $model->descripcion ? nl2br(Html::encode($model->descripcion)) : '<span class="pv-value muted">Sin detalle de insumos</span>' ?>
                </p>
            </div>
        </div>

        <!-- 5. OBSERVACIONES -->
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-title t-teal"><i class="fa fa-comment-dots" style="font-size:12px;"></i> Observaciones</div>
            <div class="card-body">
                <p class="text-box">
                    <?= $model->observacion ? nl2br(Html::encode($model->observacion)) : '<span class="pv-value muted">Sin observaciones</span>' ?>
                </p>
            </div>
        </div>

    </div>
</div>