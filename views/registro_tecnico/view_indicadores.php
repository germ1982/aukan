<?php
use yii\helpers\Url;
use yii\helpers\Json;

// Datos hardcodeados con onda
$pendientes = 18;
$solucionados = 45;
$enAsistencia = 4;
$datosGrafico = [
    ['Tipo' => 'Soporte Software', 'Total' => 25, 'Resueltos' => 22],
    ['Tipo' => 'Hardware / PC', 'Total' => 15, 'Resueltos' => 10],
    ['Tipo' => 'Redes e Internet', 'Total' => 12, 'Resueltos' => 4],
    ['Tipo' => 'Telefonía IP', 'Total' => 8, 'Resueltos' => 8],
];

// Estilos personalizados para que no parezca el Yii2 de base
$this->registerCss("

    body { background-color: #1a1a2e; color: #fff; }
    .card-tecnica { 
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        border-radius: 15px; 
        padding: 20px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        transition: transform 0.3s;
    }
          html.fixed .inner-wrapper {
    padding-top: 45px;
  }
    .card-tecnica:hover { transform: translateY(-5px); border-color: #4ecca3; }
    .valor-kpi { font-size: 3rem; font-weight: bold; display: block; }
    .label-kpi { text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; color: #a2a2a2; }
    .bg-critico { border-left: 5px solid #ff4b2b; }
    .bg-proceso { border-left: 5px solid #4facfe; }
    .bg-exito { border-left: 5px solid #4ecca3; }
    .progress { background-color: rgba(255,255,255,0.1); border-radius: 10px; height: 8px; }
    .progress-bar-glow { box-shadow: 0 0 10px #4facfe; background: linear-gradient(90deg, #00f2fe 0%, #4facfe 100%); }
");
?>

<div class="container-fluid" style="padding-top: 20px;">
    <h2 class="text-center" style="margin-bottom: 30px; font-weight: 200;">CENTRO DE MONITOREO INFORMATICO <span style="font-weight: 800; color: #4ecca3;">DATAFAM</span></h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card-tecnica bg-critico">
                <span class="label-kpi">Tickets Pendientes</span>
                <span class="valor-kpi" style="color: #ff4b2b;"><?= $pendientes ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-tecnica bg-proceso">
                <span class="label-kpi">En Asistencia</span>
                <span class="valor-kpi" style="color: #4facfe;"><?= $enAsistencia ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-tecnica bg-exito">
                <span class="label-kpi">Resueltos Hoy</span>
                <span class="valor-kpi" style="color: #4ecca3;"><?= $solucionados ?></span>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-7">
            <div class="card-tecnica" style="height: 400px;">
                <h4 style="margin-bottom: 20px;">Carga de Trabajo por Categoría</h4>
                <canvas id="graficoNeon"></canvas>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card-tecnica" style="height: 400px; overflow-y: auto;">
                <h4 style="margin-bottom: 20px;">Eficacia de Respuesta</h4>
                <?php foreach ($datosGrafico as $dato): 
                    $porc = ($dato['Total'] > 0) ? round(($dato['Resueltos'] / $dato['Total']) * 100) : 0;
                ?>
                    <div style="margin-bottom: 20px;">
                        <div class="d-flex justify-content-between" style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><?= $dato['Tipo'] ?></span>
                            <span style="color: #4ecca3;"><?= $porc ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-glow" style="width: <?= $porc ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
$labels = Json::encode(array_column($datosGrafico, 'Tipo'));
$totales = Json::encode(array_column($datosGrafico, 'Total'));
$resueltos = Json::encode(array_column($datosGrafico, 'Resueltos'));

$this->registerJs(<<<JS
    var ctx = document.getElementById('graficoNeon').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {$labels},
            datasets: [{
                label: 'Solicitados',
                data: {$totales},
                backgroundColor: 'rgba(79, 172, 254, 0.4)',
                borderColor: '#4facfe',
                borderWidth: 2
            }, {
                label: 'Solucionados',
                data: {$resueltos},
                backgroundColor: 'rgba(78, 204, 163, 0.4)',
                borderColor: '#4ecca3',
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { labels: { color: '#fff' } } }
        }
    });
JS
);
?>