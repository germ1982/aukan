<head>
    <style>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2.css') ?>
    </style>
</head>
<?php

use yii\helpers\Url;
use yii\helpers\Json;

require_once __DIR__ . '/view_indicadores_v2_consultas.php';



$tarjetas = ['CDI', 'HOGAR','DELEGACION','CCC','CFF','RESTO'];

foreach ($tarjetas as $tarjeta) {
    // Inicializamos los datos para cada tarjeta
    ${$tarjeta . '_cantidad'} = get_cantidad_connections($tarjeta,$tarjetas);
    ${$tarjeta . '_infraestructura'} = get_datos_agrupados($tarjeta, 'infraestructura', $tarjetas);
    ${$tarjeta . '_servicios'} = get_datos_agrupados($tarjeta, 'servicio', $tarjetas);
    ${$tarjeta . '_conexion'} = get_datos_agrupados($tarjeta, 'tipo_conexion', $tarjetas);
    ${$tarjeta . '_estados'} = get_estados_conexiones($tarjeta, $tarjetas);

    ${$tarjeta . 'Data'} = [
            'total'           => ${$tarjeta . '_cantidad'},
            'infraestructura' => ${$tarjeta . '_infraestructura'},
            'servicio'        => ${$tarjeta . '_servicios'},
            'enlace'          => ${$tarjeta . '_conexion'},
            'estados'         => ${$tarjeta . '_estados'},
    ];

}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Conectividad - AUKAN</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2.js') ?>
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="dashboard-header">
                <h2 class="dashboard-title">Centro de Monitoreo de Conectividad / <span>AUKAN</span></h2>
            </div>
        </div>
    </div>

    <div class="row">

    <?php
        foreach ($tarjetas as $tarjeta) {

            echo $this->render('view_indicadores_v2_tarjeta', [
            'title' => 'Conexiones de ' . $tarjeta,
            'accentColor' => 'var(--accent-' . strtolower($tarjeta) . ')',
            'chartId' => "chart-" . strtolower($tarjeta),
            'text_match'  => $tarjeta, // <-- AGREGAMOS ESTA LÍNEA  para renderizar el dashboar por grupo (view_indicadores_grupo_v2.php)
            'tarjetas'    => $tarjetas, // <-- ¡SÍ! CLAVALE ESTA LÍNEA ACÁ
            'data' => ${$tarjeta . 'Data'}
        ]);

        } 
     ?>

    </div>
</div>
</body>
</html>