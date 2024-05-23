<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_postulacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-postulacion-form">

    <?php $form = ActiveForm::begin(); ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
                <div class="col-md-10">
                    <div class="input-group"> tttt
                    
                        <?= $form->field($model, 'id_persona')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map( 
                                Mds_rum_persona::find()
                                    ->orderBy(['nombres' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                                'id',
                                function ($model) {
                                    return $model->nombres . " " . $model->apellido." - dni ".$model->dni;
                                }
                            ),
                            'options' => ['placeholder' => 'Seleccionar Persona ...', 'id' => 'cmb_persona'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                       
                    </div>
                </div>
            
        </div> <br>
        <div class="row">
                <div class="col-md-10">
                    <div class="input-group">                    
                        <?= $form->field($model, 'id_oferta')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map( 
                                Mds_rum_oferta_laboral::find()
                                    ->orderBy(['titulo' => SORT_ASC])->all(),
                                'id',
                                function ($model) {
                                    $unafecha = explode ("-",$model->fecha_publicacion);
                                    $fecha_publicacion= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]); 
                                    
                                    return $model->titulo. " (". $fecha_publicacion.")";
                                }
                            ),
                            'options' => ['placeholder' => 'Seleccionar Oferta Laboral ...', 'id' => 'cmb_of_lab'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>                       
                    </div>
                </div>                                
        </div>
    </div>    

    <?php  //echo $form->field($model, 'fecha_post')->textInput(); ?>

    <?php //echo $form->field($model, 'hora_post')->textInput(); ?> 

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
