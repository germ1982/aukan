<?php

use yii\helpers\Html;
use app\models\Edificio;
use app\models\Configuracion;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioConectividad */

// Helper para líneas de datos compactas en Modo Claro Industrial
function campoCompactoClaro($titulo, $contenido)
{
    $tituloUpper = strtoupper($titulo);
    return "<div class='tech-linea-horizontal'>
            <span class='tech-label-horizontal'>$tituloUpper:</span>
            <span class='tech-value'>$contenido</span>
          </div>";
}

// Helper para indicadores de estado tipo panel de control claro
function campoEstadoTech($titulo, $activo, $textoActivo = 'ACTIVO', $textoInactivo = 'INACTIVO')
{
    $tituloUpper = strtoupper($titulo);
    $estadoClase = $activo ? 'ok' : 'critico';
    $simbolo = $activo ? 'fa-check-square' : 'fa-minus-square';
    $texto = $activo ? $textoActivo : $textoInactivo;

    return "<div class='tech-linea-horizontal tech-status-line'>
            <span class='tech-label-horizontal'>$tituloUpper:</span>
            <div class='tech-value tech-led-status $estadoClase'>
                <span>$texto <i class='fa $simbolo'></i></span>
                <div class='tech-led-glow'></div>
            </div>
          </div>";
}

// Extracción de datos del Edificio asociado a la conectividad
$edificio = Edificio::findOne($model->idedificio);
$nombreEdificio = '-';
$detallesDireccion = 'Sin dirección registrada';
$geolocalizacion = '';

