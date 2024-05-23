<?php

use app\models\Mds_org_contacto;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\LoginForm */
?> 

<div class="mds-hor-ingreso-externo-rechazar-externo">
    <?php $form = ActiveForm::begin(); ?>
    <span class=" ">¿Desea rechazar el ingreso?</span>
    <div class="row">
                <div class="col-md-12">
                    <?= $form
                        ->field($model, 'observaciones')
                        ->textarea(['rows' => 3]) ?>
                </div>
            </div>
    <?php ActiveForm::end(); ?>

</div>
