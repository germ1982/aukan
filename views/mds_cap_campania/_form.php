<?php

use app\models\Mds_cap_campania;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_campania */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-cap-campania-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true])->label("Nombre") ?>

            <?= $form->field($model, 'limite_inscripciones')->textInput() ?>

            <?= $form->field($model, 'estado')->dropDownList(
                [
                    Mds_cap_campania::ESTADO_ACTIVA => "Activa",
                    Mds_cap_campania::ESTADO_NO_ACTIVA => "No Activa",
                ],
                ['prompt' => '-- Seleccione una opción --']
            ) ?>
        </div>

        <div class="col-md-6">
            <div>
                <?php if ($model->logo_path == null) : ?>
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
                                    Url::to('@web/uploads/campanias/' . $model->idcampania . '/archivo/' . $model->logo_path, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                ],
                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                'initialPreviewFileType' => Mds_cap_campania::getExtension($model->logo_path), // image is the default and can be overridden in config below
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
    <div clas row>
        <?= $form->field($model, 'informacion')->textarea(['rows' => 6]) ?>
    </div>
</div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>