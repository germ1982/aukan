<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;
use app\models\Mds_rum_postulacion;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_postulacion */
/* @var $form yii\widgets\ActiveForm */
$una_post = Mds_rum_postulacion::findOne($model->id);
$model->id_persona=$una_post->id_persona;
?>

<div class="mds-rum-postulacion-form3">
    <?php //echo 'el id: '.$model->id.' - el id_oferta: '.$model->id_oferta.' - id persona: '.$model->id_persona;?>
    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
                <div class="col-md-10">
                    <div class="input-group"> 
                    <?php
                           
                            $postulaciones=Mds_rum_postulacion::find() 
                                    ->select(['id_persona'])                                   
                                    ->where(['id_oferta' => $model->id_oferta])
                                    ->all();
                                    $post_final= array();
                                    $i=0;
                                    foreach ($postulaciones as $una_post) {   
                                        if ($una_post->id_persona == $model->id_persona){}
                                        else
                                        {
                                            $post_final[$i]=$una_post->id_persona;
                                            $i++;
                                        }
                                        
                                    }
                                    
                    ?>
                        <?= $form->field($model, 'id_persona')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map( 
                                Mds_rum_persona::find()
                                ->select ('mds_rum_persona.id as id, sds_com_persona.nombre as nombres, sds_com_persona.apellido as apellido,sds_com_persona.documento as dni')
                                ->innerJoin ('sds_com_persona', 'sds_com_persona.idpersona = mds_rum_persona.id_com_persona') 
                                ->orderBy(['nombres' => SORT_ASC, 'apellido' => SORT_ASC])
                                ->where(['not in','id',$post_final])
                                ->all(),
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
