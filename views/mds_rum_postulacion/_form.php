<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Mds_seg_usuario_rol;

$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one(); 
// de la tabla mds_seg_usuario: $el_usuario->idusuario
/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_postulacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-postulacion-form">

    <?php $form = ActiveForm::begin(); ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
                <div class="col-md-10">
                    <div class="input-group">   
                    <?php                    
                        if ($un_rol_usuario == null)
                        { 
                            echo $form->field($model, 'id_persona')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map( 
                                    
                                    Mds_rum_persona::find()
                                    ->select ('mds_rum_persona.id as id, sds_com_persona.nombre as nombres, sds_com_persona.apellido as apellido,sds_com_persona.documento as dni')
                                    ->innerJoin ('sds_com_persona', 'sds_com_persona.idpersona = mds_rum_persona.id_com_persona') 
                                    ->orderBy(['nombres' => SORT_ASC, 'apellido' => SORT_ASC])                                
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
                            ])->label('Persona Postulante'); 
                            
                        }
                        else // es un admin empresa
                        {                            
                            $persona_postulada=Mds_rum_persona::find()
                            ->select ('mds_rum_persona.id as id, sds_com_persona.nombre as nombres, sds_com_persona.apellido as apellido,sds_com_persona.documento as dni')
                            ->Where(["mds_rum_persona.id"=> $model->id_persona] )
                            ->innerJoin ('sds_com_persona', 'sds_com_persona.idpersona = mds_rum_persona.id_com_persona') 
                            
                            ->orderBy(['nombres' => SORT_ASC, 'apellido' => SORT_ASC])                                
                            ->one();
                            
                            $model->datocompleto= $persona_postulada->nombres.' '.$persona_postulada->apellido;
                            echo $form->field($model, 'datocompleto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre');
                            
                        }
                    ?>
                        
                       
                    </div>
                </div>
            
        </div> <br>
        <div class="row">
                <div class="col-md-10">
                    <div class="input-group">  
                    <?php                    
                        if ($un_rol_usuario == null)
                        {                   
                        echo  $form->field($model, 'id_oferta')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map( 
                                Mds_rum_oferta_laboral::find()
                                    ->orderBy(['titulo' => SORT_ASC])->all(),
                                'id',
                                function ($model) {
                                    $fecha_fin_pub=$model->fecha_publicacionfin;        
                                    $hora_fin_pub=$model->hora_publicacionfin;  
                                    $fecha_publicacion=$model->fecha_publicacion;        
                                    $hora_publicacion=$model->hora_publicacion;           
                                    $fecha_actual= date('Y-m-d');
                                    $hora_actual=  date('H:i:s'); 
                                   
                                    $activa=$model->activo == 1 ? 'Activa' : 'No Activa';
                                    $cad=$activa; 
                                    if (($fecha_actual>$fecha_fin_pub) || (($fecha_actual==$fecha_fin_pub) && ($hora_actual >= $hora_fin_pub)))
                                    {
                                        $cad.=" | Finalizada";
                                    }   
                                    else
                                    {
                                        $cad.=" | No Finalizada";     
                                    }  
                                    if (($fecha_actual>$fecha_publicacion) || (($fecha_actual==$fecha_publicacion) && ($hora_actual >= $hora_publicacion)))
                                    {
                                        $cad.=" | Publicada";
                                    }
                                    else
                                    {
                                        $cad.=" | No Publicada";    
                                    }  


                                    $unafecha = explode ("-",$model->fecha_publicacion);
                                    $fecha_publicacion= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]); 
                                    
                                    return $model->titulo. " (". $fecha_publicacion." ".$cad.")";
                                }
                            ),
                            'options' => ['placeholder' => 'Seleccionar Oferta Laboral ...', 'id' => 'cmb_of_lab'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);

                        }
                        else
                        {
                            $oferta_lab=Mds_rum_oferta_laboral::find()
                            ->orderBy(['titulo' => SORT_ASC])
                            ->Where(["id"=> $model->id_oferta] )
                            ->one();
                            $model->nombre_oferta= $oferta_lab->titulo. " (". $oferta_lab->fecha_publicacion.")";
                            echo $form->field($model, 'nombre_oferta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre');
                        
                        }
                ?>                       
                    </div>
                </div>                                
        </div>
        <br>
        <div class="row" style="display: flex;align-items: flex-end;">
                <div class="col-md-8" >
                    <div class="input-group">                                           
                        <?php echo $form->field($model, 'estado')->        
                            dropDownList([ 'postulado' => 'postulado', 'elegido' => 'elegido', 'seleccionado' => 'seleccionado', 'finalista' => 'finalista','descartado' => 'descartado'])
                            ->label('Estado de la Postulación'); 
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

