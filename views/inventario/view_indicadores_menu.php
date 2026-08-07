<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

// Registramos jQuery explícitamente en Yii2
\yii\web\JqueryAsset::register($this);
// Usamos el iddecreto real del modelo actual


$iddecreto = 2;

// Función para construir el árbol recursivo de organismos y dispositivos
function obtenerArbolRecursivo($idOrganismo)
{
    $nodo = \app\models\Organismo::findOne($idOrganismo);
    if (!$nodo) return null;

    // 1. Inicializamos el acumulador de la entidad con todas las categorías en 0
    $totalesAcumulados = [
        'empleados' => 0,
        'cpu'       => 0,
        'monitor'   => 0,
        'teclado'   => 0,
        'mouse'     => 0,
        'impresora' => 0,
        'scanner'   => 0,
        'router'    => 0,
        'telefono'  => 0,
        'celular'   => 0,
    ];

    // Buscamos los hijos directos activos
    $hijosOrg = \app\models\Organismo::find()
        ->where(['padre' => $nodo->idorganismo, 'activo' => 1])
        ->all();

    $hijosArray = [];
    foreach ($hijosOrg as $hijo) {
        $nodoHijo = obtenerArbolRecursivo($hijo->idorganismo);
        if ($nodoHijo) {
            $hijosArray[] = $nodoHijo;

            // 3. Sumamos recursivamente todas las categorías del hijo al organismo padre
            foreach ($totalesAcumulados as $clave => $valor) {
                $totalesAcumulados[$clave] += $nodoHijo['totales'][$clave] ?? 0;
            }
        }
    }

    // Buscamos los dispositivos asociados si los hubiera
    $dispositivos = \app\models\OrganismoDispositivo::find()
        ->where(['idorganismo' => $nodo->idorganismo, 'activo' => 1])
        ->all();

    $dispArray = [];
    foreach ($dispositivos as $disp) {
        // 5. Ejecutamos la consulta completa con todos los componentes
        $totalesDisp = contarEquipamientoPorDispositivo($disp->iddispositivo);
        // Conteo de empleados ligados al dispositivo
        $cantEmpleadosDisp = contarEmpleadosPorDispositivo($disp->iddispositivo);
        $totalesDisp['empleados'] = $cantEmpleadosDisp;

        // 6. Sumamos los totales del dispositivo al total general del organismo
        foreach ($totalesDisp as $clave => $valor) {
            $totalesAcumulados[$clave] += $valor;
        }

        $dispArray[] = [
            'id' => $disp->iddispositivo,
            'descripcion' => $disp->descripcion,
            'tipo' => 'dispositivo',
            'totales' => $totalesDisp
        ];
    }

    return [
        'id' => $nodo->idorganismo,
        'descripcion' => $nodo->descripcion,
        'nivel' => $nodo->nivel,
        'tipo' => 'organismo',
        'totales' => $totalesAcumulados,
        'hijos' => array_merge($hijosArray, $dispArray)
    ];
}

// Ejemplo de cómo inicializarlo buscando la raíz del decreto (reemplazá $iddecreto por el que corresponda)
$relacionRaiz = \app\models\OrganismoOrgDec::find()
    ->alias('od')
    ->innerJoin('organismo o', 'o.idorganismo = od.idorganismo')
    ->where(['od.iddecreto' => $iddecreto])
    ->andWhere(['o.padre' => null])
    ->one();

$arbolCompleto = null;
if ($relacionRaiz) {
    $arbolCompleto = obtenerArbolRecursivo($relacionRaiz->idorganismo);
}


// Función para contar los CPUs vinculados a un dispositivo específico
function contarCpusPorDispositivo($idDispositivo)
{
    // Busca en inventario artículos que sean tipo CPU vinculados a este dispositivo
    return (int) \app\models\Inventario::find()
        ->alias('i')
        ->innerJoin('articulo a', 'a.idarticulo = i.idarticulo')
        ->innerJoin('configuracion c', 'c.descripcion = CAST(a.idtipo AS CHAR)')
        ->where([
            'i.iddispositivo' => $idDispositivo,
            'i.activo' => 1,
            'c.id_configuracion_tipo' => \app\models\ConfiguracionTipo::TIPO_ARTICULO_CPU
        ])
        ->count();
}

