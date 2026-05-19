<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$gridColumns = require(__DIR__ . '/_columns.php');



$boton_articulos = Html::a(
    '<i class="fas fa-boxes"></i> Articulos',
    ['articulo/index'],
    ['title' => 'Artículos', 'class' => 'btn btn-primary boton_menu neon','target' => '_blank']
);

$boton_nuevo_articulo = Html::a(
    '<i class="fas fa-plus"></i> Nuevo Artículo',
    ['articulo/create'],
    ['title' => 'Nuevo Artículo', 'class' => 'btn btn-primary boton_menu neon','role' => 'modal-remote']
);

$customButtonsA = "$boton_articulos. $boton_nuevo_articulo"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '10px'; // Tamaño de letra para la grilla

$dataProvider = $dataProvider ?? null; // Asegúrate de que $dataProvider esté definido
$searchModel = $searchModel ?? null; // Asegúrate de que $

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Inventario',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
?>

