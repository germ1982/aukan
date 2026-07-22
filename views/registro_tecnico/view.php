<?php

use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnico;
use app\models\RegistroTecnicoAsistencia;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroTecnico */

$this->title = 'Registro numero ' . str_pad($model->idregistro, 5, '0', STR_PAD_LEFT);

$solicitante = Empleado::get_empleado($model->idsolicitante);
$dispositivo = $model->iddispositivo ? OrganismoDispositivo::get_dispositivo($model->iddispositivo) : null;
$tipo = $model->idtipo_registro ? Configuracion::findOne($model->idtipo_registro) : null;

$asistentes_sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                   FROM registro_tecnico_asistencia a
                   JOIN empleado e ON a.idtecnico = e.idempleado
                   JOIN personas p ON p.idpersona = e.idpersona
                   WHERE a.idregistro = {$model->idregistro}";
$asistentes = Yii::$app->db->createCommand($asistentes_sql)->queryAll();

$estados = [
    RegistroTecnico::ESTADO_PENDIENTE   => ['label' => 'Pendiente',      'bg' => '#FAEEDA', 'color' => '#633806'],
    RegistroTecnico::ESTADO_ASISTENCIA  => ['label' => 'En Asistencia',  'bg' => '#E6F1FB', 'color' => '#0C447C'],
    RegistroTecnico::ESTADO_FINALIZADO  => ['label' => 'Finalizado',     'bg' => '#EAF3DE', 'color' => '#27500A'],
];
$estado = $estados[$model->estado] ?? ['label' => 'Desconocido', 'bg' => '#eee', 'color' => '#333'];

$base = Yii::$app->request->baseUrl;

$qrData = implode(' | ', array_filter([
    'Registro #' . $model->idregistro,
    $solicitante ? 'Solicitante: ' . $solicitante->descripcion : null,
    $dispositivo ? 'Sector: ' . $dispositivo->descripcion : null,
    $tipo ? 'Tipo: ' . $tipo->descripcion : null,
    $model->problema ? 'Problema: ' . $model->problema : null,
    $model->solucion ? 'Solución: ' . $model->solucion : null,
    'Fecha: ' . date('d/m/Y', strtotime($model->fecha_solicitud)),
]));
?>

