<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_barrio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-barrio-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>

    <?php if (!Yii::$app->request->isAjax || $botones) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' :
                'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btnBarrioGuardar']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>
<?php

$script = <<<  JS

    $('form#{$model->formName()}').on('beforeSubmit',function(e){
        
        var \$form = $(this);
        $.post(

            \$form.attr("action"),
            \$form.serialize()

        )
        .done(function(result){            
            if(result == 1){
                $(\$form).trigger("reset");                
                $('#modal_abm').modal('hide'); //or  $('#IDModal').modal('hide');                
                e.preventDefault();    
                $.post("index.php?r=sds_com_barrio/cmb_barrio&id=" + $("#sds_ris_risneu-idlocalidad").val(), function(data) {
                    $("select#barrio").html(data);
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