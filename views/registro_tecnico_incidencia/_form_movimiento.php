<?php

use app\helpers\AppCheckboxListHelper;
use app\models\ConstantesGlobales;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var array $tecnicos_asistencia
 * @var bool $esPrimerMovimiento
 */

// Cargar tipos de movimientos desde ConstantesGlobales
$tipos_movimiento = ConstantesGlobales::ESTADOS_INCIDENCIAS;

?>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Fecha</label>
            <input type="text" id="mov_fecha" class="form-control" value="<?= date('d/m/Y') ?>" readonly>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Hora</label>
            <input type="text" id="mov_hora" class="form-control" value="<?= date('H:i') ?>" readonly>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Tipo de Movimiento</label>
            <select id="mov_idmovimiento_nombre" class="form-control">
                <?php foreach ($tipos_movimiento as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>">
                        <?= Html::encode($tipo['movimiento']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Descripción / Observación</label>
            <textarea id="mov_descripcion" class="form-control" rows="3" placeholder="Detalle la novedad o trabajo realizado..."></textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="control-label">Técnicos / Asistentes Intervinientes</label>
        <?php
        echo AppCheckboxListHelper::render(
            $tecnicos_asistencia,
            'idempleado',
            'descripcion',
            'asistentes_movimiento',
            []
        );
        ?>
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-default" onclick="ocultar_abm_movimiento()">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="agregarMovimiento()">
            <i class="glyphicon glyphicon-plus"></i> Añadir a la Lista
        </button>
    </div>
</div>