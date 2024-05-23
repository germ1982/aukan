<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-configuracion-form">
    <?php $form = ActiveForm::begin(['action' => ['sds_com_configuracion/' . ($model->isNewRecord ? 'create' . ($botones ? '_ext' : '') : 'update'), 'tipo' => $model->idconfiguraciontipo, 'id' => $model->idconfiguracion], 'id' => $model->formName()]); ?>
    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>
    <?php if ($botones) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' :
                'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',]) ?>
            <?= Html::button('Cerrar', [
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_configuracion").hide();
                $("#modal_abm").modal("hide");
                $("#interv_form").show();
                $("#docente_form").show();
                $("#main_form").show();
                $("#btnGuardar").show();
                $("#btnCerrar").show();'
            ]); ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$ajax = Yii::$app->request->isAjax;
$script = <<<  JS

    $('form#{$model->formName()}').on('beforeSubmit',function(e){        
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){            
            if(result >= 1){
                $(\$form).trigger("reset");                  
                $('#abm_configuracion').hide();
                $('#modal_abm').modal('hide'); //or  $('#IDModal').modal('hide');
                $("#interv_form").show();
                $("#main_form").show();
                $("#docente_form").show();
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                e.preventDefault();
                $.post("index.php?r=sds_com_configuracion/cmb_config&tipo=$model->idconfiguraciontipo", function(data) {
                    $("select#config_$model->idconfiguraciontipo").html(data);
                    $("select#config_$model->idconfiguraciontipo").val(result);
                    $("select#config_$model->idconfiguraciontipo").trigger('change');
                });
            }else{
                $("#message").html(result);
            }
        }).fail(function(){
            console.log("server error");
        });
       
        return false;
    });
        

JS;

$this->registerJs($script);

?>