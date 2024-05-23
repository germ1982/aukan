<?php

use app\models\Mds_org_organismo_externo;
use app\models\Mds_org_organismo_externoSearch;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_organismo_externo */
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

<div class="mds-org-organismo-externo-view">


    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, "disabled" => "true"]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'link_externo')->textInput(['maxlength' => true, "disabled" => "true"])->label("Link") ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'informacion')->textarea(['rows' => 6, "disabled" => "true"]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="display:<?= $model->logo ? "block" : "none" ?>">
                <h5><b>Imagen</b></h5>
                <div id="salud_content" style="text-align: center">
                    <div class="row" style="margin-top:2%">
                        <img id='base64image' src="<?= 'uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $model->logo ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>