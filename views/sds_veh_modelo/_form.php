<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_modelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-veh-modelo-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
    <div class="row">
        <div class="col-md-10">
        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true])->label("Descripción") ?>
        </div>
        <div class="col-md-2" style="padding-top:35px">
        <?= $form->field($model, 'activo')->checkBox(['checked' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'idmarca')->hiddenInput()->label(false) ?> 

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php if ($botones) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' :
                'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',]) ?>
            <?= Html::button('Cerrar', [
                'class' => 'btn btn-default',
                'onclick' => 
                '/*$("#abm_configuracion").hide();*/
                $("#modal_abm").modal("hide");
                /* $("#interv_form").show(); */
                /* $("#docente_form").show(); */
                /* $("#main_form").show(); */
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
                //$('#abm_configuracion').hide();
                $('#modal_abm').modal('hide'); //or  $('#IDModal').modal('hide');
                $("#main_form").show();
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                e.preventDefault();
                $.post("index.php?r=sds_veh_modelo/cmb_modelo&marca=$model->idmarca", function(data) {
                    var options = "";
                    data.forEach(modelo => {
                        options+="<option value='"+modelo.idmodelo+"'>"+modelo.descripcion+"</option>";
                    });
                    $("select#modelo_marca").html(options);
                    $("select#modelo_marca").val(result);
                    $("select#modelo_marca").trigger('change');
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
