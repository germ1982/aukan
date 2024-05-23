<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;

?>

<div class="mds-hor-registro-import-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class='col-md-12'>
            <?php
            echo $form->field(
                $model,
                'archivo_txt',
                [
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false
                ]
            )
                ->widget(FileInput::classname(), [
                    'options' => ['accept' => 'text'],
                    'language' => 'es',
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['txt','dat'],
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'mainClass' => 'input-group-sm',
                        'maxFileSize' => 2000
                    ]
                ])->label(false);
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>