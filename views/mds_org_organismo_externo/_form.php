<?php

use app\models\Mds_org_organismo_externo;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_organismo_externo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-org-organismo-externo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'link_externo')->textInput(['maxlength' => true])->label("Link") ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'informacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div>
                <?php if ($model->logo == null) : ?>
                    <?= $form->field($model, 'temp_logo', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                    <?= $form->field($model, 'temp_logo', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                    Url::to('@web/uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $model->logo, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                ],
                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                'initialPreviewFileType' => Mds_org_organismo_externo::getExtension($model->logo), // image is the default and can be overridden in config below
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                ]
                            ],
                            'pluginEvents' => [
                                "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                "filereset" => "function() {  }",
                            ]
                        ]);
                    ?>
                <?php endif; ?>
                <?= $form->field($model, 'borrar_logo')->hiddenInput(['id' => 'borrar'])->label(false) ?>
            </div>
        </div>
    </div>
</div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>

</div>