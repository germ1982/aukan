<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_monto */

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-atp-alta-create">
    <div class="mds-atp-alta-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-12 required" required id="idsucursal-required">
                <?= $form->field($model, 'monto')->textInput(['maxlength'=>15, 'placeholder' => '$'])->hint('No ingresar puntos e incluir dos decimales obligatorios. Ejemplos: 4500,00 - 2000,50')->label('Monto'); ?>
            </div>
            <br>
            <div class="col-md-12">
                <p><b>Cantidad total de solicitudes a procesar: <?php echo $count_montos ?></b></p>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