<style>
.rt-wrap { padding: 8px 0 16px; }
.rt-header { display:flex; align-items:center; gap:12px; padding:12px 16px; background:#fff; border:0.5px solid #e0e0e0; border-radius:10px; margin-bottom:10px; }
.rt-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.rt-full { grid-column:1/-1; }
.card { background:#fff; border:0.5px solid #e0e0e0; border-radius:10px; overflow:hidden; }
.card-title { font-size:12px; font-weight:500; padding:7px 14px; display:flex; align-items:center; gap:7px; }
.card-body { padding:10px 14px; }
.t-blue   { background:#B5D4F4; color:#0C447C; }
.t-teal   { background:#9FE1CB; color:#085041; }
.t-amber  { background:#FAC775; color:#633806; }
.t-green  { background:#C0DD97; color:#27500A; }
.t-purple { background:#CECBF6; color:#26215C; }
.t-coral  { background:#F0997B; color:#4A1B0C; }
.pv-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:0.5px solid #f0f0f0; gap:12px; font-size:12px; }
.pv-row:last-child { border-bottom:none; }
.pv-label { color:#777; white-space:nowrap; }
.pv-value { color:#333; text-align:right; }
.pv-value.muted { color:#aaa; font-style:italic; }
.badge { font-size:11px; padding:3px 10px; border-radius:20px; font-weight:500; }
.solicitante-wrap { display:flex; align-items:center; gap:10px; padding:6px 0; }
.avatar { width:38px; height:38px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:500; color:#1e40af; flex-shrink:0; }
.asistentes-wrap { display:flex; flex-wrap:wrap; gap:10px; padding:6px 0; }
.asistente-item { display:flex; flex-direction:column; align-items:center; gap:4px; }
.asistente-foto { width:38px; height:38px; border-radius:50%; object-fit:cover; border:1.5px solid #e0e0e0; }
.asistente-nombre { font-size:10px; color:#777; text-align:center; max-width:60px; line-height:1.3; }
.problema-box { font-size:12px; color:#333; line-height:1.7; padding:4px 0; }
.qr-wrap { display:flex; flex-direction:column; align-items:center; gap:8px; padding:4px 0; }
.btn-pdf { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#fff; border:0.5px solid #ccc; border-radius:8px; font-size:12px; font-weight:500; color:#333; cursor:pointer; text-decoration:none; }
.btn-pdf:hover { background:#f5f5f5; }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="rt-wrap">

    <div class="rt-header">
        <div style="width:42px;height:42px;border-radius:8px;background:#E6F1FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa fa-wrench" style="font-size:18px;color:#0C447C;"></i>
        </div>
        <div style="flex:1;">
            <p style="font-size:15px;font-weight:500;color:#222;margin:0;"><?= $this->title ?></p>
            <p style="font-size:12px;color:#888;margin:0;">
                Solicitud: <?= date('d/m/Y', strtotime($model->fecha_solicitud)) ?>
                <?= $model->hora_solicitud ? ' ' . substr($model->hora_solicitud, 0, 5) : '' ?>
                <?php if ($model->fecha_solucion): ?>
                    &nbsp;·&nbsp; Solución: <?= date('d/m/Y', strtotime($model->fecha_solucion)) ?>
                    <?= $model->hora_solucion ? ' ' . substr($model->hora_solucion, 0, 5) : '' ?>
                <?php endif; ?>
            </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <?php if ($tipo): ?>
                <span class="badge" style="background:#E6F1FB;color:#0C447C;"><?= Html::encode($tipo->descripcion) ?></span>
            <?php endif; ?>
            <span class="badge" style="background:<?= $estado['bg'] ?>;color:<?= $estado['color'] ?>;"><?= $estado['label'] ?></span>
            <a class="btn-pdf" href="<?= Url::to(['registro-tecnico/pdf', 'id' => $model->idregistro]) ?>">
                <i class="fa fa-file-pdf-o"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="rt-grid">

        <div class="card">
            <div class="card-title t-blue"><i class="fa fa-user" style="font-size:12px;"></i> Solicitante</div>
            <div class="card-body">
                <?php if ($solicitante): ?>
                    <div class="solicitante-wrap">
                        <?php
                        $partes = explode(' ', $solicitante->descripcion);
                        $iniciales = strtoupper(substr($partes[0] ?? '', 0, 1) . substr($partes[1] ?? '', 0, 1));
                        ?>
                        <div class="avatar"><?= $iniciales ?></div>
                        <div>
                            <p style="font-size:13px;font-weight:500;color:#222;margin:0;"><?= Html::encode($solicitante->descripcion) ?></p>
                            <p style="font-size:11px;color:#888;margin:0;">Legajo <?= $model->idsolicitante ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="pv-value muted">No especificado</span>
                <?php endif; ?>
            </div>

                        <div class="card-body">
                <?php if ($dispositivo): ?>
                    <div style="display:flex;align-items:center;gap:10px;padding:6px 0;">
                        <div style="width:36px;height:36px;border-radius:8px;background:#E6F1FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa fa-building" style="font-size:15px;color:#185FA5;"></i>
                        </div>
                        <p style="font-size:12px;color:#333;margin:0;"><?= Html::encode($dispositivo->descripcion) ?></p>
                    </div>
                <?php else: ?>
                    <span class="pv-value muted">No especificado</span>
                <?php endif; ?>
            </div>
        </div>



        <div class="card">
            <div class="card-title t-amber"><i class="fa fa-exclamation-circle" style="font-size:12px;"></i> Problema</div>
            <div class="card-body">
                <p class="problema-box"><?= $model->problema ? Html::encode($model->problema) : '<span class="muted" style="color:#aaa;font-style:italic;">Sin descripción</span>' ?></p>
            </div>
        </div>

        

        <div class="card">
            <div class="card-title t-purple"><i class="fa fa-users" style="font-size:12px;"></i> Asistentes</div>
            <div class="card-body">
                <?php if (empty($asistentes)): ?>
                    <span style="color:#aaa;font-style:italic;font-size:12px;">Sin asistentes asignados</span>
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

        <div class="card">
            <div class="card-title t-green"><i class="fa fa-check-circle" style="font-size:12px;"></i> Solución</div>
            <div class="card-body">
                <p class="problema-box"><?= $model->solucion ? Html::encode($model->solucion) : '<span style="color:#aaa;font-style:italic;">Sin solución registrada</span>' ?></p>
            </div>
        </div>



    </div>
</div>

<script>
new QRCode(document.getElementById("qrcode"), {
    text: <?= json_encode($qrData) ?>,
    width: 100,
    height: 100,
    colorDark: "#333333",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.M
});
</script>