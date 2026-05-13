<?php

use app\controllers\SiteController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Organismo;
use app\models\OrganismoDecreto;

$organismo = Organismo::findOne($model->abreviatura);
$subtitulo = '$model->origen_alta: ' . $model->origen_alta;
if ($model->origen_alta != 0) {
    $decreto = OrganismoDecreto::findOne($model->iddecreto)->descripcion;
    $rama = "Raiz";
    if ($model->origen_alta == 2) {
        $rama = Organismo::findOne($model->padre)->descripcion;
    }
    $subtitulo = "Decreto: $decreto - Padre: $rama";
}
$this->registerJs("aplicarCorrector('input_descripcion');");


/* @var $this yii\web\View */
/* @var $model app\models\Organismo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organismo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <h4><?= $subtitulo ?></h4>
        </div>
    </div>
    <div class="row">
        <div class=" col-md-12">
            <div class="row">
                <div class=" col-md-6">
                    <?= $form->field($model, 'descripcion')->textInput(['id' => 'input_descripcion', 'maxlength' => true, 'spellcheck' => 'true', 'lang' => 'es','autocomplete' => 'on'])->label("algo") ?>
                </div>
                <div class="col-md-6">
                    <?php if ($model->origen_alta == 0): ?>
                        <?= SiteController::actionGet_input_select2($form, $model, 'padre', 'cmb_padre', Organismo::get_organismos(), 'idorganismo', 'descripcion', 'Padre') ?>
                    <?php else: ?>


                        <label class="control-label"><?= $model->getAttributeLabel('padre') ?></label>
                        <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                            <?= $model->padre ? Organismo::findOne($model->padre)->descripcion : 'Raíz' ?>
                        </p>

                        <?= $form->field($model, 'padre')->hiddenInput()->label(false) ?>

                    <?php endif; ?>
                </div>

            </div>
        </div>

        <div class=" col-md-12">
            <div class="row">

                <div class=" col-md-4">
                    <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-2" style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
                </div>
                <div class=" col-md-2">
                    
                    <?php if ($model->origen_alta != 1): ?>
                        <?= $form->field($model, 'nivel')->textInput() ?>
                    <?php else: ?>


                        <label class="control-label"><?= $model->getAttributeLabel('nivel') ?></label>
                        <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                            <?= $model->nivel ?>
                        </p>

                        <?= $form->field($model, 'nivel')->hiddenInput()->label(false) ?>

                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <div class="reminder-card">
                        <div class="reminder-header">
                            <h4>Recordatorio de Nivel</h4>
                        </div>
                        <div class="reminder-content">
                            <ol>
                                <li> Ministerios</li>
                                <li> Subsecretarias</li>
                                <li> Coordinaciones</li>
                                <li> Direcciones Provinciales</li>
                                <li> Direcciones Generales</li>
                                <li> Direcciones</li>
                                <li> Departamentos / Otros</li>
                                <!-- Agrega más elementos aquí -->
                            </ol>
                        </div>
                    </div>
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

