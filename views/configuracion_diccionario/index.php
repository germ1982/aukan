<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$gridColumns = require(__DIR__ . '/_columns.php');



$boton_registro = Html::a(
    '',
    ['registro_tecnico/index'], 
    [
        'title' => 'Diccionario', 
        'class' => 'btn btn-primary boton_menu neon',
        'style' => '
            background-image: url("img/registros_tecnicos.jpg"); /* Asegúrate de que la ruta sea correcta */
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

$customButtonsA = "$boton_registro"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '10px'; // Tamaño de letra para la grilla

$dataProvider = $dataProvider ?? null; // Asegúrate de que $dataProvider esté definido
$searchModel = $searchModel ?? null; // Asegúrate de que $

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Diccionario De Palabras De Silvana',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
?>