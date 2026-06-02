<?php

use app\models\EdificioOficina;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Organismo;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */

function campoCompacto($titulo, $contenido)
{
    $tituloUpper = strtoupper($titulo);
    echo "<div class='cyber-linea-horizontal'>
            <span class='cyber-label-horizontal'>$tituloUpper:</span>
            <span class='cyber-value'>$contenido</span>
          </div>";
}

function campo($titulo, $contenido)
{
    // Pasamos el título a mayúsculas para cumplir con el diseño de la imagen
    $tituloUpper = strtoupper($titulo);

    echo "<div class='cyber-field-wrapper'>
        <div class='cyber-title'>$tituloUpper</div>
        <div class='campo'>
            $contenido
        </div>
    </div>";
}

$organismo = Organismo::findOne($model->idorganismo);
$oficina = EdificioOficina::findOne($model->idoficina);
$nombreArchivo = $oficina && $oficina->plano_ubicacion ? $oficina->plano_ubicacion : 'plano_e0_o0.jpg';;
// Construimos la ruta física para verificar si el archivo existe en el servidor de XAMPP
$rutaFisica = Yii::getAlias('@webroot') . '/img/oficinas-planos/' . $nombreArchivo;
// Construimos la URL web para mostrar en las etiquetas HTML
$urlArchivo = Url::to('@web/img/oficinas-planos/' . $nombreArchivo);
// 2. Evaluamos qué tipo de archivo es y si existe
$mostrarTipo = 'default';
if (file_exists($rutaFisica) && is_file($rutaFisica)) {
    // pathinfo con PATHINFO_EXTENSION nos devuelve 'jpg', 'pdf', 'png', etc.
    $extension = strtolower(pathinfo($rutaFisica, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $mostrarTipo = 'imagen';
    } elseif ($extension === 'pdf') {
        $mostrarTipo = 'pdf';
    }
} else {
    // Si el archivo no existe, podemos asignar una imagen de "no disponible" o dejar el contenedor vacío
    $urlArchivo = Url::to('@web/img/oficinas-planos/plano_e0_o0.jpg');
    $mostrarTipo = 'imagen';
}

// Buscamos una sola vez el dispositivo para no hacer dos consultas separadas abajo
$dispositivoExtra = OrganismoDispositivo::findOne($model->iddispositivo);
$alias = $dispositivoExtra ? $dispositivoExtra->alias : '-';
$telefono = $dispositivoExtra ? $dispositivoExtra->telefono : '-';

$edificio = OrganismoDispositivo::get_edificio($model->iddispositivo);
$nombreEdificio = '-';
$detallesDireccion = 'Sin dirección registrada';
$telefonoEdificio = '-';
$geolocalizacion = '';

if ($edificio !== null) {
    // Armamos el nombre combinando fija y gestión (usando lo que vimos de nulos por las dudas)
    $fija = !empty($edificio->descripcion_fija) ? $edificio->descripcion_fija : '';
    $gestion = !empty($edificio->descripcion_gestion) ? $edificio->descripcion_gestion : '';
    $nombreEdificio = trim(($fija && $gestion) ? "$fija - $gestion" : "$fija$gestion");

    // Armamos la línea de dirección de corrido limpia
    $partesDireccion = array_filter([
        $edificio->direccion_calle,
        $edificio->direccion_altura,
        $edificio->direccion
    ]);
    if (!empty($partesDireccion)) {
        $detallesDireccion = implode(' ', $partesDireccion);
    }

    $geolocalizacion = !empty($edificio->geolocalizacion) ? trim($edificio->geolocalizacion) : '';
}

// Nueva función especializada para el indicador de estado cyberpunk
function campoEstadoCyber($titulo, $activo)
{
    $tituloUpper = strtoupper($titulo);
    $estadoClase = $activo ? 'activo' : 'inactivo';
    $simbolo = $activo ? 'fa-check-circle' : 'fa-times-circle'; // Un escudo o un 'X' apagada
    $texto = $activo ? 'SI' : 'NO'; // Un escudo o un 'X' apagada

    echo "<div class='cyber-linea-horizontal cyber-status-line'>
            <span class='cyber-label-horizontal'>$tituloUpper:</span>
            <div class='cyber-value cyber-led-status $estadoClase'>
                <span>$texto <i class='fa $simbolo'></i></span>
                <div class='cyber-led-glow'></div>
            </div>
          </div>";
}

$empleados = Empleado::get_por_dispositivo_con_foto($model->iddispositivo);
?>

<style>
    /* CONTENEDOR GENERAL ULTRA COMPACTO */
    .dispositivo-view {
        background-color: #020813;
        color: #00ffff;
        padding: 15px 20px;
        border-radius: 4px;
        font-family: 'Courier New', Courier, monospace;
        border: 1px solid rgba(0, 255, 255, 0.2);

        /* Limitamos el ancho para que no se estire infinito */
        margin: 0 auto;
    }

    /* FILA DE TEXTO EN UNA SOLA LÍNEA */
    .cyber-linea {
        display: flex;
        justify-content: 开;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px dashed rgba(0, 255, 255, 0.15);
        /* Línea divisoria sutil */
    }

    .cyber-linea-horizontal {
        display: flex;
        justify-content: 开;
        align-items: center;
        border-bottom: 1px dashed rgba(0, 255, 255, 0.15);
        /* Línea divisoria sutil */
    }

    /* Quitamos la línea al último elemento para que quede limpio */
    .cyber-linea:last-child {
        border-bottom: none;
    }

    /* EL TÍTULO (A LA IZQUIERDA) */
    .cyber-label {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #00ffff;
        min-width: 150px;
        /* Ancho fijo para que todos los datos arranquen alineados */
        display: inline-block;
    }

    .cyber-label-horizontal {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #00ffff;

        /* Ancho fijo para que todos los datos arranquen alineados */
        display: inline-block;
    }

    /* EL DATO (A LA DERECHA) */
    .cyber-value {
        font-size: 13px;
        color: #ffffff;
        /* Blanco para lectura rápida */
        padding-left: 10px;
    }

    /* CONTENEDOR REDUCIDO PARA LOS PLANOS/MAPAS */
    .cyber-value-links {
        font-size: 12px;
        color: #fff;
    }

    .cyber-value-links a {
        color: #00ffff;
        text-decoration: underline;
        margin-right: 15px;
    }

    .cyber-value-links a:hover {
        color: #fff;
    }

    .modal-content {
        background-color: #020813 !important;
        /* Fondo transparente para que se vea el diseño de la card */
        border: none;
        box-shadow: none;
    }

    .modal-body {
        padding: 2px !important;
    }

    .modal-footer {
        background-color: #020813 !important;
        /* Fondo transparente para que se vea el diseño de la card */
        border: none;
        box-shadow: none;
    }

    /* FONDO OSCURO GENERAL DE LA CARD */
    .dispositivo-view {
        background-color: #020813;
        /* Azul casi negro como el gráfico */
        color: #00ffff;
        /* Cian / Neón principal */
        padding: 5px;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        /* Toque técnico/ciberpunk */
        border: 0px solid rgba(0, 255, 255, 0.2);
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.05);
        position: relative;
        overflow: hidden;
    }

    /* DETALLES DECORATIVOS DE LOS ENCUADRES (Tech Hud) */
    .dispositivo-view::before {
        content: "++++";
        position: absolute;
        top: 5px;
        right: 15px;
        color: rgba(0, 255, 255, 0.3);
        font-size: 10px;
        letter-spacing: 3px;
    }

    /* CONTENEDOR DE CADA CAMPO */
    .cyber-field-wrapper {
        margin-bottom: 20px;
    }

    /* TÍTULOS ESTILO LOREM IPSUM (Chicos y con líneas sutiles) */
    .cyber-title {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #00ffff;

        text-transform: uppercase;
        opacity: 0.9;
        display: flex;
        align-items: center;
    }

    .cyber-title::after {
        content: "";
        flex-grow: 1;
        margin-left: 10px;
        height: 1px;
        background: linear-gradient(to right, rgba(0, 255, 255, 0.3), transparent);
    }

    /* LAS CAJAS DE TEXTO (Los inputs de visualización) */
    .campo {
        padding: 8px 12px;
        font-size: 13px;
        color: #ffffff;
        /* Texto de adentro blanco para que contraste perfecto */
        background-color: rgba(0, 255, 255, 0.03);
        /* Fondo con un toque cian mínimo */
        border: 1px solid rgba(0, 255, 255, 0.4);
        /* Borde cian definido */
        border-radius: 0px;
        /* Bordes rectos bien agresivos como los cuadros del gráfico */
        box-shadow: inset 0 0 8px rgba(0, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .campo:hover {
        border-color: #00ffff;
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
    }

    /* SECCIÓN DEL PLANO / MAPA DE LA OFICINA */
    .contenedor-mapa-cyber {
        border: 1px dashed #00ffff;
        background-color: rgba(0, 255, 255, 0.02);
        height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
        padding: 10px;
        text-transform: uppercase;
        border-radius: 0px;
    }



    /* DECORACIÓN EN BLOQUE AJEDREZADO (Como el de la derecha del gráfico) */
    .cyber-grid-deco {
        font-size: 10px;
        color: rgba(0, 255, 255, 0.2);
        line-height: 8px;
        letter-spacing: 2px;
        margin-top: 5px;
    }

    #base64image {
        display: block;
        border: 1px solid rgba(0, 255, 255, 0.4);
        padding: 8px;
        background-color: #020813;
        max-width: 100%;
    }

    /* Estilo para el contenedor del título que alinea todo horizontalmente */
    .cyber-title-flex {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #00ffff;
        margin-bottom: 6px;
        text-transform: uppercase;
        opacity: 0.9;

        /* El truco de alineación horizontal perfecta */
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0, 255, 255, 0.3);
        padding-bottom: 4px;
    }

    .cyber-title-flex i {
        font-size: 14px;
        /* Tamaño del icono un toque más sutil para el título */
        color: #00ffff;
    }

    /* El contenedor interno ahora es una fila de datos planos en vez de un mapa gigante */


    /* Letra más pequeña para la dirección y teléfono */
    .cyber-text-sub {
        font-size: 11px;
        color: #ffffff;
        opacity: 0.85;
        line-height: 1.5;
        margin: 0;
    }

    .cyber-text-sub span {
        color: #00ffff;
        /* Resaltado para etiquetas chicas */
        font-weight: bold;
    }

    /* CONTENEDOR DEL IFRAME DE GOOGLE MAPS */
    .contenedor-mapa-cyber i {
        font-size: 24px;
        color: #00ffff;
        margin-bottom: 8px;
        animation: pulse 2s infinite;
    }

    .contenedor-mapa-cyber-flat {
        padding: 8px 12px;
        background-color: rgba(0, 255, 255, 0.02);
        border-left: 2px solid #00ffff;
        /* Detalle de línea de neón a la izquierda */
    }

    .contenedor-mapa-cyber {
        border: 1px solid rgba(0, 255, 255, 0.4);
        background-color: #020813;
        position: relative;
        width: 100%;
        height: 360px;
        /* Altura compacta para que no rompa la pantalla */
        overflow: hidden;
    }

    .contenedor-mapa-cyber iframe {
        width: 100%;
        height: 100%;
        /* border: none; */

        /* TRUCO CIBERPUNK: Filtro CSS para invertir los colores de Google Maps 
          y darle un look "Modo Oscuro / Tecnológico" que pegue con el diseño.
        */
        filter: invert(90%) hue-rotate(180deg) contrast(120%) brightness(90%);
        opacity: 0.85;
    }

    .contenedor-mapa-cyber iframe:hover {
        opacity: 1;
    }

    .cyber-no-geo {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.5);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Estilos para el nuevo indicador LED de estado */
    .cyber-led-status {
        font-size: 16px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Estado ACTIVO: El LED brillante */
    .cyber-led-status.activo i {
        color: #00ffff;
        /* Cian neón */
        animation: pulseStatus 2s infinite;
    }

    .cyber-led-status.activo .cyber-led-glow {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 255, 255, 0.4);
        /* Resplandor cian */
        border-radius: 50%;
        filter: blur(8px);
        opacity: 0.8;
    }

    /* Estado INACTIVO: El LED apagado */
    .cyber-led-status.inactivo i {
        color: rgba(245, 94, 24, 0.91);
        /* Cian muy atenuado */
        opacity: 0.5;
    }

    /* Quitamos el resplandor para el estado inactivo */
    .cyber-led-status.inactivo .cyber-led-glow {
        display: none;
    }

    /* Animación de pulso para el estado activo */
    @keyframes pulseStatus {

        0%,
        100% {
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.8), 0 0 20px rgba(0, 255, 255, 0.5);
        }

        50% {
            text-shadow: 0 0 15px rgba(0, 255, 255, 1), 0 0 30px rgba(0, 255, 255, 0.8);
        }
    }


    /* Estilo cyberpunk para el contenedor del plano */
    .contenedor-mapa-cyber {
        border: 1px solid rgba(0, 255, 255, 0.4);
        background-color: #020813;
        width: 100%;
        height: 350px;
        /* Le damos buena altura para que el PDF o la imagen se luzcan */
        overflow: hidden;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .contenedor-mapa-cyber img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        /* Evita que el plano se deforme */
        opacity: 0.9;
    }

    .contenedor-mapa-cyber object,
    .contenedor-mapa-cyber iframe {
        width: 100%;
        height: 100%;
        border: none;
        background-color: #020813;
    }
