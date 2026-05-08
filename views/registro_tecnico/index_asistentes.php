<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$gridColumns = require(__DIR__ . '/_columns_asistentes.php');

$boton_registros = Html::a(
                                    '<i class="fa fa-list"></i> Registros Tecnicos',
                                    ['index'],
                                    ['title' => 'Registros Tecnicos', 'class' => 'btn btn-primary boton_menu neon']
                                );

$boton_tipos_registro = Html::a(
                                        '<i class="fa fa-tags"></i> Tipos de Registro',
                                        ['index_tipos_registro'],
                                        ['title' => 'Tipos de Registro', 'class' => 'btn btn-primary boton_menu neon']
                                    );

$customButtonsA = "$boton_registros . $boton_tipos_registro"; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = Html::a(
    '<i class="glyphicon glyphicon-plus"></i>',
    ['registro_tecnico/create_asistentes', 'id_configuracion_tipo' => \app\models\ConfiguracionTipo::TIPO_ASISTENCIA_INFORMATICA],
    ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']
) .
Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index_asistentes'], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']) .
'{toggleData}' .
'{export}';

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '11px'; // Tamaño de letra para la grilla

$dataProvider = $dataProvider ?? null; // Asegúrate de que $dataProvider esté definido
$searchModel = $searchModel ?? null; // Asegúrate de que $

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Asistentes de Registro Tecnico',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);
