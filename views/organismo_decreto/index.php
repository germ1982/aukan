<?php
    use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

    $gridColumns = require(__DIR__ . '/_columns.php');
    $customButtonsA = Html::a(
                            'Manual',
                            Url::to('instructivos/manual_navidad_compartida.pdf'),
                            [
                                'data-pjax' => 0,
                                'data-toggle' => 'tooltip',
                                'title' => 'Ver Manual de Uso',
                                'class' => 'btn btn-primary boton_menu neon',
                                'target' => '_blank'
                            ]
                        ) ; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

    $customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

    $anchoModal = '1200px'; // Ancho del modal en PX
    $tamañoLetra = '11px'; // Tamaño de letra para la grilla

    // 2. Renderizar la vista completa
    echo AppIndexGenericoHelper::renderIndex(
        $this,                  // Objeto View ($this)
        'Decretos',      // Título
        $gridColumns,           // Columnas
        $dataProvider,          // DataProvider (viene del controlador)
        $searchModel,           // SearchModel (viene del controlador)
        $customButtonsA,
        $customButtonsB,
        $anchoModal,
        $tamañoLetra,
    );
?>