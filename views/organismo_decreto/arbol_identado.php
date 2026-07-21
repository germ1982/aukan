<?php

use app\models\OrganismoOrgDec;
use app\models\Organismo;
use yii\helpers\Html;

// Registrar los paquetes de assets de AjaxCrud y CommonIndex
\johnitvn\ajaxcrud\CrudAsset::register($this);
\app\assets\CommonIndexAsset::register($this);

// Registrar el archivo CSS local dependiendo de AppAsset
$this->registerCssFile('@web/css/css_index_views.css', [
    'depends' => [\app\assets\AppAsset::class]
]);

$iddecreto = $iddecreto ?? null;

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

    $hijos = app\models\Organismo::find()->where(['padre' => $nodo->idorganismo, 'activo' => 1])->all();
    $dispositivos = app\models\OrganismoDispositivo::find()->where(['idorganismo' => $nodo->idorganismo, 'activo' => 1])->all();
    
    $tieneHijos = (!empty($hijos) || !empty($dispositivos));
?>
    <li class="<?= $claseNivel ?> <?= $tieneHijos ? 'df-nodo-rama' : '' ?>">
        <div class="df-nodo-indentado">
            <?php if ($tieneHijos): ?>
                <button type="button" class="df-btn-toggle-rama" title="Contraer/Expandir">
                    <i class="fa fa-chevron-down"></i>
                </button>
            <?php endif; ?>

            <span class="df-descripcion"><?= Html::encode($nodo->descripcion) ?></span>

            <?= Html::a(
                '<i class="fa fa-plus-circle"></i><span class="df-btn-text">Crear Organismo</span>',
                ['organismo/create', 'origen_alta' => 2, 'iddecreto' => $iddecreto, 'idpadre' => $nodo->idorganismo],
                ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-success']
            ) ?>
            <?= Html::a(
                '<i class="fa fa-edit"></i><span class="df-btn-text">Editar Organismo</span>',
                ['organismo/update', 'id' => $nodo->idorganismo],
                ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-warning']
            ) ?>
            <?= Html::a(
                '<i class="fa fa-eye"></i><span class="df-btn-text">Ver Organismo</span>',
                ['organismo/view', 'id' => $nodo->idorganismo],
                ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-info']
            ) ?>
            <?= Html::a(
                '<i class="fa fa-plus-circle" style="color: #9b59b6;"></i><span class="df-btn-text">Crear Dispositivo</span>',
                ['organismo_dispositivo/create', 'origen_alta' => 1, 'idorganismo' => $nodo->idorganismo],
                ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-success']
            ) ?>

        </div>

        <?php if ($tieneHijos): ?>
            <ul class="df-rama-hijos">
                <?php foreach ($dispositivos as $disp): ?>
                    <?= renderizarNodoDispositivo($disp) ?>
                <?php endforeach; ?>

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

<?php
/**
 * @param mixed $dispositivo
 * @return string
 */
function renderizarNodoDispositivo($dispositivo)
{
    ob_start();
    $empleados = app\models\Empleado::get_por_dispositivo($dispositivo->iddispositivo);
    $inventario = app\models\Inventario::get_por_dispositivo($dispositivo->iddispositivo);

    $tieneDetalle = (!empty($empleados) || !empty($inventario));
?>
    <li class="df-col-nivel-8 <?= $tieneDetalle ? 'df-nodo-rama' : '' ?>">
        <div class="df-nodo-indentado df-nodo-dispositivo"> 
            <?php if ($tieneDetalle): ?>
                <button type="button" class="df-btn-toggle-rama" title="Contraer/Expandir">
                    <i class="fa fa-chevron-down"></i>
                </button>
            <?php endif; ?>

            <span class="df-descripcion">
                <?= Html::encode($dispositivo->descripcion)/* .' Telefono: ' .Html::encode($dispositivo->telefono) */  ?>
            </span>

            <?= Html::a(
                    '<i class="fa fa-edit"></i><span class="df-btn-text">Editar Dispositivo</span>',
                    ['organismo_dispositivo/update', 'id' => $dispositivo->iddispositivo],
                    ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-warning']
                ) .
                Html::a(
                    '<i class="fa fa-eye"></i><span class="df-btn-text">Ver Dispositivo</span>',
                    ['organismo_dispositivo/view', 'id' => $dispositivo->iddispositivo],
                    ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-info']
                ). 
                (
                !empty($empleados) ? '' : 
                Html::a(
                    '<i class="fa fa-user" style="color: #0075fa; padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text">Nuevo Empleado</span>', 
                    ['empleado/create', 'origen_alta' => 1, 'iddispositivo' => $dispositivo->iddispositivo], 
                    ['role' => 'modal-remote', 'class' => 'df-btn-expandible text-warning', 'title' => 'Añadir Empleado al Dispositivo']).
                Html::a(
                        '<i class="fa fa-box"  style="color: #a8a606;  padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text">    </span>',
                        ['inventario/create'],
                        ['role' => 'modal-remote', 'class' => ' text-warning', 'title' => 'Añadir Articulo a Inventario']
                    )
                )
            ?>
        </div>

        <?php if ($tieneDetalle): ?>
            <ul class="df-rama-hijos">
                <?= renderizarListaEmpleados($empleados, $dispositivo, $inventario) ?>
            </ul>
        <?php endif; ?>
    </li>
<?php
    return ob_get_clean();
}

