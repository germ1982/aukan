<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use app\models\InformaticaControlInsumosEventos;

$gridColumns = require(__DIR__ . '/_columns.php');

$boton_asistentes = Html::a(
    '<i class="fa fa-users"></i> Asistentes Técnicos',
    ['index_asistentes'],
    ['title' => 'Asistentes', 'class' => 'btn btn-primary boton_menu neon']
);

$boton_tipos_registro = Html::a(
    '<i class="fa fa-tags"></i> Tipos de Registro',
    ['index_tipos_registro'],
    ['title' => 'Tipos de Registro', 'class' => 'btn btn-primary boton_menu neon']
);

$boton_ultimo_decreto_old = Html::a(
    '<i class="fa fa-building"></i> Estructura',
    ['organismo_decreto/cargar_arbol', 'id' => 2, 'iddecreto' => 1],
    [
        'title' => 'Ultimo Decreto',
        'class' => 'btn btn-primary boton_menu neon'
    ]
);

$boton_ultimo_decreto = Html::a(
    '',
    ['organismo_decreto/cargar_arbol', 'id' => 2, 'iddecreto' => 1],
    [
        'title' => 'Ultimo Decreto',
        'class' => 'btn btn-primary boton_menu neon',
        'style' => '
            background-image: url("img/datafam_estructura.jpg");
            background-size: cover;
            background-position: center;
            color: white; 
            border: none;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            padding: 15px 60px;
        '
    ]
);

$boton_diccionario = Html::a(
    '',
    ['configuracion_diccionario/index'],
    [
        'title' => 'Diccionario',
        'class' => 'btn btn-primary boton_menu neon',
        'style' => '
            background-image: url("img/diccionario.jpg");
            background-size: cover;
            background-position: center;
            color: white; 
            border: none;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            padding: 15px 60px;
        '
    ]
);

$boton_indicadores = Html::a(
    'Indicadores',
    ['view_indicadores'],
    ['title' => 'Asistentes', 'class' => 'btn btn-primary boton_menu neon','target' => '_blank']
);

$boton_indicadores_react = Html::a(
    'Indicadores',
    ['view_indicadores_react'],
    ['title' => 'Asistentes', 'class' => 'btn btn-primary boton_menu neon','target' => '_blank']
);

// 1. Evaluación de estados en la base de datos
$hayPrestamo = InformaticaControlInsumosEventos::find()
    ->where(['estado' => InformaticaControlInsumosEventos::ESTADO_EN_PRESTAMO])
    ->exists();

$haySolicitado = InformaticaControlInsumosEventos::find()
    ->where(['estado' => InformaticaControlInsumosEventos::ESTADO_SOLICITADO])
    ->exists();

// 2. Criterio de color y texto dinámico
if ($hayPrestamo) {
    $claseAlerta = 'btn-alerta-naranja';
    $textoIndicador = '<i class="fa fa-warning"></i> ALERTA!!';
    $funcion = "mostrar_alerta('roja')";
} elseif ($haySolicitado) {
    $claseAlerta = 'btn-alerta-amarillo';
    $textoIndicador = '<i class="fa fa-warning"></i> ALERTA';
    $funcion = "mostrar_alerta('amarilla')";
} else {
    $claseAlerta = 'btn-alerta-verde';
    $textoIndicador = '<i class="fa fa-check-circle"></i> Insumos en Casa';
    $funcion = "mostrar_alerta('verde')";
}

// 3. Render con Html::button
$boton_alerta_insumos_prestamos = Html::button(
    $textoIndicador,
    [
        'title' => 'Préstamo Insumos',
        'class' => 'btn boton_menu ' . $claseAlerta,
        'onclick' => $funcion,
    ]
);

$customButtonsA = "$boton_asistentes . $boton_tipos_registro . $boton_ultimo_decreto . $boton_diccionario.$boton_alerta_insumos_prestamos $boton_indicadores_react ";

$customButtonsB = '';

$anchoModal = '1200px';
$tamañoLetra = '10px';

$dataProvider = $dataProvider ?? null;
$searchModel = $searchModel ?? null;

// Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,
    'Registro Tecnico',
    $gridColumns,
    $dataProvider,
    $searchModel,
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
?>
<style>
    <?php include __DIR__ . '/index.css'; ?>
</style>


<?php
$url = \yii\helpers\Url::to(['registro_tecnico/check_alerta']);

$this->registerJs(
    <<<JS
    // A. CONFIGURACIÓN INICIAL
    var urlSonido = "https://tmpfiles.org/dl/wtw0A5zfVOP7/registro.wav";
    var sonido = new Audio(urlSonido); 

    document.addEventListener('click', function() {
        sonido.play().catch(err => {
            console.log("Audio bloqueado: El usuario debe hacer clic en la página al menos una vez.");
        });
    }, { once: true });

    // B. EL TEMPORIZADOR (LOOP)
    setInterval(function() {
        fetch('{$url}')
            .then(response => response.json())
            .then(data => {
                if (data.disparar) {
                    sonido.play().catch(err => {
                        console.log("Audio bloqueado: El usuario debe hacer clic en la página al menos una vez.");
                    });
                }
            })
            .catch(err => console.error("Error en la petición de alerta:", err));
    }, 120000);

    // C. FUNCIÓN GLOBAL DE ALERTA
    window.mostrar_alerta = function(tipo) {
        let contenido = "Sin Insumos Prestados";
        let titulo = 'Sin Insumos de eventos Prestados <br> Todo es Paz....';
        let color = 'blue';
        let gif = 'https://i.giphy.com/pa42oCzjwtVQc.webp';

        switch (tipo) {
            case 'roja':
                contenido = "Insumos Prestados";
                titulo = 'ALERTA!!! <br> Hay insumos Prestados';
                color = 'red';
                gif = 'https://i.giphy.com/3ov9k9Ss9N3wO6FQ7C.webp';
                break;

            case 'amarilla':
                contenido = "Insumos Solicitados";
                titulo = 'Atencion!!! <br> Hay Solicitudes para el prestamo de insumos de eventos';
                color = 'yellow';
                gif = 'https://i.giphy.com/hqmfJ2HdlyU6jEJBcH.gif';
                break;
        }

        mostrarAlerta(titulo, contenido, null, color, gif);
    };
JS
);
?>