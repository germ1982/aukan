<?php

    /** @var mixed $dataProvider */
    /** @var mixed $searchModel */
    use app\helpers\AppIndexGenericoHelper;

    $gridColumns = require(__DIR__ . '/_columns.php');
    $customButtonsA = ""; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

    $customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

    $anchoModal = '1200px'; // Ancho del modal en PX
    $tamañoLetra = '10px'; // Tamaño de letra para la grilla

    // 2. Renderizar la vista completa
    echo AppIndexGenericoHelper::renderIndex(
        $this,                  // Objeto View ($this)
        'Control de Movimiento de Insumos Para Eventos',      // Título
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
    <?php include __DIR__ . '/index.css'; ?>
</style>