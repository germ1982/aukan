<?php

use app\models\Mds_org_novedad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_novedad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-org-novedad-form">

    <p>
        <b>Fecha Novedad: </b>
        <?php echo date_format(date_create($model->fechahora), 'd/m/Y H:i') ?>
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado')->dropDownList([
        Mds_org_novedad::PUBLICADO => "Publicado",
        Mds_org_novedad::NO_PUBLICADO => "No Publicado"
    ], ['prompt' => '-- Seleccione una opción --']) ?>


    <?= $form->field($model, 'tipo')->dropdownList(
        ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_NOVEDAD, false),
            'idconfiguracion',
            'descripcion'
        ),
        [
            'prompt' => '-- Seleccione una opción --'
        ]
    );
    ?>

    <?php if ($model->imagen == null) : ?>
        <?= $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
            ->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                'language' => 'es',
                'pluginOptions' => [
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'showClose' => false,
                    'mainClass' => 'input-group-sm',
                    'uploadUrl' => Url::to(['/mds_com_novedad/update']),
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
        <?= $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
            ->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                'language' => 'es',
                'pluginOptions' => [
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'showClose' => false,
                    'mainClass' => 'input-group-sm',
                    'uploadUrl' => Url::to(['/mds_org_informe/update']),
                    'maxFileSize' => 1000000000,
                    'previewFileType' => 'file',
                    'initialPreview' => [
                        Url::to('@web/uploads/novedades/' . $model->imagen, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                    ],
                    'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                    'initialPreviewFileType' => Mds_org_novedad::getExtension($model->imagen), // image is the default and can be overridden in config below
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

    <?= $form->field($model, 'borrar_adjunto')->hiddenInput(['id' => 'borrar'])->label(false) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>