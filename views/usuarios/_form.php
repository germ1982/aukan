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
        <div class="col-md-8">
        <div class="row" style='padding-left:10px; '>
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form
                            ->field($model, 'documento')
                            ->textInput([
                                'id' => 'input_dni_persona',
                                //'onkeyup' => 'ValidarIngresoDni(0);',
                                //'disabled' => $generada
                            ])
                            ->label(
                                $model->isNewRecord
                                    ? 'Buscar Destinatario por Dni'
                                    : 'DNI Destinatario'
                            ) ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni',
                                'Buscar Dni',
                                'datos_persona(0);'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-8" style="padding-top:30px;" id="txt_mensaje"></div>
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
                    <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php
                if ($model->avatar == null) 
                    {
                        echo $form->field($model, 'avatar', ['enableClientValidation' => true, 
                                                                'enableAjaxValidation' => false])
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
                    } 
                else 
                    {
                        $archivo = "/img/usuarios-avatares/$model->avatar.jpg";
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
                                        Html::img(Url::base() .$archivo, ['class' => 'file-preview-image', 'style' => 'width:100%']),
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