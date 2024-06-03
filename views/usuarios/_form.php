<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use yii\helpers\Url;
use kartik\widgets\FileInput;

?>

<div class="usuarios-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'activo')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php
                if ($model->avatar == null) {
                    echo $form->field($model, 'avatar', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            //'name' => 'i1',
                            'options' => ['accept' => 'image/*'],
                            'language' => 'es',
                            'pluginOptions' => [
                                //'showPreview' => false,
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showClose' => false,
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_rum_novedad/update']),
                                'maxFileSize' => 1000,
                                /* 'initialPreview'=>[
                                                    Html::img($model->Foto,['class'=>'file-preview-image']),
                                                    ], */
                                'previewFileType' => 'image',
                                'initialCaption' => $model->avatar,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                                //'minFileCount' => 1,
                                // 'validateInitialCount' => true,
                            ],
                        ])->label('IMAGEN PRINCIPAL');
                } else {
                    echo $form->field($model, 'avatar', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*'],
                            'language' => 'es',
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                'showCaption' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showClose' => false,
                                'showCancel' => false,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/mds_rum_novedad']),
                                'maxFileSize' => 1000,
                                'previewFileType' => 'image',
                                'initialPreview' => [
                                    //Html::img($model->avatar, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                    Html::img(Url::base() . "/uploads/novedades/" . $model->avatar, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                    //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->avatar);
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->avatar,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                "filereset" => "function() {  }",
                            ]
                        ])->label('IMAGEN PRINCIPAL');
                }
            ?>
        </div>
    </div>
</div>
    







<?php ActiveForm::end(); ?>

</div>