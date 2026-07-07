<?php

/* @var $this yii\web\View */


use johnitvn\ajaxcrud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\models\Empleado;
use app\models\Persona;

$this->title = 'Página Principal';

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->id : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$persona = $usuario->idpersona != null ? Persona::findOne($usuario->idpersona) : null;
$empleado = $usuario->idpersona != null ? Empleado::findOne($usuario->idpersona) : null;

// display error message
if (Yii::$app->session->hasFlash('error_modulo')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> No se puede ingresar al módulo</h4>
        <?= Yii::$app->session->getFlash('error_modulo') ?>
    </div>
<?php endif; ?>
<style>
    .content-body {
        padding: 5px 10px 20px 17px;
    }

    .banner {
        position: relative;
        height: 150px;
        background-color: black;
        text-align: center;

        justify-content: center;
        align-items: center;
        overflow: hidden;
        border: 2px solid #87b867;
        border-radius: 15px;
        margin: 5px;
        margin-top: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .banner video {
        position: absolute;
        top: 50%;
        left: 50%;
        width: auto;
        /* Ajusta el ancho del video */
        height: 100%;
        /* Ajusta la altura del video */
        transform: translate(-50%, -50%);
        object-fit: cover;
        /* Ajusta el video para cubrir todo el área del contenedor */
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class=" breadcrumbs">
            <li>
                <a href="index.php?r=site%2Findex">
                    <i class=" neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<style>
    .banner {
        background-image: url('img/logo_aukan2.png');
        background-size: cover;
        background-position: center;
        height: 80px;
        background-position-y: 498px;
        z-index: 1;
    }

    .banner video {
        height: 140%;
        left: 6%;
    }


    /* Importamos una tipografía futurista/tecnológica de Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');

    .contenido-banner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 4;
        /* Por encima de los efectos y el video */

        /* Flexbox para alinear el texto a la izquierda y centrado verticalmente */
        display: flex;
        align-items: center;
        justify-content: flex-start;
        /* padding-left: 5%; */
        padding: 0 56px;
        /* Margen para que no pegue al borde de la pantalla */
        box-sizing: border-box;
    }
    .contenido-banner video {
    height: 90%;
    object-fit: contain;
    border-radius: 10px;

        box-shadow: 0px 0px 6px rgb(67, 70, 252),
            0px 0px 10px rgba(108, 214, 245, 0.3);

}

    .texto-bienvenida {
        font-family: 'Orbitron', 'Arial Black', sans-serif;
        /* Tipografía técnica */
        font-size: 36px;
        /* Ajusta el tamaño a tu gusto */
        font-weight: 700;
        text-transform: UPPERCASE;
        /* Mantiene todo en minúsculas como me pediste */
        letter-spacing: 1px;
        /* Separación entre letras estilo logo */
        margin-top: 8px;
        /* EFECTO 1: Degradado celeste dentro de la letra */
        background: linear-gradient(to bottom, #dcf5ff 20%, #6cd6f5 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;

        /* EFECTO 2: Borde oscuro definido alrededor de las letras */
        -webkit-text-stroke: 1.5px #071624;

        /* EFECTO 3: Sutil brillo/sombra de fondo para dar profundidad */
        text-shadow:
            0px 2px 4px rgba(0, 0, 0, 0.3),
            0px 0px 10px rgba(108, 214, 245, 0.3);

        /* Pequeña animación opcional para que acompañe el mood del banner */
        animation: brilloTexto 3s ease-in-out infinite alternate;
        flex: 1;
    }
/* Alineás el primer texto a la izquierda y el segundo a la derecha */
.contenido-banner .texto-bienvenida:first-of-type {

    text-align: RIGHT;
}

.contenido-banner .texto-bienvenida:last-of-type {

    padding-left: 20px;
}
    /* Animación para que el resplandor de la letra "respire" */
    @keyframes brilloTexto {
        0% {
            filter: drop-shadow(0 0 2px rgba(0, 212, 255, 0.2));
        }

        100% {
            filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.6));
        }
    }
</style>
<div class="site-index">
    <div class="banner">


        <div class="capa-lineas"></div>
        <div class="capa-chispas"></div>

        <!-- NUEVO: Estructura del contenido -->
        <div class="contenido-banner">

         <video autoplay loop muted>
                <source src="img\aukan_home_banner.mp4" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
            <h1 class="texto-bienvenida">
                <div>BIENVENIDO A AUKAN CENTRO DE INFORMACION</div>
            </h1>


        </div>
    </div>

    <!-- Accesos -->

    <div class="row" style="padding: 5px 0px 0px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">
        <div class="col-md-3">
            <?php
            $titulo = "Web Informatica";
            $archivo_contenido_tarjeta = "web_informatica.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
            <br>
            <?php
            $titulo = "Registro Tecnico";
            $archivo_contenido_tarjeta = "indicadores_tecnicos.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
        </div>
        <div class="col-md-3">

            <?php
            $titulo = "Cumpleaños";
            $archivo_contenido_tarjeta = "cumpleaños.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
            <br>
            <?php
            $titulo = "Informacion Personal";
            $archivo_contenido_tarjeta = "sitio_en_construccion.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
        </div>
        <div class="col-md-3">
            <?php
            $titulo = "El Clima en Neuquen";
            $archivo_contenido_tarjeta = "eventos_del_dia.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
            <br>
            <?php
            $titulo = "Efemerides";
            $archivo_contenido_tarjeta = "sitio_en_construccion.php";
            include 'tarjetas/tarjeta_base.php'
            ?>
        </div>
        <div class="col-md-3">
            <?php
            include 'tarjetas/tarjeta_futbol.php'
            ?>
        </div>


    </div>
    <br>
    <div class="row" style="padding: 5px 0px 0px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">
        <div class="col-md-3">

        </div>

        <div class="col-md-3">

        </div>


        <div class="col-md-3">


        </div>


    </div>

    <br>

    <!-- Informes Pendientes -->

    <!--     <div class="row" id="abm_contacto" style="display:none;padding-top: 10px;">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-default">
                <header class="panel-heading">
                    <h3 class="panel-title">
                        Agregar Contacto
                    </h3>
                </header>
                <div class="panel-body">
                    <?php
                    /* echo $this->render('/empleado/_form', [
                        'model' => $empleado,
                        'botones' => true
                    ]); */
                    ?>
                </div>
            </section>
        </div>
    </div> -->
</div>
<?php
$this->registerJsFile('@web/js/indicadores_menu.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs(
    "$(\"#tipo_documento\").change(function() {
        var idtipo = $(\"#tipo_documento\").val();
        var idempleado = \"" . ($empleado != null ? $empleado->idempleado : 0) . "\";
        $.post(\"index.php?r=empleado/get_documentos&idempleado=\" + idempleado + \"&idtipo=\" + idtipo, function(data) {
            $(\"#tabla_docs\").html(data);
        });
    });
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        });
    
    var href=$('#generate-report').attr('href')+'&edificio='+$('#select-edificio').val();
    $('#generate-report').attr('href', href);
    $('#select-edificio').attr();
    $('#select-edificio').change(function(){
        var href=$('#generate-report').attr('href')+'&edificio='+$(this).val();
        $('#generate-report').attr('href', href);
    });"
);
?>