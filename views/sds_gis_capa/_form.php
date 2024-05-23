<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-gis-capa-form">

	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-10">
			<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-2" style="padding-top: 35px;">
			<?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
		</div>
	</div>
	<div class="row justify-content-center vh-100">
	<div class='col-md-12 ' >
                        <?php
                        if ($model->capa_icono == null) {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    //'name' => 'i1',
                                    'options' => ['accept' => 'image/*'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        //'showPreview' => false,
                                        'allowedFileExtensions' => [ 'png'],                                        
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/sds_gis_capa/update']),
                                        'maxFileSize' => 1000,
                                        /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                        'previewFileType' => 'image',
                                        'initialCaption' => $model->capa_icono,
                                        'fileActionSettings' => [
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel'=> false
                                        ]
                                        //'minFileCount' => 1,
                                        // 'validateInitialCount' => true,
                                    ],
                                ])->label('Capa Icono');
                        } else {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => [ 'png'], 
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/sds_gis_capa']),
                                        'maxFileSize' => 1000,
                                        'previewFileType' => 'image',                                        
                                        'initialPreview' => [
                                            Html::img($model->capa_icono, ['class' => 'file-preview-image', 'style' => 'width:100%']),                                           
                                        ],
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        
                                        'initialCaption' => $model->capa_icono,
                                        'fileActionSettings' => [
                                            'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel'=> false
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                        "filereset" => "function() {  }",
                                    ]
                                ])->label('Capa Icono');
                        }
                        ?>
            </div>
           
        </div><br>
	</div>
	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>