<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
            background-image: url("img/datafam_estructura.jpg"); /* Asegúrate de que la ruta sea correcta */
            background-size: cover;
            background-position: center;
            color: white; 
            border: none;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            padding: 15px 60px; /* Ajustalo según el tamaño que quieras */
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
            background-image: url("img/diccionario.jpg"); /* Asegúrate de que la ruta sea correcta */
            background-size: cover;
            background-position: center;
            color: white; 
            border: none;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            padding: 15px 60px; /* Ajustalo según el tamaño que quieras */
        '
    ]
);

$boton_indicadores = Html::a(
    '<i class="fa fa-users"></i> Indicadores',
    ['view_indicadores'],
    ['title' => 'Asistentes', 'class' => 'btn btn-primary boton_menu neon','target' => '_blank']
);

$customButtonsA = "$boton_asistentes . $boton_tipos_registro . $boton_ultimo_decreto . $boton_diccionario.$boton_indicadores"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '10px'; // Tamaño de letra para la grilla

$dataProvider = $dataProvider ?? null; // Asegúrate de que $dataProvider esté definido
$searchModel = $searchModel ?? null; // Asegúrate de que $

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Registro Tecnico',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
?>
<style>
    .form-control {
        font-size: 11px !important;
        height: 30px !important;
        padding: 5px 5px !important;
    }

    .select2-container--krajee .select2-selection {
        height: 30px !important;
        padding: 5px 5px !important;
        font-size: 11px !important;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        height: 28px !important;
    }

    /* Tamaño de la letra de las opciones en el desplegable */
    .select2-results__option {
        font-size: 10px !important;
    }

    /* Tamaño de la letra de la opción seleccionada */
    .select2-selection__rendered {
        font-size: 10px !important;
    }

    textarea.form-control {
        height: auto !important;
    }

    label {
        margin-bottom: 0px !important;
    }
</style>


<?php
/** * PASO 1: Generar la URL de consulta 
 * Usamos el helper de Yii2 para que la ruta sea dinámica y no falle si cambia el dominio.
 */
$url = \yii\helpers\Url::to(['registro_tecnico/check_alerta']);

/** * PASO 2: Registrar el bloque de JavaScript
 * El <<<JS le indica a PHP que todo lo que sigue es código de cliente.
 */
$this->registerJs(
    <<<JS
    // A. CONFIGURACIÓN INICIAL
    // Guardamos la URL del audio en una variable de texto.
    var urlSonido = "https://tmpfiles.org/dl/wtw0A5zfVOP7/registro.wav";
    
    // Convertimos ese texto en un "Objeto de Audio" real para que tenga funciones como .play()
    var sonido = new Audio(urlSonido); 
    // Función para desbloquear el audio con el primer clic del usuario
    document.addEventListener('click', function() {
        // Reproducimos un segundo y pausamos para "pedir permiso" al navegador

        sonido.play().catch(err => {
                        console.log("Audio bloqueado: El usuario debe hacer clic en la página al menos una vez.");
                    });

    }, { once: true }); // El 'once: true' hace que esto se ejecute SOLO una vez

    // B. EL TEMPORIZADOR (LOOP)
    // Definimos una función que se ejecute sola cada X cantidad de tiempo.
    setInterval(function() {
        
        // C. LA CONSULTA AL SERVIDOR (FETCH / AJAX)
        // El navegador "viaja" a la URL que definimos arriba en PHP.
        fetch('{$url}')
            .then(response => response.json()) // Intentamos convertir la respuesta en un objeto JSON
            .then(data => {
                
                // D. LA CONDICIÓN DE DISPARO
                // Si el controlador mandó un "disparar: true", procedemos.
                if (data.disparar) {
                    
                    // E. EJECUCIÓN DEL SONIDO
                    // Intentamos reproducir. Usamos .catch por si el navegador bloquea el autoplay.
                    sonido.play().catch(err => {
                        console.log("Audio bloqueado: El usuario debe hacer clic en la página al menos una vez.");
                    });

                    // F. INTERFAZ DE USUARIO
                    // Si tenías un spinner o cartel de carga, lo ocultamos.
                    //$('#loading').hide();
                }
            })
            // G. CONTROL DE ERRORES DE RED
            // Si el servidor está caído o no hay internet, te avisa por consola.
            .catch(err => console.error("Error en la petición de alerta:", err));

    }, 120000); // 120000 milisegundos = 2 minutos
JS
);
?>