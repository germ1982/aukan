<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

if (isset($model->archivo_adjunto)) {
    $imagePath = Url::to('uploads_datafam/legajo_runneu/' . $model->archivo_adjunto);

    // Agrega la imagen a la vista previa inicial
    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Imagen', 'title' => $model->archivo_adjunto, 'width' => '100%', 'height' => 'auto']),
    ];
}
?>

<style>
    .file-drop-zone {
        min-height: 200px !important; /* Aumenta la altura del área de carga */
        height: 350 !important; 
    }

    .file-preview-object {
    height: 300px !important; /* Ajusta la altura según necesites */
}
.file-preview-frame object {
    height: 300px !important;
}
    .file-preview-image {
        height: 100% !important; 
        min-height: 100px !important;
        /* max-width: 100% !important; */
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .file-preview-thumbnails{
        height: 320 !important; 
    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
        height: 100% !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
        height: 250px !important;

    }


</style>

<div class="runneu-legajo-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'], // Esto permite cargar archivos
    ]); ?>
    <div class="row">
        <div class=" col-md-5">
            <div class="row">
                <div class=" col-md-12">
                    <?= $form->field($model, 'num_legajo')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-12">
                    <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

        </div>
        <div class=" col-md-7">
            <?= $form->field($model, 'archivo_adjunto_file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*, .pdf, .docx'],
                'pluginOptions' => [
                    'initialPreview' => $model->archivo_adjunto ? [Yii::$app->request->baseUrl . '/uploads_datafam/legajo_runneu/' . $model->archivo_adjunto] : [],
                    'initialPreviewAsData' => true,
                    'initialPreviewFileType' => 'any', // Permite mostrar distintos tipos de archivos
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx'],
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreviewConfig' => $model->archivo_adjunto ? [[
                        'type' => pathinfo($model->archivo_adjunto, PATHINFO_EXTENSION) === 'pdf' ? 'pdf' : 'image',
                        'caption' => $model->archivo_adjunto,
                        'downloadUrl' => Yii::$app->request->baseUrl . '/uploads_datafam/legajo_runneu/' . $model->archivo_adjunto
                    ]] : [],
                ],
            ]); ?>

        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>