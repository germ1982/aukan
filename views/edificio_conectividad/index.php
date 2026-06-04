<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile(
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
);

$this->registerJsFile(
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$gridColumns = require(__DIR__ . '/_columns.php');

$boton_edificios = Html::a(
    '<i class="fa fa-institution"></i> Edificios',
    ['edificio/index'],
    ['title' => 'Edificios', 'class' => 'btn btn-primary boton_menu neon']
);
$boton_indicadores = Html::a(
    '<i class="fa fa-users"></i> Indicadores',
    ['view_indicadores'],
    ['title' => 'Asistentes', 'class' => 'btn btn-primary boton_menu neon','target' => '_blank']
);


$customButtonsA = "$boton_edificios $boton_indicadores"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']) .
                        '{toggleData}'.
                        '{export}' ;

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '10px'; // Tamaño de letra para la grilla

$dataProvider = $dataProvider ?? null; // Asegúrate de que $dataProvider esté definido
$searchModel = $searchModel ?? null; // Asegúrate de que $

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Conectividad de Edificios',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
?>

