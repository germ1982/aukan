<?php

use yii\helpers\Html;

/* @var $model app\models\OrganismoDecreto */

$hoy = new DateTime();
$inicio = new DateTime($model->periodo_inicio);
$fin = new DateTime($model->periodo_final);

$estadoFecha = '';
$claseEstado = '';

if ($fin < $hoy) {
    $estadoFecha = 'Vencido';
    $claseEstado = 'danger';
} elseif ($fin->diff($hoy)->days <= 30) {
    $estadoFecha = 'Por vencer';
    $claseEstado = 'warning';
} else {
    $estadoFecha = 'Vigente';
    $claseEstado = 'success';
}

?>

<div class="organismo-decreto-view container-fluid">

    <div class="card shadow-lg border-0">
        <div class="card-header bg-white border-0 px-4 py-2 d-flex justify-content-between align-items-center">
    </div>

</div>
    </div>

</div>

        <!-- BODY -->
        <div class="card-body">

            <div class="row g-4">

                <!-- FECHAS -->
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light h-100">
                        <h6 class="text-muted mb-3">Períodos</h6>

                        <p><strong>Inicio:</strong><br>
                            <?= Yii::$app->formatter->asDate($model->periodo_inicio, 'php:d/m/Y') ?>
                        </p>

                        <p><strong>Final:</strong><br>
                            <?= Yii::$app->formatter->asDate($model->periodo_final, 'php:d/m/Y') ?>
                        </p>

                        <p><strong>Prórroga:</strong><br>
                            <?= $model->periodo_prorroga
                                ? Yii::$app->formatter->asDate($model->periodo_prorroga, 'php:d/m/Y')
                                : '<span class="text-muted">No definido</span>' ?>
                        </p>
                    </div>
                </div>

                <!-- ESTADOS -->
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">

                        <h6 class="text-muted mb-3">Estado</h6>

                        <p>
                            <strong>Activo:</strong><br>
                            <?= $model->activo
                                ? '<span class="badge bg-success">Activo</span>'
                                : '<span class="badge bg-secondary">Inactivo</span>' ?>
                        </p>

                        <p>
                            <strong>Vigencia:</strong><br>
                            <span class="badge bg-<?= $claseEstado ?>">
                                <?= $estadoFecha ?>
                            </span>
                        </p>

                    </div>
                </div>

                <!-- INFO EXTRA -->
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light h-100">

                        <h6 class="text-muted mb-3">Resumen</h6>

                        <p>
                            <strong>Duración:</strong><br>
                            <?= $inicio->diff($fin)->days ?> días
                        </p>

                        <p>
                            <strong>Restante:</strong><br>
                            <?php
                            if ($fin < $hoy) {
                                echo '<span class="text-danger">Finalizado</span>';
                            } else {
                                echo $hoy->diff($fin)->days . ' días';
                            }
                            ?>
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
<style>
    .card {
    border-radius: 12px;
}

.card-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    border-bottom: 1px solid #f1f3f5;
}

.badge {
    font-size: 0.85rem;
    padding: 6px 10px;
}
</style>