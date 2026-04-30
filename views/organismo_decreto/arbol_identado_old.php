<style>
    /* Contenedor del árbol vertical */
.df-tree-vertical {
    list-style: none;
    padding-left: 20px;
}

.df-tree-vertical ul {
    list-style: none;
    padding-left: 30px; /* Sangría para los hijos */
    position: relative;
}

/* La línea vertical que conecta los hermanos */
.df-tree-vertical ul::before {
    content: '';
    position: absolute;
    top: 0;
    left: 10px;
    border-left: 2px solid #ccc;
    height: 100%;
    width: 0;
}

.df-tree-vertical li {
    position: relative;
    margin: 0;
    padding: 10px 5px;
}

/* El conector en forma de "L" para cada nodo */
.df-tree-vertical li::before {
    content: '';
    position: absolute;
    top: 20px; /* Ajustar según el alto del nodo */
    left: -20px;
    width: 20px;
    height: 0;
    border-top: 2px solid #ccc;
}

/* Quitamos la línea vertical sobrante del último hijo */
.df-tree-vertical ul li:last-child::after {
    content: '';
    position: absolute;
    top: 22px; 
    left: -22px;
    width: 5px;
    height: 100%;
    background: #fff; /* O el color de fondo de tu sitio */
}

/* Estilo del nodo (el cuadrito con el texto) */
.df-nodo-vertical {
    background: #fff;
    border: 1px solid #ddd;
    padding: 5px 12px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
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
    <div class="df-tree-vertical">
        <ul>
            <?= renderizarNodoIdentado($raiz, $iddecreto) ?>
        </ul>
    </div>

<?php else: ?>
    <div class="alert alert-info">No hay una estructura cargada.</div>
<?php endif; ?>

<?php
/**
 * Función recursiva para el estilo Identado (Vertical)
 */
function renderizarNodoIdentado($nodo, $iddecreto) {
    ob_start();
    ?>
    <li>
        <div class="df-nodo-vertical">
            <strong><?= Html::encode($nodo->descripcion) ?></strong>
            
            <?= Html::a('<i class="fa fa-plus-circle"></i>', 
                ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo], 
                [
                    'role' => 'modal-remote', 
                    'title' => 'Agregar Dependencia', 
                    'class' => 'text-success',
                    'style' => 'margin-left: 8px;'
                ]
            ) ?>
        </div>

        <?php 
        // Buscamos si este nodo tiene hijos
        $hijos = Organismo::find()->where(['padre' => $nodo->idorganismo])->all(); 
        ?>

        <?php if (!empty($hijos)): ?>
            <ul>
                <?php foreach ($hijos as $hijo): ?>
                    <?= renderizarNodoIdentado($hijo, $iddecreto) ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
    <?php
    return ob_get_clean();
}
?>