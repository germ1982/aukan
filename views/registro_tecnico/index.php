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

$customButtonsA = "$boton_asistentes . $boton_tipos_registro . $boton_ultimo_decreto"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

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