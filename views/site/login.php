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
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }

    .login-box {
        background: rgba(0, 0, 0, 0.8);
        border: 2px solid #00bfff;
        border-radius: 10px;
        padding: 40px;
        padding-top: 10px;
        box-shadow: 0 0 20px #00bfff;
        width: 300px;
    }

    .login-box h1 {
        margin-bottom: 20px;
        color: #00bfff;
    }

    .form-group {
        margin-top: 20px;
        text-align: center;
    }

    .btn-primary {
        background-color: #00bfff;
        border-color: #00bfff;
        color: #121212;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #0080ff;
        border-color: #0080ff;
    }

    .form-control {
        background-color: #1e1e1e!important;
        border: 1px solid #00bfff!important;
        color: #00bfff;
    }

    .form-control:focus {
        background-color: #1e1e1e!important;
        border-color: #0080ff!important;
        color: #00bfff;
    }

    .checkbox label {
        color: #00bfff;
    }

    video {
        height: 100%;
        width: 100%;
    }

    .background-div {
        position: fixed;
        /* O puede ser fixed si quieres que no se mueva al hacer scroll */
        top: 0;
        left: 0;

        background-color: black;
        /* O cualquier otro contenido o estilo */
        z-index: 1;
        /* Nivel más bajo */
    }

    .overlay-div {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        /* O puede ser fixed si quieres que no se mueva al hacer scroll */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* O cualquier otro contenido o estilo */
        z-index: 2;
        /* Nivel más alto */
    }

    .logo {
        display: block;

        width: 225px;
        /* Ajusta el ancho según sea necesario */
        height: auto;
        /* Esto mantiene la proporción de la imagen */
    }
</style>
<div class="background-div">
    <video autoplay loop src="https://video.wixstatic.com/video/d47472_58cce06729c54ccb935886c4b3647274/1080p/mp4/file.mp4" autoplay muted></video>
</div>


<div class="overlay-div">

    <div class="login-box">

        <img src="img/logo datafam.png" alt="Logo" class="logo">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,])->label('Usuario') ?>
        <?= $form->field($model, 'password')->passwordInput()->label('Contraseña') ?>
        <?= $form->field($model, 'rememberMe')->checkbox()->label('Mantener sesión iniciada') ?>

        <div class="form-group">
            <?= Html::submitButton('Iniciar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>





</div>