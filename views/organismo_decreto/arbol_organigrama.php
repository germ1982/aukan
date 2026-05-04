<?php
use app\models\OrganismoOrgDec;
use app\models\Organismo;
use yii\helpers\Html;

$iddecreto = $iddecreto ?? null;

$relacionRaiz = OrganismoOrgDec::find()->where(['iddecreto' => $iddecreto])->one();
?>

<?php if ($relacionRaiz): ?>
    <?php $raiz = Organismo::findOne($relacionRaiz->idorganismo); ?>
    
    <div class="df-tree-wrapper">
        <ul>
            <?= renderizarNodo($raiz, $iddecreto) ?>
        </ul>
    </div>

<?php else: ?>
    <div class="alert alert-warning">
        No hay una estructura iniciada para este decreto.
    </div>
<?php endif; ?>

<?php
/**
 * Función recursiva para dibujar las ramas
 * Separamos el HTML de la lógica
 */
function renderizarNodo($nodo, $iddecreto, $nivel = 1) {
    // Iniciamos un buffer de salida para no escupir echos directos
    ob_start(); 
    ?>
    
    <li class="nivel-<?= $nodo->nivel ?>">
        <div class="df-organismo-box">
            <strong><?= Html::encode($nodo->descripcion) ?></strong>
            <br>
            <?= Html::a('<i class="fa fa-plus-circle"></i>', 
                ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo], 
                [
                    'role' => 'modal-remote', 
                    'title' => 'Agregar Dependencia', 
                    'class' => 'text-success'
                ]
            ) ?>
        </div>

        <?php 
        $hijos = Organismo::find()->where(['padre' => $nodo->idorganismo])->all(); 
        ?>

        <?php if (!empty($hijos)): ?>
            <ul>
                <?php foreach ($hijos as $hijo): ?>
                    <?= renderizarNodo($hijo, $iddecreto) ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>

    <?php
    return ob_get_clean(); // Retornamos todo el bloque HTML como un string
}
?>

<style>

    .df-tree-wrapper {
    overflow-x: auto;
    width: 100%;
}
    /* Contenedor principal con prefijo para evitar colisiones */
.df-tree-wrapper ul {
    padding-top: 20px; 
    position: relative;
    transition: all 0.5s;
    display: flex;
    justify-content: center;
    padding-left: 0; /* Quitamos el padding por defecto de las ul */
}

.df-tree-wrapper li {
    float: left; 
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
    transition: all 0.5s;
}

/* Conectores horizontales */
.df-tree-wrapper li::before, .df-tree-wrapper li::after {
    content: '';
    position: absolute; 
    top: 0; 
    right: 50%;
    border-top: 2px solid #555555; /* Un gris un poco más oscuro para que se vea bien */
    width: 50%; 
    height: 20px;
}
.df-tree-wrapper li::after {
    right: auto; 
    left: 50%;
    border-left: 2px solid #555;
}

/* Quitar conectores en nodos únicos */
.df-tree-wrapper li:only-child::after, .df-tree-wrapper li:only-child::before {
    display: none;
}
.df-tree-wrapper li:only-child { 
    padding-top: 0;
}
.df-tree-wrapper li:first-child::before {
    border: 0 none;
}

.df-tree-wrapper li:last-child::after {
    /* border: 0 none; */
    width: 1px; /* Para que el conector horizontal no se extienda tanto en el último hijo */
}

/* Línea vertical que baja del nodo padre */
.df-tree-wrapper ul ul::before {
    content: '';
    position: absolute; 
    top: 0; 
    left: 50%;
    border-left: 2px solid #555;
    width: 0; 
    height: 20px;
}

/* Base del Box */
.df-organismo-box {
    border: 1px solid #ddd; /* Borde sutil para los lados */
    border-top: 5px solid #555; /* Franja superior gruesa por defecto */
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
    display: inline-block;
    border-radius: 6px; /* Un poco menos redondeado para que la franja luzca mejor */
    background-color: #fff;
    transition: all 0.3s;
    position: relative;
    min-width: 150px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    text-align: center;
}

/* Colores Dinámicos por Nivel */
/* Asumiendo que al renderizar el organigrama le ponés la clase del nivel al contenedor */


/* Nivel 1: El tope de la pirámide */
.nivel-1 .df-organismo-box { border-top-color: #d9534f; } /* Ministerios (Rojo) */

/* Nivel 2: Subsecretarías */
.nivel-2 .df-organismo-box { border-top-color: #f0ad4e; } /* Subsecretarías (Naranja) */

/* Nivel 3: Coordinaciones (La que se sumó) */
.nivel-3 .df-organismo-box { border-top-color: #f39c12; } /* Coordinaciones (Ámbar/Dorado) */

/* Nivel 4: Direcciones Provinciales */
.nivel-4 .df-organismo-box { border-top-color: #5bc0de; } /* Dir. Provinciales (Celeste) */

/* Nivel 5: Direcciones Generales (La otra nueva) */
.nivel-5 .df-organismo-box { border-top-color: #3498db; } /* Dir. Generales (Azul) */

/* Nivel 6: Direcciones */
.nivel-6 .df-organismo-box { border-top-color: #d2e052; } /* Direcciones (Lima/Verde claro) */

/* Nivel 7: Departamentos */
.nivel-7 .df-organismo-box { border-top-color: #7ade5b; } /* Departamentos (Verde) */

/* Efecto Hover para que se note que es interactivo */
.df-organismo-box:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
    border-color: #bbb;
    border-top-width: 8px; /* Un pequeño detalle: la franja se agranda al pasar el mouse */
}
</style>