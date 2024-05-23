<?php

use app\models\Mds_cap_campania;
use kartik\form\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_campania */
?>

<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 40%;
    }
</style>

<div class="mds-cap-campania-view">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, "disabled" => "true"])->label("Nombre") ?>

            <?= $form->field($model, 'limite_inscripciones')->textInput() ?>

            <?= $form->field($model, 'estado')->dropDownList(
                [
                    Mds_cap_campania::ESTADO_ACTIVA => "Activa",
                    Mds_cap_campania::ESTADO_NO_ACTIVA => "No Activa",
                ],
                ['prompt' => '-- Seleccione una opción --',
                 "disabled" => "true"]
            ) ?>
        </div>

        <div class="col-md-6">
            <div style="display:<?= $model->logo_path ? "block" : "none" ?>">

                <h5><b>Imagen</b></h5>

                <div id="salud_content" style="text-align: center">
                    <div class="row" style="margin-top:2%">
                        <img id='base64image' src="<?= 'uploads/campanias/' . $model->idcampania . '/archivo/' . $model->logo_path ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div clas row>
        <?= $form->field($model, 'informacion')->textarea(['rows' => 6, "disabled" => "true"]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>