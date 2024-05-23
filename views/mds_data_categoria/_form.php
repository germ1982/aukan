<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_data_categoria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-data-categoria-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-12'>
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-12'>
            <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true, 'rows' => 4]) ?>
        </div>
        <div class='col-md-12'>
            <?php if ($model->icono == null) : ?>
                <?= $form->field($model, 'temp_icono', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'maxFileSize' => 10000000,
                            'previewFileType' => 'file',
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ],
                    ]);

                ?>
            <?php else : ?>
                <?= $form->field($model, 'temp_icono', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Url::to('@web/uploads/categoria/' . $model->idcategoria . '/icono/' . $model->icono, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                            ],
                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { console.log('fileclear'); $('#borrar_icono').val(true);}",
                            "filereset" => "function() {  }",
                        ]
                    ]);
                ?>
            <?php endif; ?>
            <?= $form->field($model, 'borrar_icono')->hiddenInput(['id' => 'borrar'])->label(false) ?>
        </div>
        <div class='col-md-12'>
            <?php if ($model->imagen_fondo == null) : ?>
                <?= $form->field($model, 'temp_imagen_fondo', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ],
                    ]);

                ?>
            <?php else : ?>
                <?= $form->field($model, 'temp_imagen_fondo', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                    ->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialPreview' => [
                                Url::to('@web/uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $model->imagen_fondo, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                            ],
                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { console.log('fileclear'); $('#borrar_imagen_fondo').val(true);}",
                            "filereset" => "function() {  }",
                        ]
                    ]);
                ?>
            <?php endif; ?>
            <?= $form->field($model, 'borrar_imagen_fondo')->hiddenInput(['id' => 'borrar'])->label(false) ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>