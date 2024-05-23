<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_seg_usuario;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_observacion */
?>

<div class="mds-rum-observacion-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-5">
           <b>Fecha/hora:</b> <?= $model->fecha.' '.$model->hora?>            
        </div>
        <div class="col-md-5">
            <?php
                $un_seg_usuario=Mds_seg_usuario::findOne($model->id_persona);
                $cad=$un_seg_usuario->nombre.' '.$un_seg_usuario->apellido;               
            ?>
            <b>Autor:</b> <?= $cad?> 
        </div>
        <br>
    </div>
    <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observacion')->textarea(['rows' => 6])->label("<b>Observación:</b>") ?>  
            </div>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>

