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
        <div class="col-md-4">
            <strong>DNI: </strong> <?= $un_com_persona->documento ?>        
        </div>
</div><br>
<div class="mds-seg-usuario-form">
    
    <?php $form = ActiveForm::begin(); ?>
   
    <div class="row">
        <div class="col-md-5">       
            <?= $form->field($model, 'user')->textInput(['maxlength' => true])->label('Nombre de Usuario') ?>
        </div>
        <div class="col-md-4">              
                <?= $form->field($model, 'pass')->textInput(['maxlength' => true])->label('Contraseña') ?>                            
        </div>
        <div class="col-md-3">
        <?= $form->field($model, 'activo')->dropDownList(
            [
                '0' => "Cuenta no Activa",
                '1' => "Cuenta Activa",
            ],)
            ->label('Estado de la Cuenta') ?>
        </div>   
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'mail')->textInput(['maxlength' => true])->label('Email') ?>
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


<script>
    $("#cmb_contacto").change(function() {
        var idcontacto = $("#cmb_contacto").val();
        $.post("index.php?r=mds_org_contacto/get_contacto&id=" + idcontacto, function(data) {
            data = JSON.parse(data);
            $("#mds_seg_usuario-user").val(data['user']);
            $("#mds_seg_usuario-pass").val(data['pass']);
            $("#mds_seg_usuario-nombre").val(data['nombre']);
            $("#mds_seg_usuario-apellido").val(data['apellido']);
            $("#mds_seg_usuario-mail").val(data['mail']);
        });
    });

    $("#is_externo").change(function() {
        $("#is_externo").prop('checked') ? $("#externo_content").show() : $("#externo_content").hide();
    });
</script>