</style>


<div class="dispositivo-view">
    <div class="row">

        <div class="col-md-6">
            <?= campoCompacto('Dispositivo', $model->descripcion) ?>
        </div>
        <div class="col-md-2">
            <?= campoEstadoCyber('Es Oficial', $model->es_oficial) ?>
        </div>
        <div class="col-md-2">
            <?= campoEstadoCyber('Es organismo', $model->es_organismo) ?>
        </div>
        <div class="col-md-2">
            <?= campoEstadoCyber('Activo', $model->activo) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?= campoCompacto('alias', $alias) ?>
        </div>

        <div class="col-md-8">
            <?= campoCompacto('Organismo', $organismo ? $organismo->descripcion : 'No asignado') ?>
        </div>
    </div>
</div>

<div class="dispositivo-view">

    <div class="cyber-grid-deco" style="font-size: 8px; color: rgba(0, 255, 255, 0.1); margin:0px; letter-spacing: 2px;">▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞</div>

</div>

<div class="dispositivo-view">
    <div class="row">
        <div class="col-md-6">
            <div class="cyber-field-wrapper">
                <div class="cyber-title">
                    <span>EDIFICIO: <?= strtoupper($nombreEdificio) ?></span>

                </div>
                <?= "DIRECCION: $detallesDireccion - TEL: $model->telefono" ?>
                <div class="contenedor-mapa-cyber">
                    <?php if (!empty($geolocalizacion)): ?>
                        <iframe
                            src="https://maps.google.com/maps?q=<?= $geolocalizacion ?>&z=16&t=m&output=embed"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    <?php else: ?>
                        <div class="cyber-no-geo">
                            <i class="fa fa-exclamation-triangle" style="margin-right: 6px; color: #ffcc00;"></i>
                            Coordenadas GPS no registradas en el sistema
                        </div>
                    <?php endif; ?>
                </div>
                <div class="cyber-grid-deco" style="font-size: 8px; color: rgba(0, 255, 255, 0.1); margin-top: 4px; letter-spacing: 2px;">▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="cyber-field-wrapper">
                <div class="cyber-title">Plano Edificio</div>
                <?= "OFICINA: $oficina->descripcion" ?>

                <div style="cursor: pointer;"
                    onclick="window.open('<?= $urlArchivo ?>', '_blank');"
                    title="Click para abrir plano en tamaño completo">
                    <div class="contenedor-mapa-cyber">
                        <?php if ($mostrarTipo === 'imagen'): ?>
                            <img src="<?= $urlArchivo ?>" alt="Plano de la Oficina">

                        <?php elseif ($mostrarTipo === 'pdf'): ?>
                            <object data="<?= $urlArchivo ?>#toolbar=0&navpanes=0" type="application/pdf">
                                <iframe src="<?= $urlArchivo ?>#toolbar=0&navpanes=0"></iframe>
                            </object>

                        <?php else: ?>
                            <img src="<?= $urlArchivo ?>" alt="Plano No Disponible">
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="dispositivo-view">
    <div class="row">
        <div class="col-md-3">
            <div class="cyber-field-wrapper" style="margin-bottom: 0px;">
    <div class="cyber-title" style="margin-bottom: 8px;">
        <span>Personal / Operadores</span>
    </div>
    
    <div class="campo" style="padding: 10px; font-size: 12px;">
        
        <?php if (!empty($empleados)): ?>
            <?php foreach ($empleados as $index => $empleado): ?>
                <?php
                // Evaluamos la foto de cada uno
                $archivoFoto = !empty($empleado['foto']) ? $empleado['foto'] : 'default-avatar.png';
                $rutaFotoWeb = Url::to('@web/img/empleados-fotos/' . $archivoFoto);
                ?>

                <div style="display: flex; align-items: center; <?= $index > 0 ? 'margin-top: 8px;' : '' ?>">
                    <img src="<?= $rutaFotoWeb ?>"
                        class="imagen-avatar-grilla"
                        width="25"
                        height="25"
                        alt="Avatar"
                        style="object-fit: cover;"> 
                    <strong style="color: #ffffff;">
                        <?= htmlspecialchars(strtoupper($empleado['descripcion'] ?? 'Sin Nombre')) ?>
                    </strong>
                </div>
            <?php endforeach; ?>
            
        <?php else: ?>
            <div style="opacity: 0.6; text-align: center; padding: 5px 0;">
                <i class="fa fa-users-slash" style="color: rgba(245, 94, 24, 0.91); margin-right: 4px;"></i>
                Sin operadores asignados
            </div>
        <?php endif; ?>

    </div>
</div>
        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-5">

        </div>


    </div>
</div>