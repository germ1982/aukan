<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runneu-legajo-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'], // Esto permite cargar archivos
    ]); ?>

    <?= $form->field($model, 'num_legajo')->textInput() ?>

    <?= $form->field($model, 'dni')->textInput() ?>

    <?= $form->field($model, 'archivo_adjunto')->fileInput() ?>

    <?php if (!empty($model->archivo_adjunto) && file_exists(Yii::getAlias('@webroot/uploads/legajo_runneu/' . basename($model->archivo_adjunto)))): ?>
        <h3>Previsualización del archivo:</h3>
        <?php
        // Verificar si es una imagen
        if (in_array(pathinfo($model->archivo_adjunto, PATHINFO_EXTENSION), ['png', 'jpg', 'jpeg'])) {
            echo Html::img(Yii::getAlias('@webroot/uploads/legajo_runneu/' . basename($model->archivo_adjunto), ['width' => '300']));
        }
        // Verificar si es un PDF
        elseif (pathinfo($model->archivo_adjunto, PATHINFO_EXTENSION) === 'pdf') {
            echo '<iframe src="' . Yii::getAlias('@webroot/uploads/legajo_runneu/' . basename($model->archivo_adjunto) . '" width="100%" height="600px"></iframe>');
        }
        ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>