function contarEquipamientoPorDispositivo($idDispositivo)
{
    // Inicializamos todas las categorías solicitadas en 0
    $totales = [
        'cpu'       => 0,
        'monitor'   => 0,
        'teclado'   => 0,
        'mouse'     => 0,
        'impresora' => 0,
        'scanner'   => 0,
        'router'    => 0,
        'telefono'  => 0,
        'celular'   => 0,
    ];

    // Traemos la descripción del tipo (desde configuracion) unida a inventario por el articulo
    $registros = (new \yii\db\Query())
        ->select(['LOWER(c.descripcion) as tipo_desc', 'COUNT(i.idinventario) as cantidad'])
        ->from(['i' => 'inventario'])
        ->innerJoin(['a' => 'articulo'], 'a.idarticulo = i.idarticulo')
        ->innerJoin(['c' => 'configuracion'], 'c.id_configuracion = a.idtipo')
        ->where([
            'i.iddispositivo' => $idDispositivo,
            'i.activo' => 1
        ])
        ->groupBy(['c.id_configuracion'])
        ->all();

    // Mapeo dinámico por palabras clave (MATCH flexible)
    foreach ($registros as $reg) {
        $desc = $reg['tipo_desc'];
        $cant = (int)$reg['cantidad'];

        if (stripos($desc, 'cpu') !== false)       $totales['cpu'] += $cant;
        if (stripos($desc, 'monit') !== false)     $totales['monitor'] += $cant;
        if (stripos($desc, 'teclad') !== false)    $totales['teclado'] += $cant;
        if (stripos($desc, 'mous') !== false)      $totales['mouse'] += $cant;
        if (stripos($desc, 'impresor') !== false)  $totales['impresora'] += $cant;
        if (stripos($desc, 'scan') !== false)      $totales['scanner'] += $cant;
        if (stripos($desc, 'rout') !== false)      $totales['router'] += $cant;
        if (stripos($desc, 'telef') !== false)     $totales['telefono'] += $cant;
        if (stripos($desc, 'celul') !== false)     $totales['celular'] += $cant;
    }

    return $totales;
}

// Función auxiliar para contar empleados vinculados a un dispositivo
function contarEmpleadosPorDispositivo($idDispositivo)
{
    return (int) \app\models\Empleado::find()
        ->where([
            'iddispositivo' => $idDispositivo,
            'activo' => 1
        ])
        ->count();
}
?>

<!-- CDN rápido de Select2 para prueba directa -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    <?= include 'view_indicadores_menu.css'; ?>
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.min.js"></script>

<!-- Dejamos el árbol disponible en una variable global para tu archivo JS externo -->
<script>
    window.arbolDatos = <?= json_encode($arbolCompleto) ?>;
</script>

<script>
    <?= include 'view_indicadores_menu.js'; ?>
</script>

<!-- ==========================================
     1. SPLASH SCREEN (INTRO DE 3 SEGUNDOS)
     ========================================== -->
<div id="intro-splash-overlay">
    <div class="intro-card-frame">
        <!-- Video MP4 local con marco neón y auto-reproducción -->
        <video autoplay loop muted playsinline class="intro-media">
            <source src="<?= Yii::getAlias('@web') ?>/img/aukan_home_banner.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video>
    </div>
</div>

<!-- ==========================================
     2. FONDO Y CONTENIDO PRINCIPAL
     ========================================== -->
<div class="video-background-container">
    <iframe
        <?php
        $video = "eWRDwD6cVe8"; // Neuquén Paraíso
        $inicio = 8;
        $final = 1224;
        ?>
        src="https://www.youtube.com/embed/<?= $video ?>?autoplay=1&mute=1&loop=1&playlist=<?= $video ?>&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&vq=hd2160&start=<?= $inicio ?>&end=<?= $final ?>"
        frameborder="0"
        allow="autoplay; encrypted-media"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
</div>

<div class="overlay-div-dashboard">

    <!-- HEADER DE ANCHO A ANCHO -->
    <header class="dashboard-header-bar">
        <div class="header-content-wrapper">
            <?= Html::img('@web/img/logo_aukan.png', ['alt' => 'Logo', 'class' => 'header-logo']) ?>
            <div class="header-titles-container">
                <h1 class="header-title">Indicadores de Equipamiento Informatico</h1>
                <div id="header-breadcrumb" class="header-breadcrumb"></div>
            </div>
        </div>
    </header>

    <!-- BARRA GLOBAL DE CONTROLES -->
    <div class="dashboard-toolbar-container">
        <div id="toolbar-actions" class="toolbar-content-wrapper">
            <!-- El botón de volver se inyecta por JS si corresponde -->
            <div class="search-select-container">
                <select id="buscador-sectores" style="width: 300px;">
                    <option value="">🔍 Buscar sector...</option>
                </select>
            </div>
        </div>
        <!-- TERCERA LÍNEA: NOMBRE DEL NIVEL SUPERIOR -->
        <div style="text-align: center;">
    <div id="contenedor-padre-superior"></div>
</div>
    </div>

    <!-- BODY PARA LAS TARJETAS -->
    <main class="dashboard-body-container">
        <!-- Acá irán las tarjetas de indicadores -->
    </main>

</div>

<!-- Script simple para ocultar el splash a los 3 segundos -->