<?php

use yii\helpers\Html;

// Preparar los datos para el gráfico
$labels = [];
$data = [];
foreach ($registros as $registro) {
    $labels[] = $registro['descripcion'];
    $data[] = $registro['visitas'];
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        color: #333;
    }

    h1 {
        text-align: center;
        color: #003366;
        font-size: 20pt;
        margin-bottom: 10px;
    }

    h3, h4 {
        color: #003366;
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 4px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table th {
        background-color: #f2f2f2;
        color: #003366;
        font-weight: bold;
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    .table td {
        border: 1px solid #ccc;
        padding: 8px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .col-md-8, .col-md-4 {
        box-sizing: border-box;
    }

    .col-md-8 {
        width: 65%;
    }

    .col-md-4 {
        width: 30%;
        text-align: center;
    }

    .grafico {
        width: 100%;
        max-width: 800px;
        margin-top: 10px;
        border: 1px solid #ccc;
        padding: 5px;
        background: #fff;
    }

    .footer {
        text-align: center;
        font-size: 9pt;
        margin-top: 30px;
        color: #999;
    }
</style>

<h1>Estadísticas de Derivación</h1>

<div class="row">
    <div class="col-md-12">
        <h3>Listado de Visitas</h3>
        <table class="table">
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

    <div class="col-md-12">
        <h4>Distribución por Dispositivo</h4>

        <?php if (file_exists($graficoImagen)): ?>
            <img src="<?= $graficoImagen ?>" class="grafico">
        <?php else: ?>
            <p>No hay gráfico disponible.</p>
        <?php endif; ?>
    </div>
</div>

<div class="footer">
    Informe generado automáticamente por DATAFAM comprometidos con la excelencia. Fecha: <?= date('d/m/Y H:i') ?>
</div>