function renderizarListaEmpleados($empleados, $dispositivo, $inventario = [])
{
    ob_start();
?>
    <li class="df-col-nivel-9">
        <div class="df-nodo-indentado df-nodo-personal-lista">

            <div style="display: flex; width: 100%; gap: 15px; box-sizing: border-box;">

                <div style="flex: 1; width: 50%; min-width: 0;">
                    <h4 class="df-titulo-empleados" style="font-size: 13px; margin-top: 0; font-weight: bold; color: #555; padding-bottom: 5px; border-bottom: 1px solid #eee;">
                        Empleados
                        <?=
                        Html::a(
                            '<i class="fa fa-user"  style="color: #0075fa; padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text"></span>',
                            ['empleado/create', 'origen_alta' => 1, 'iddispositivo' => $dispositivo->iddispositivo],
                            ['role' => 'modal-remote', 'class' => 'text-warning', 'title' => 'Añadir Empleado al Dispositivo']
                        ) .
                            Html::a(
                                '<i class="fa fa-users"  style="color: #108801; padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text"></span>',
                                ['empleado/migrar_empleados', 'iddispositivo_viejo' => $dispositivo->iddispositivo],
                                ['role' => 'modal-remote', 'class' => 'text-warning', 'title' => 'Migrar Empleados']
                            )
                        ?>
                    </h4>

                    <div class="df-lista-interna-empleados" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($empleados as $emp): ?>
                            <div class="df-item-empleado-linea" style="padding: 4px 0; border-bottom: 1px solid #f9f9f9; font-size: 11px; display: flex; justify-content: space-between; align-items: center;">

                                <div style="flex: 1; min-width: 0; padding-right: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa fa-user-o text-muted" style="font-size: 10px; margin-right: 3px;"></i>
                                    <span style="color: #666;" title="<?= Html::encode($emp['descripcion']) ?>">
                                        <?= Html::encode($emp['descripcion']) ?>
                                    </span>
                                </div>

                                <div style="flex-shrink: 0; display: flex; gap: 6px;">
                                    <?= Html::a(
                                        '<i class="fa fa-edit"></i>',
                                        ['empleado/update', 'id' => $emp['idempleado']],
                                        ['role' => 'modal-remote', 'class' => 'text-warning ', 'style' => 'font-size: 12px;', 'title' => 'Editar']
                                    ) ?>
                                    <?= Html::a(
                                        '<i class="fa fa-eye"></i>',
                                        ['empleado/view', 'id' => $emp['idempleado']],
                                        ['role' => 'modal-remote', 'class' => 'text-info', 'style' => 'font-size: 12px;', 'title' => 'Ver']
                                    ) ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="flex: 1; width: 50%; min-width: 0; border-left: 1px solid #eee; padding-left: 10px;">
                    <h4 style="font-size: 13px; margin-top: 0; font-weight: bold; color: #555; padding-bottom: 5px; border-bottom: 1px solid #eee;">
                        Inventario
                        <?=
                        Html::a(
                            '<i class="fa fa-plus"  style="color: #a8a606;  padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text">    </span>',
                            ['inventario/create', 'origen_alta' => 1, 'iddispositivo' => $dispositivo->iddispositivo],
                            ['role' => 'modal-remote', 'class' => ' text-warning', 'title' => 'Añadir Articulo a Inventario']
                        )
                            .
                            Html::a(
                                '<i class="fa fa-boxes"  style="color: #e9b200;  padding-right: 5px; padding-left: 5px;"></i><span class="df-btn-text">    </span>',
                                ['articulo/create'],
                                ['role' => 'modal-remote', 'class' => ' text-warning', 'title' => 'Nuevo Articulo']
                            )
                        ?>
                    </h4>


                    <div class="df-lista-interna-empleados" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($inventario as $inv): ?>
                            <div class="df-item-empleado-linea" style="padding: 4px 0; border-bottom: 1px solid #f9f9f9; font-size: 11px; display: flex; justify-content: space-between; align-items: center;">

                                <div style="flex: 1; min-width: 0; padding-right: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa fa-user-o text-muted" style="font-size: 10px; margin-right: 3px;"></i>
                                    <span style="color: #666;" title="<?= Html::encode($inv['descripcion']) ?>">
                                        <?= Html::encode($inv['descripcion']) ?>
                                    </span>
                                </div>


                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

        </div>
    </li>
<?php
    return ob_get_clean();
}
?>

