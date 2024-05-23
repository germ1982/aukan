<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_franco */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-franco-form">
    <div id="errors" class="alert alert-danger" style="display: none;"></div>
    <?php
    $form = ActiveForm::begin(['action' => ['mds_hor_franco/' . ($model->isNewRecord ? 'create_ext' : 'update_ext'), 'id' => $model->idfranco, 'idcontacto' => $model->idcontacto, 'fecha' => $model->fecha], 'id' => $model->formName()]); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            if ($model->fecha != null) {
                $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
            }
            echo $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => true,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha',
                    'class' => 'form-control input-md',
                    'disabled' => true
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha'); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tipo')->widget(Select2::class, [
                'data' => $tipos_franco,
                'options' => ['placeholder' => 'Seleccione Tipo de Franco...'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="row" style="padding-top: 2%">
            <div class="col-md-1">
                <?= ($model->isNewRecord ? "" : Html::a('Eliminar', ['delete', 'id' => $model->idfranco], ['class' => 'btn btn-danger'])) ?>
            </div>
            <div class="col-md-3 col-md-offset-8">
                <div class="form-group pull-right">
                    <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

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
                $('#modal_abm').modal('hide'); 
                e.preventDefault();                
            }else{
                $("#errors").show(),
                $("#errors").html(result);
            }
        }).fail(function(){
            console.log("server error");

        });
       
        return false;
    });
        

JS;

$this->registerJs($script);

?>