<?php
use app\models\OrganismoOrgDec;
use app\models\Organismo;
use yii\helpers\Html;

/* @var $iddecreto integer */

// 1. Buscamos el organismo raíz vinculado a este decreto
// (Asumiendo que hay uno solo por decreto según lo que hablamos)
$relacionRaiz = OrganismoOrgDec::find()->where(['iddecreto' => $iddecreto])->one();

if ($relacionRaiz) {
    $raiz = Organismo::findOne($relacionRaiz->idorganismo);
    
    echo '<div class="tree-container">';
    echo '<ul>';
    renderizarNodo($raiz, $iddecreto);
    echo '</ul>';
    echo '</div>';
} else {
    echo '<div class="alert alert-warning">No hay una estructura iniciada para este decreto.</div>';
}

/**
 * Función recursiva para dibujar las ramas
 */
function renderizarNodo($nodo, $iddecreto) {
    echo '<li>';
    // Dibujamos el nombre del organismo y el botón (+) para agregar hijos (Origen 2)
    echo '<div class="nodo-content">';
    echo Html::encode($nodo->descripcion);
    echo ' ' . Html::a('<i class="fa fa-plus-circle"></i>', 
        ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo], 
        ['role' => 'modal-remote', 'title' => 'Agregar Dependencia', 'class' => 'text-success']
    );
    echo '</div>';

    // Buscamos los hijos (organismos que tengan a este como 'padre')
    $hijos = Organismo::find()->where(['padre' => $nodo->idorganismo])->all();

    if (!empty($hijos)) {
        echo '<ul>';
        foreach ($hijos as $hijo) {
            renderizarNodo($hijo, $iddecreto);
        }
        echo '</ul>';
    }
    echo '</li>';
}
?>