<?php

use app\models\OrganismoOrgDec;
use app\models\Organismo;
use yii\helpers\Html;

/* @var $iddecreto integer */

$relacionRaiz = OrganismoOrgDec::find()
    ->alias('od')
    // 1. Seleccionamos solo los campos de la tabla intermedia
    ->select(['od.iddecreto', 'od.idorganismo'])
    // 2. El JOIN con el ON explícito
    ->innerJoin(
        'organismo o',
        'o.idorganismo = od.idorganismo'
    )
    // 3. Los filtros
    ->where(['od.iddecreto' => $iddecreto])
    ->andWhere(['o.padre' => null])
    ->one();

if ($relacionRaiz):
    $raiz = Organismo::findOne($relacionRaiz->idorganismo);
?>


    <div class="df-tree-indentado-columnar">
        <ul>
            <?= renderizarNodoColumnar($raiz, $iddecreto, 1) // Empezamos en nivel 1 
            ?>
        </ul>
    </div>

<?php else: ?>
    <div class="alert alert-info">No hay una estructura cargada.</div>
<?php endif; ?>

<?php
function renderizarNodoColumnar($nodo, $iddecreto, $nivel)
{
    ob_start();
    // Determinamos la clase según el nivel (df-nivel-1, df-nivel-2, etc.)
    $claseNivel = "df-col-nivel-" . $nodo->nivel;
?>
    <li class="<?= $claseNivel ?>">
        <div class="df-nodo-indentado">
            <span class="df-descripcion"><?= Html::encode($nodo->descripcion) ?></span>

            <?= Html::a(
                '<i class="fa fa-plus-circle"></i>',
                ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo],
                ['role' => 'modal-remote', 'class' => 'text-success']
            ) ?>
        </div>

        <?php
        $hijos = app\models\Organismo::find()->where(['padre' => $nodo->idorganismo])->all();
        if (!empty($hijos)): ?>
            <ul>
                <?php foreach ($hijos as $hijo): ?>
                    <?= renderizarNodoColumnar($hijo, $iddecreto, $nivel + 1) ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
<?php
    return ob_get_clean();
}


?>

<style>
    /* Espacio entre descripción y botón */
    .df-descripcion {
        margin-right: 15px;
        display: inline-block;
        vertical-align: middle;
    }

    /* Contenedor principal */
    .df-tree-indentado-columnar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }



    /* El secreto: margen izquierdo fijo por nivel multiplicando un ancho de columna */
    .df-col-nivel-1 {
        margin-left: 0px;
    }

    .df-col-nivel-2 {
        margin-left: 80px;
    }

    .df-col-nivel-3 {
        margin-left: 80px;
    }

    .df-col-nivel-4 {
        margin-left: 80px;
    }

    .df-col-nivel-5 {
        margin-left: 80px;
    }

    .df-col-nivel-6 {
        margin-left: 80px;
    }

    .df-col-nivel-7 {
        margin-left: 80px;
    }

    .df-nodo-indentado {
        font-size: 12px;
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #fff;
        border: 1px solid #eee;
        border-left: 4px solid #555;
        /* Una barrita lateral para dar cuerpo */
        margin-bottom: 5px;
        border-radius: 4px;
        width: fit-content;
        min-width: 300px;
        /* Para que todos los cuadritos tengan un tamaño similar */
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Nivel 1: Ministerios */
    .df-col-nivel-1>.df-nodo-indentado {
        border-left-color: #d9534f;
    }

    /* Nivel 2: Subsecretarías */
    .df-col-nivel-2>.df-nodo-indentado {
        border-left-color: #f0ad4e;
    }

    /* Nivel 3: Coordinaciones (Nueva) */
    .df-col-nivel-3>.df-nodo-indentado {
        border-left-color: #f39c12;
    }

    /* Nivel 4: Direcciones Provinciales */
    .df-col-nivel-4>.df-nodo-indentado {
        border-left-color: #5bc0de;
    }

    /* Nivel 5: Direcciones Generales (Nueva) */
    .df-col-nivel-5>.df-nodo-indentado {
        border-left-color: #3498db;
    }

    /* Nivel 6: Direcciones */
    .df-col-nivel-6>.df-nodo-indentado {
        border-left-color: #d2e052;
    }

    /* Nivel 7: Departamentos */
    .df-col-nivel-7>.df-nodo-indentado {
        border-left-color: #7ade5b;
    }

    .badge-nivel {
        background: #eee;
        color: #777;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-right: 10px;
        font-weight: bold;
    }

    .df-tree-indentado li::before {
        content: '';
        position: absolute;
        top: 20px;
        /* Ajustar según el alto del nodo */
        left: -20px;
        width: 20px;
        height: 0;
        border-top: 2px solid #ccc;
    }

    /* ============================= */
    /* BASE DEL ÁRBOL */
    /* ============================= */

    .df-tree-indentado-columnar li {
        position: relative;
        /* Necesario para posicionar las líneas internas */
        padding-left: 20px;
        /* Espacio para que entren las líneas */
    }


    /* ============================= */
    /* LÍNEA VERTICAL (columna del árbol) */
    /* ============================= */

    .df-tree-indentado-columnar li::after {
        content: '';
        position: absolute;
        top: -5;
        /* Arranca desde arriba del nodo */
        left: -20px;
        /* Se ubica a la izquierda del contenido */
        width: 0;
        height: 108%;
        /* Ocupa todo el alto del nodo */
        border-left: 1px solid #319146;
        /* Dibuja la línea vertical */
    }


    /* ============================= */
    /* LÍNEA HORIZONTAL (conexión al nodo) */
    /* ============================= */

    .df-tree-indentado-columnar li::before {
        content: '';
        position: absolute;
        top: 20px;
        /* Altura donde conecta con el nodo (ajustable) */
        left: -20px;
        /* Parte desde la línea vertical */
        width: 40px;
        /* Largo de la línea horizontal */
        height: 0;
        border-top: 1px solid #319146;
        /* Dibuja la línea horizontal */
    }


    /* ============================= */
    /* CORTE DE LÍNEA EN ÚLTIMO HIJO */
    /* ============================= */

    .df-tree-indentado-columnar li:last-child::after {
        height: 20px;
        /* Corta la línea vertical para que no siga de más */
    }


    /* Estilo base del botón expandible */
.df-btn-expandible {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    overflow: hidden;
    max-width: 30px; /* Solo muestra el icono al inicio */
    transition: max-width 0.5s ease-in-out, background-color 0.3s;
    white-space: nowrap;
    vertical-align: middle;
    padding: 5px;
    border-radius: 20px;
}

/* El texto oculto por defecto */
.df-btn-text {
    max-width: 0;
    opacity: 0;
    margin-left: 0;
    transition: all 0.5s;
    font-size: 12px;
    font-weight: bold;
}

/* EFECTO HOVER: Se estira y muestra el texto */
.df-btn-expandible:hover {
    max-width: 200px; /* Se estira lo suficiente para el texto */
    background-color: #ebf7ee; /* Un verde muy clarito de fondo al expandir */
    padding-right: 12px;
    text-decoration: none;
}

.df-btn-expandible:hover .df-btn-text {
    max-width: 150px;
    opacity: 1;
    margin-left: 8px; /* Espacio entre el + y el texto */
}

/* Ajuste del icono para que no se mueva */
.df-btn-expandible i {
    font-size: 18px;
    flex-shrink: 0;
}
</style>