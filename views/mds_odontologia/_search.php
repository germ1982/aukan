<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_odontologiaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-odontologia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idodontologia') ?>

    <?= $form->field($model, 'idpersona') ?>

    <?= $form->field($model, 'cant_dientes') ?>

    <?= $form->field($model, 'cant_caries') ?>

    <?= $form->field($model, 'cant_obturados') ?>

    <?php // echo $form->field($model, 'cant_perdidos') 
    ?>

    <?php // echo $form->field($model, 'observaciones') 
    ?>

    <?php // echo $form->field($model, 'iddispositivo') 
    ?>

    <?php // echo $form->field($model, 'idescolaridad') 
    ?>

    <?php // echo $form->field($model, 'idtipointervencion') 
    ?>

    <?php // echo $form->field($model, 'idtipovisita') 
    ?>

    <?php // echo $form->field($model, 'enfermedad_periodontal') 
    ?>

    <?php // echo $form->field($model, 'enfermedad_base') 
    ?>

    <?php // echo $form->field($model, 'created_at') 
    ?>

    <?php // echo $form->field($model, 'updated_at') 
    ?>

    <?php // echo $form->field($model, 'deleted_at') 
    ?>

    <?php // echo $form->field($model, 'idusuario_carga') 
    ?>

    <?php // echo $form->field($model, 'idusuario_modifica') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>