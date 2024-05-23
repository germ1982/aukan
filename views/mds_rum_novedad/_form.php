<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_novedad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-novedad-form">

    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">                    
                <?= $form->field($model, 'titulo')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">                    
                <?= $form->field($model, 'contenido')->textarea(['rows' => 6]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">    
                <?= $form->field($model, 'activo')->checkBox(['selected' => $model->activo])?> 
            </div>
            <div class="col-md-4">    
                <?= $form->field($model, 'publicado')->checkBox(['selected' => $model->publicado])?> 
            </div>
        </div>
        <div class="row">

        <div class='col-md-6' align="center";>
                        <?php
                        if ($model->imagen == null) {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_rum_novedad/update']),
                                        'maxFileSize' => 1000,
                                        /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                        'previewFileType' => 'image',
                                        'initialCaption' => $model->imagen,
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
                                ])->label('IMAGEN PRINCIPAL');
                        } else {
                            echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                ->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'language' => 'es',
                                    'pluginOptions' => [
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                        'showCaption' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showClose' => false,
                                        'showCancel'=> false,
                                        'mainClass' => 'input-group-sm',
                                        'uploadUrl' => Url::to(['/mds_rum_novedad']),
                                        'maxFileSize' => 1000,
                                        'previewFileType' => 'image',
                                        'initialPreview' => [
                                            //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                            Html::img(Url::base()."/uploads/novedades/".$model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                           //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                        ],
                                        'overwriteInitial' => true,
                                        'autoReplace' => true,
                                        'initialCaption' => $model->imagen,
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
                                ])->label('IMAGEN PRINCIPAL');
                        }
                        ?>
            </div>
           
        </div>
        
        
    </div>


    <?php //echo  $form->field($model, 'comment_status')->textInput(['maxlength' => true]); ?>

    <?php  // echo $form->field($model, 'comment_count')->textInput(); ?>
  
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
