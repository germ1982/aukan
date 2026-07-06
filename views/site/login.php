<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    body {
        background-color: #121212;
        color: #00bfff;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    /* Estilo del contenedor del video de view_indicadores */
    .video-background-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 1;
        overflow: hidden;
       /*  filter: blur(5px); */
        transform: scale(1.05);
        pointer-events: none;
    }

    .video-background-container iframe {
        width: 100vw;
        height: 56.25vw;
        min-height: 100vh;
        min-width: 177.77vh;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) translateZ(0);
        will-change: transform;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }

    /* Capa superior para centrar el login box sin romper el layout */
    .overlay-div {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* Capa oscura sutil sobre el video */
        z-index: 2;
    }

    /* Caja de login adaptada a la estética de Aukán */
    .login-box {
       background: rgba(0, 20, 40, 0.4);
            /* Capa azulada traslúcida */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        border: 2px solid #00bfff;
        border-radius: 10px;
        padding: 40px;
        padding-top: 20px;
        box-shadow: 0 0 25px rgba(0, 191, 255, 0.6);
        width: 320px;
        backdrop-filter: blur(4px); /* Refuerza el efecto glassmorphism */
    }

    .logo {
        display: block;
        width: 225px;
        height: auto;
        margin: 0 auto 25px auto;
    }

    .form-group {
        margin-top: 20px;
        text-align: center;
    }

    .btn-primary {
        background-color: #00bfff;
        border-color: #00bfff;
        color: #121212;
        font-weight: bold;
        width: 100%; /* Botón completo más pro */
        padding: 10px;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #0080ff;
        border-color: #0080ff;
        color: #ffffff;
    }

    .form-control {
        background-color: #1e1e1e !important;
        border: 1px solid #00bfff !important;
        color: #00d2ff;
    text-shadow: 0 0 6px rgba(0, 210, 255, 0.6);

    }

    .form-control:focus {
        background-color: #1e1e1e !important;
        border-color: #0080ff !important;
        color: #00bfff !important;
        box-shadow: 0 0 8px rgba(0, 191, 255, 0.5);
    }
    .control-label {
            text-transform: uppercase!important;
    letter-spacing: 7px!important;
    font-size: 0.85rem!important;
    color: #a2c8ff!important;
    }
    .checkbox label {
        color: #00bfff;
    }
</style>

<div class="video-background-container">
    <iframe
        <?php
        $video_1 = "CQDtI7DrU7o"; // ID del primer video
        $video_2 = "z8JMxm9tXZQ"; // varios de la ciudad
        $video_3 = "8rOTk7-6j84"; // dron desde rio hata plaza de las banderas
        $video_4 = "dVKPZ_IvuBI"; //imagenes del interior
        $video_5 = "IrdQ369a2qw"; //dron de noche
        $video_6 = "iOcZFaB_M1I"; // rio neuquen
        $video_7 = "eWRDwD6cVe8"; //neuquen paraiso

        $inicio = 8;
        $final = 1224;
        $video = $video_7; // ID del segundo video
        ?>
        
        src="https://www.youtube.com/embed/<?= $video ?>?autoplay=1&mute=1&loop=1&playlist=<?= $video ?>&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&vq=hd2160&start=<?= $inicio ?>&end=<?= $final ?>"
        
        frameborder="0"
        allow="autoplay; encrypted-media"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
</div>

<div class="overlay-div">
    <div class="login-box">
        <img src="img/logo_aukan.png" alt="Logo" class="logo">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Usuario') ?>
        <?= $form->field($model, 'password')->passwordInput()->label('Contraseña') ?>
        <?= $form->field($model, 'rememberMe')->checkbox()->label('Mantener sesión iniciada') ?>

        <div class="form-group">
            <?= Html::submitButton('Iniciar Sesión', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>