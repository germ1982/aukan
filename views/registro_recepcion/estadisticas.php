<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$labels = [];
$data = [];
foreach ($registros as $registro) {
    $labels[] = $registro['descripcion'];
    $data[] = $registro['visitas'];
}
?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => Url::to(['registro_recepcion/estadisticas']),
            'options' => ['id' => 'form-estadisticas']
        ]); ?>

        <div class="row">
            <div class="col-md-3">
                <label for="fecha_inicio">Inicio:</label>
                <?= DatePicker::widget([
                    'name' => 'fecha_inicio',
                    'value' => Yii::$app->formatter->asDate($fecha_inicio, 'php:d/m/Y'),
                    'options' => ['placeholder' => 'Seleccionar fecha...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-3">
                <label for="fecha_final">Final:</label>
                <?= DatePicker::widget([
                    'name' => 'fecha_final',
                    'value' => Yii::$app->formatter->asDate($fecha_final, 'php:d/m/Y'),
                    'options' => ['placeholder' => 'Seleccionar fecha...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6" style="margin-top: 20px;">
                <?= Html::submitButton('Ver', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Exportar PDF', ['registro_recepcion/exportar', 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final], [
                    'class' => 'btn btn-success',
                    'target' => '_blank'
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <h4>Listado de visitas</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sector</th>
                    <th>Visitas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r): ?>
                    <tr>
                        <td><?= Html::encode($r['descripcion']) ?></td>
                        <td><?= Html::encode($r['visitas']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <h4>Distribución por Dispositivo</h4>
        <canvas id="graficoDispositivos" width="100px" height="auto"></canvas>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderGraficoDispositivos() {
        const canvas = document.getElementById('graficoDispositivos');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    data: <?= json_encode($data) ?>,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Cantidad total de registros por dispositivo'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                return `${label}: ${value}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Si se está accediendo directamente (no por modal), ejecutarlo al cargar
    document.addEventListener("DOMContentLoaded", function() {
        renderGraficoDispositivos();
    });
</script>