<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runneu-legajo-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'], // Esto permite cargar archivos
    ]); ?>

    <?= $form->field($model, 'num_legajo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>

    <!-- Aquí se gestiona la carga de archivo -->
    <div class="col-md-4">
        <?= $form->field($model, 'archivo_adjunto')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*, .pdf, .docx'], // Se pueden cargar imágenes y archivos PDF, DOCX
            'pluginOptions' => [
                'initialPreview' => $model->archivo_adjunto ? [Yii::$app->request->baseUrl . '/uploads/legajo_runneu/' . $model->archivo_adjunto] : [],
                'initialPreviewAsData' => true,
                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx'],
                'showPreview' => true,
                'showCaption' => false,
                'showRemove' => true,
                'showUpload' => false,
                'maxFileSize' => 2000, // Limitar el tamaño del archivo a 2 MB
            ],
        ]); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>