if ($edificio !== null) {
    $fija = !empty($edificio->descripcion_fija) ? $edificio->descripcion_fija : '';
    $gestion = !empty($edificio->descripcion_gestion) ? $edificio->descripcion_gestion : '';
    $nombreEdificio = trim(($fija && $gestion) ? "$fija - $gestion" : "$fija$gestion");

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

// Resolvemos descripciones de las configuraciones vinculadas
$estadoEnlace = Configuracion::findOne($model->estado);
$infraestructura = Configuracion::findOne($model->infraestructura);
$servicio = Configuracion::findOne($model->servicio);
$tipoConexion = Configuracion::findOne($model->tipo_conexion);
?>

<style>
    /* CONTENEDOR GENERAL LIGHT TECH / LABORATORIO */
    .tech-view {
        background-color: #f4f7f6; /* Fondo gris claro de instrumental médico/técnico */
        color: #1a252c; /* Tipografía oscura de alta legibilidad */
        padding: 12px 18px;
        border-radius: 0px; /* Bordes rectos industriales */
        font-family: 'Courier New', Courier, monospace;
        border: 1px solid #b4c6cc;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.03);
        position: relative;
        /* margin-bottom: 10px; */
    }

    /* Detalles decorativos de las esquinas estilo HUD de laboratorio */
    .tech-view::before {
        content: "[ SYS_DATAFAM ]";
        position: absolute;
        top: 4px;
        right: 12px;
        color: rgba(26, 37, 44, 0.4);
        font-size: 9px;
        letter-spacing: 1px;
    }

    /* FILA DE TEXTO EN UNA SOLA LÍNEA */
    .tech-linea-horizontal {
        display: flex;
        justify-content: flex-start;
        align-items: center;

        border-bottom: 1px dashed rgba(0, 0, 0, 0.15);
    }

    .tech-linea-horizontal:last-child {
        border-bottom: none;
    }

    /* ETIQUETAS (IZQUIERDA) */
    .tech-label-horizontal {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #4a6984; /* Azul acero corporativo/técnico */
        min-width: 140px;
        display: inline-block;
    }

    /* VALORES (DERECHA) */
    .tech-value {
        font-size: 13px;
        color: #111111;
        font-weight: bold;
        padding-left: 8px;
    }

    /* CONTENEDOR DE CAMPOS GRANDES (OBSERVACIONES) */
    .tech-field-wrapper {
        margin-bottom: 12px;
    }

    .tech-title {
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #2c3e50;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        margin-bottom: 6px;
    }

    .tech-title::after {
        content: "";
        flex-grow: 1;
        margin-left: 10px;
        height: 1px;
        background: linear-gradient(to right, rgba(74, 105, 132, 0.4), transparent);
    }

    .tech-campo-bloque {
        padding: 8px 12px;
        font-size: 12px;
        color: #222222;
        background-color: #ffffff;
        border: 1px solid #b4c6cc;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        min-height: 60px;
    }

    /* MAPA CON FILTRO INDUSTRIAL LIGHT */
    .contenedor-mapa-tech {
        border: 1px solid #b4c6cc;
        background-color: #ffffff;
        width: 100%;
        height: 280px;
        overflow: hidden;
        position: relative;
    }

    .contenedor-mapa-tech iframe {
        width: 100%;
        height: 100%;
        border: none;
        /* FILTRO MODO CLARO HIGH-CONTRAST TECNOLÓGICO: 
           Saca el ruido visual verde/amarillo de los mapas comunes y los tira a escala de azules/grises
        */
        filter: sepia(10%) saturate(70%) hue-rotate(180deg) contrast(110%);
        opacity: 0.9;
    }

    .tech-no-geo {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #7f8c8d;
        text-transform: uppercase;
        background-color: #eaedd0; /* Alerta sutil en amarillo seco industrial */
        border: 1px dashed #d35400;
        padding: 15px;
    }

    /* SISTEMA DE LEDS MODERNOS SOBRE FONDO CLARO */
    .tech-led-status {
        font-size: 12px;
        position: relative;
        padding: 2px 8px;
        border-radius: 2px;
        font-weight: bold;
    }

    /* Estado OK (Enlace arriba, activo, etc) -> Verde Instrumental */
    .tech-led-status.ok i {
        color: #27ae60;
        text-shadow: 0 0 4px rgba(39, 174, 96, 0.4);
    }
    .tech-led-status.ok {
        background-color: rgba(46, 204, 113, 0.15);
        color: #1e7e34;
    }

    /* Estado CRÍTICO / CAÍDO -> Naranja/Rojo Industrial */
    .tech-led-status.critico i {
        color: #e67e22;
        text-shadow: 0 0 4px rgba(230, 126, 34, 0.4);
    }
    .tech-led-status.critico {
        background-color: rgba(230, 126, 34, 0.15);
        color: #d35400;
    }

    /* Separador decorativo de matriz de puntos */
    .tech-grid-deco {
        font-size: 8px;
        color: rgba(74, 105, 132, 0.2);
        margin: 4px 0;
        letter-spacing: 3px;
        text-align: center;
    }
</style>

<div class="tech-view">
    <div class="row">
        <div class="col-md-3">
            <?= campoCompactoClaro('Infraestructura', $infraestructura ? $infraestructura->descripcion : '-') ?>
        </div>
        <div class="col-md-3">
            <?= campoCompactoClaro('Servicio', $servicio ? $servicio->descripcion : '-') ?>
        </div>
        <div class="col-md-4">
            <?= campoEstadoTech('Estado', ($estadoEnlace && strpos(strtoupper($estadoEnlace->descripcion), 'MINISTERIO') === false), $estadoEnlace ? $estadoEnlace->descripcion : 'DESCONOCIDO', 'CAÍDO / PROBLEMAS') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= campoCompactoClaro('Tipo Conexión', $tipoConexion ? $tipoConexion->descripcion : '-') ?>
        </div>
        <div class="col-md-3">
            <?= campoCompactoClaro('Ancho de Banda', $model->velocidad_en_mb ? $model->velocidad_en_mb . " Mbps" : '-') ?>
        </div>
        
        
    </div>
</div>

<div class="tech-view" style="padding: 2px; background-color: #e0e6e8;">
    <div class="tech-grid-deco">▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞ SYSTEMA_DATAFAM_CONNECTADO ▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞</div>
</div>

<div class="tech-view">
    <div class="row">
        <div class="col-md-6">
            <div class="tech-field-wrapper">
                <div class="tech-title">Ubicación de la Terminal</div>
                <div style="margin-bottom: 10px; font-size: 12px; line-height: 1.6;">
                    <strong>EDIFICIO:</strong> <span style="color: #4a6984;"><?= strtoupper($nombreEdificio) ?></span><br>
                    <strong>DIRECCIÓN:</strong> <?= $detallesDireccion ?><br>
                    
                </div>
            </div>

            <div class="tech-field-wrapper">
                <div class="tech-title">Notas Técnicas / Observaciones</div>
                <div class="tech-campo-bloque">
                    <?= !empty($model->observacion) ? nl2br(htmlspecialchars($model->observacion)) : '<em>Sin novedades registradas en este nodo.</em>' ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="tech-field-wrapper">
                <div class="tech-title">Terminal GPS / Geoposicionamiento</div>
                <div class="contenedor-mapa-tech">
                    <?php if (!empty($geolocalizacion)): ?>
                        <iframe
                            src="https://maps.google.com/maps?q=<?= $geolocalizacion ?>&z=15&t=m&output=embed"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    <?php else: ?>
                        <div class="tech-no-geo">
                            <i class="fa fa-map-signs" style="font-size: 20px; display:block; margin-bottom: 5px;"></i>
                            ERROR: Coordenadas del Edificio ausentes en la base de datos de la Subsecretaría.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>