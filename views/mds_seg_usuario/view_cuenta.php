<?php

use app\models\Sds_com_persona;
use app\models\Mds_rum_persona;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_rol;
use app\models\Mds_seg_usuario_entrega_tipo;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_tipo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario */
/* @var $form yii\widgets\ActiveForm */

$una_rum_persona= Mds_rum_persona::find()
                ->where(['id' => $id_rum_persona])
                ->one();
$un_com_persona = Sds_com_persona::find()
                    ->where(['idpersona' => $una_rum_persona->id_com_persona])
                    ->one();
?>
<div class="row">
        <div class="col-md-5">
            <strong>Nombres y apellidos: </strong> <?php echo $un_com_persona->nombre.' '.$un_com_persona->apellido; ?>        
        </div>
        <div class="col-md-3">
            <strong>DNI: </strong> <?= $un_com_persona->documento ?>        
        </div>
        <div class="col-md-4">
            <?php
                if ($model->activo == 1)
                {
                    echo '<strong>Estado de la Cuenta: </strong> Cuenta Activa';
                }
                else
                {
                    if ($model->activo == 0)
                    {
                        echo '<strong>Estado de la Cuenta: </strong> Cuenta No Activa';
                    }
                }
            ?>
                
        </div>
</div><br>
<div class="mds-seg-usuario-form">
    
    <?php $form = ActiveForm::begin(); ?>
   
    <div class="row">
        <div class="col-md-5">       
            <?= $form->field($model, 'user')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre de Usuario') ?>
        </div>
        <div class="col-md-4">              
                <?= $form->field($model, 'pass')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Contraseña') ?>                            
        </div>          
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'mail')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Email') ?>
        </div>    
         
    </div>
    
    
    <?php /* $form->field($model, 'imagen')->textarea(['rows' => 6])  */ ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Index_cuenta', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

