<?php

use kartik\file\FileInput;
?>

<style>
    .file-drop-zone {
        min-height: 100px !important;
    }

    .file-preview-image {
        min-height: 100px !important;
        max-width: 100% !important;
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'imageFile')->widget(FileInput::className(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'initialPreview' => $initialPreview,
                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                'showPreview' => true,
                'showCaption' => false,
                'showRemove' => true,
                'showUpload' => false,
                'showClose' => false,
                'showCancel' => false,
                'mainClass' => 'input-group-sm',
                'maxFileSize' => 100000,
                'fileActionSettings' => [
                    'showRemove' => false,
                    'showUpload' => false,
                    'showZoom' => false,
                    'showCaption' => false,
                    'showCancel' => false
                ],
                'previewFileType' => 'image',
                'layoutTemplates' => [
                    'footer' => '',  // Remueve el footer en la vista previa si es necesario
                ],
                'initialPreviewConfig' => [
                    ['width' => '50px'] // Define el ancho de la vista previa
                ]
            ]
        ]); ?>
    </div>

</div>