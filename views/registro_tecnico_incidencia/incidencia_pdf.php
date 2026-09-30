<?php

use app\models\ConstantesGlobales;
use app\models\Empleado;
use app\models\Inventario;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use yii\helpers\Html;

/**
 * @var app\models\RegistroTecnicoIncidencia $model
 */

// Carga de datos
$ingresante = Empleado::get_empleado($model->idingresante);
$dispositivo = $model->iddispositivo ? OrganismoDispositivo::get_dispositivo($model->iddispositivo) : null;
$retira = $model->idretira ? Empleado::get_empleado($model->idretira) : null;

$movimientos = RegistroTecnicoIncidenciaMovimiento::find()
    ->where(['idincidencia' => $model->idincidencia])
    ->orderBy(['fecha' => SORT_ASC, 'hora' => SORT_ASC])
    ->all();

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

$ultimoMovimiento = end($movimientos);
$estadoActualId = $ultimoMovimiento ? $ultimoMovimiento->idmovimiento_nombre : 1;
$infoEstado = ConstantesGlobales::ESTADOS_INCIDENCIAS[$estadoActualId] ?? [
    'movimiento' => 'Sin Registrar',
    'color' => '#eee',
    'color_texto' => '#333'
];

?>



<!-- LÍNEA 1: CABECERA Y SECTOR -->
<table class="table-layout">
    <tr>
        <td class="card-box" style="width: 30%;">
            <div class="card-head t-blue">Incidencia #<?= str_pad($model->idincidencia, 5, '0', STR_PAD_LEFT) ?></div>
            <div class="card-body">
                <strong>Fecha:</strong> <?= $movimientos ? date('d/m/Y H:i', strtotime($movimientos[0]->fecha . ' ' . $movimientos[0]->hora)) : 'Sin fecha' ?><br><br>
                <span class="badge-estado" style="background-color: <?= $infoEstado['color'] ?>; color: <?= $infoEstado['color_texto'] ?>;">
                    <?= Html::encode($infoEstado['movimiento']) ?>
                </span>
            </div>
        </td>
        <td class="card-box" style="width: 70%;">
            <div class="card-head t-amber">Sector / Ubicación</div>
            <div class="card-body">
                <strong><?= $dispositivo ? Html::encode($dispositivo->descripcion) : 'No especificado' ?></strong>
            </div>
        </td>
    </tr>
</table>

<!-- LÍNEA 2: INGRESADO POR Y RETIRADO POR -->
<table class="table-layout">
    <tr>
        <td class="card-box" style="width: 50%;">
            <div class="card-head t-blue">Ingresado Por / Solicitante</div>
            <div class="card-body">
                <?php if ($ingresante): ?>
                    <strong><?= Html::encode($ingresante->descripcion) ?></strong><br>
                    <span style="color: #666;">Legajo: <?= $model->idingresante ?></span>
                <?php else: ?>
                    <span style="color: #888; font-style: italic;">No especificado</span>
                <?php endif; ?>
            </div>
        </td>
        <td class="card-box" style="width: 50%;">
            <div class="card-head <?= $retira ? 't-green' : 't-gray' ?>">Retirado Por / Entrega</div>
            <div class="card-body">
                <?php if ($retira): ?>
                    <strong><?= Html::encode($retira->descripcion) ?></strong><br>
                    <span style="color: #666;">Legajo: <?= $model->idretira ?></span>
                <?php else: ?>
                    <span style="color: #666; font-style: italic;">Pendiente de retiro / Equipo en taller</span>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>

<!-- LÍNEA 3: DETALLE DEL EQUIPO -->
<table class="table-layout">
    <tr>
        <td class="card-box" style="width: 100%;">
            <div class="card-head t-teal">Equipo / Detalle Inicial</div>
            <div class="card-body">
                <?= !empty($detalleEquipo) ? Html::encode($detalleEquipo) : '<span style="color: #888; font-style: italic;">Sin detalle registrado</span>' ?>
            </div>
        </td>
    </tr>
</table>

<!-- LÍNEA 4: HISTORIAL DE MOVIMIENTOS -->
<table class="table-layout">
    <tr>
        <td class="card-box" style="width: 100%;">
            <div class="card-head t-purple">Historial de Movimientos e Intervenciones</div>
            <div style="padding: 5px;">
                <?php if (empty($movimientos)): ?>
                    <p style="color: #888; font-style: italic; padding: 5px;">Sin movimientos registrados.</p>
                <?php else: ?>
                    <table class="table-mov">
                        <thead>
                            <tr>
                                <th style="width: 15%; text-align: center;">Fecha / Hora</th>
                                <th style="width: 20%; text-align: center;">Tipo</th>
                                <th style="text-align: left;">Descripción / Detalle</th>
                                <th style="width: 25%; text-align: left;">Técnicos Intervinientes</th>
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

                                $asistentesSql = "SELECT CONCAT(p.apellido, ' ', p.nombre) as nombre
                                                  FROM registro_tecnico_incidencia_movimiento_asistencia a
                                                  JOIN empleado e ON a.idtecnico = e.idempleado
                                                  JOIN personas p ON p.idpersona = e.idpersona
                                                  WHERE a.idmovimiento = :idmov";
                                $asistentesMov = Yii::$app->db->createCommand($asistentesSql, [':idmov' => $mov->idmovimiento])->queryAll();
                                ?>
                                <tr>
                                    <td style="text-align: center;">
                                        <strong><?= date('d/m/Y', strtotime($mov->fecha)) ?></strong><br>
                                        <span style="color: #666;"><?= substr($mov->hora, 0, 5) ?></span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge-estado" style="background-color: <?= $tipoMov['color'] ?>; color: <?= $tipoMov['color_texto'] ?>;">
                                            <?= Html::encode($tipoMov['movimiento']) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: left;">
                                        <?= nl2br(Html::encode($mov->descripcion)) ?>
                                    </td>
                                    <td style="text-align: left;">
                                        <?php if (empty($asistentesMov)): ?>
                                            <span style="color: #888; font-style: italic;">Sin datos</span>
                                        <?php else: ?>
                                            <?php 
                                                $nombres = array_map(function($tec) {
                                                    return Html::encode($tec['nombre']);
                                                }, $asistentesMov);
                                                echo implode(', ', $nombres);
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>