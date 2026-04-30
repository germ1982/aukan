<style>
    /* --- Contenedor Principal para Árbol Lateral (L2R) --- */
.df-tree-lateral {
    display: flex;
    justify-content: flex-start; /* Crece hacia la derecha */
    align-items: center; /* Centrado vertical de la raíz */
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Manejo de las listas (niveles) */
.df-tree-lateral ul {
    position: relative;
    padding: 10px 0 10px 30px; /* Sangría a la izquierda para la línea */
    margin: 0;
    list-style: none;
    display: flex;
    flex-direction: column; /* Hijos uno debajo del otro */
    justify-content: center;
}

/* Línea vertical principal que conecta a los hermanos */
.df-tree-lateral ul::before {
    content: '';
    position: absolute;
    top: 0;
    left: 10px; /* Ajustar según padding de la ul */
    border-left: 2px solid #666; /* Color de línea */
    height: 100%;
}

/* El elemento de la lista (nodo y sus hijos) */
.df-tree-lateral li {
    position: relative;
    margin: 10px 0;
    padding: 0;
    display: flex;
    align-items: center; /* El nodo y su sub-lista alineados verticalmente */
}

/* La línea horizontal en "L" o "T" que sale hacia la derecha */
.df-tree-lateral li::before {
    content: '';
    position: absolute;
    top: 50%; /* Justo en el medio del nodo */
    left: -20px; /* Conecta con la línea vertical de la UL */
    width: 20px;
    height: 0;
    border-top: 2px solid #666;
}

/* Ajustes para el primer y último hijo del nivel */
.df-tree-lateral li:first-child::after,
.df-tree-lateral li:last-child::after {
    content: '';
    position: absolute;
    left: -22px; /* Mismo que el padding-left de la ul */
    width: 5px;
    background: #fff; /* O el color de fondo de tu sitio */
}
.df-tree-lateral li:first-child::after {
    top: 0;
    height: 50%; /* Tapa la línea vertical de arriba */
}
.df-tree-lateral li:last-child::after {
    bottom: 0;
    height: 50%; /* Tapa la línea vertical de abajo */
}

/* Quitar conector si es hijo único */
.df-tree-lateral li:only-child::after {
    height: 100%;
}

/* --- Estilo del Nodo (El cuadrito) --- */
.df-nodo-lateral {
    background: #fff;
    border: 2px solid #666;
    padding: 8px 15px;
    border-radius: 6px;
    color: #333;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
    min-width: 150px;
    z-index: 10; /* Para que esté sobre las líneas */
    transition: all 0.3s ease;
}

/* Hover sutil */
.df-nodo-lateral:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
    transform: translateX(3px); /* Pequeño movimiento a la derecha */
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.4);
}
    
</style>

<?php
use app\models\OrganismoOrgDec;
use app\models\Organismo;
use yii\helpers\Html;

/* @var $iddecreto integer */

$relacionRaiz = OrganismoOrgDec::find()->where(['iddecreto' => $iddecreto])->one();

if ($relacionRaiz): 
    $raiz = Organismo::findOne($relacionRaiz->idorganismo);
?>
    <div class="df-tree-lateral">
        <ul>
            <?= renderizarNodoLateral($raiz, $iddecreto) ?>
        </ul>
    </div>

<?php else: ?>
    <div class="alert alert-info">No hay una estructura cargada para visualizar lateralmente.</div>
<?php endif; ?>

<?php
/**
 * Función recursiva para el estilo Lateral (Izquierda a Derecha)
 */
function renderizarNodoLateral($nodo, $iddecreto) {
    ob_start();
    ?>
    <li>
        <div class="df-nodo-lateral">
            <strong><?= Html::encode($nodo->descripcion) ?></strong>
            
            <?= Html::a('<i class="fa fa-plus-circle"></i>', 
                ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo], 
                [
                    'role' => 'modal-remote', 
                    'title' => 'Agregar Dependencia', 
                    'class' => 'text-success',
                    'style' => 'margin-left: 10px;'
                ]
            ) ?>
        </div>

        <?php 
        // Buscamos los hijos
        $hijos = Organismo::find()->where(['padre' => $nodo->idorganismo])->all(); 
        ?>

        <?php if (!empty($hijos)): ?>
            <ul>
                <?php foreach ($hijos as $hijo): ?>
                    <?= renderizarNodoLateral($hijo, $iddecreto) ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <?php
    return ob_get_clean();
}
?>