<style>
    .df-col-nivel-9 {
        margin-left: 60px;
    }

    .df-nodo-personal-lista {
        background-color: #fcfcfc;
        border-left-color: #2ecc71 !important;
        min-width: 530px !important;
    }

    .df-lista-empleados {
        margin: 0;
        padding: 5px 0;
        list-style: none;
        font-size: 11px;
    }

    .df-item-empleado {
        padding: 2px 0;
        border-bottom: 1px dotted #eee;
    }

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

    .df-col-nivel-8 {
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

    .df-col-nivel-8>.df-nodo-indentado {
        border-left-color: #9b59b6;
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

    .df-tree-indentado-columnar li::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -20px;
        width: 0;
        height: 108%;
        border-left: 1px solid #319146;
    }

    .df-tree-indentado-columnar li::before {
        content: '';
        position: absolute;
        top: 20px;
        left: -20px;
        width: 40px;
        height: 0;
        border-top: 1px solid #319146;
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
        max-width: 30px;
        /* Solo muestra el icono al inicio */
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
        max-width: 200px;
        /* Se estira lo suficiente para el texto */
        background-color: #ebf7ee;
        /* Un verde muy clarito de fondo al expandir */
        padding-right: 12px;
        text-decoration: none;
    }

    .df-btn-expandible:hover .df-btn-text {
        max-width: 150px;
        opacity: 1;
        margin-left: 8px;
        /* Espacio entre el + y el texto */
    }

    /* Ajuste del icono para que no se mueva */
    .df-btn-expandible i {
        font-size: 14px;
        flex-shrink: 0;
    }

    .df-nodo-dispositivo {
        background-color: #f5dafa;
        border-left-color: #cda2df;
    }

    /* =========================================================================
       BOTÓN DE TOGGLE MINIMALISTA CÍRCULO
       ========================================================================= */
    .df-btn-toggle-rama {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        cursor: pointer;
        font-size: 9px;
        color: #64748b;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .df-btn-toggle-rama:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #cbd5e1;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }

    /* Rotación elegante del chevron */
    .df-btn-toggle-rama i {
        transition: transform 0.2s ease;
    }
    
    .df-rama-contraida .df-btn-toggle-rama i {
        transform: rotate(-90deg);
    }

    /* Clases lógicas para colapsar */
    .df-rama-oculta {
        display: none !important;
    }

    .df-nodo-rama.df-rama-contraida::after {
        height: 25px !important;
    }
</style>

<?php
$js = <<<JS
$(document).on('click', '.df-btn-toggle-rama', function(e) {
    e.preventDefault();
    
    var \$btn = $(this);
    var \$liContenedor = \$btn.closest('li');
    var \$ramaHijos = \$liContenedor.find('> .df-rama-hijos');
    
    if (\$ramaHijos.length) {
        \$ramaHijos.toggleClass('df-rama-oculta');
        \$liContenedor.toggleClass('df-rama-contraida');
    }
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>