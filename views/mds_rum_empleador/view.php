<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_usuario;
use app\models\Mds_rum_domicilio;
use yii\helpers\Url;
use app\models\Mds_seg_usuario_rol;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_empleador */
/* @var $form yii\widgets\ActiveForm */
$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one(); 
?>

<div class="mds-rum-empleador-form"> 

    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">  
                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre o Razon Social') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">  
                <?= $form->field($model, 'slogan')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>
    </div>
  
<br>
    

    <div class="row">
            <div class="col-md-6" >
                CONTACTO 
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'nombre_contacto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                            <?= $form->field($model, 'cargo_contacto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>                                                                                                                                                                                                                                
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                            <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                            <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>                        
                </div>        
            </div>         
            
            <div class='col-md-6' align="center";>
            <?php
                        if ($model->imagen == null) {
                            echo 'No tiene imagen guardada';
                        }
                        else
                        {
                            echo '
                            <figcaption class="text-center">IMAGEN PRINCIPAL</figcaption>
                                <img  width="100%"   src="';
                                //echo Html::img(Url::base()."/uploads/ofertas/".$model->imagen);
                                echo Url::base() . '/uploads/empleador/'.$model->imagen ;
                                echo  '">
                                
                            ';

                        }
                            ?>
                       
            </div>
           
        </div><br> 
    DOMICILIO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <?php
        if ($model->id==null)
        { //echo 'es nuevo';
        
        }
        else
        { //echo 'Ya tiene contenido';
            $un_domicilio = Mds_rum_domicilio::findOne($model->iddomicilio);                    
        }
            
           
    ?>
        <div class="row">
            <div class="col-md-5"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'calle')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Calle'); }
                    else
                    {   $model->calle=$un_domicilio->calle;
                        echo  $form->field($model, 'calle')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Calle'); 
                    } 
                ?>
                
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'numero')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Numero'); }
                    else
                    {   $model->numero=$un_domicilio->numero;
                        echo  $form->field($model, 'numero')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Numero'); } 
                ?>
                
            </div>
            <div class="col-md-3"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'manzana')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Manzana'); }
                    else
                    {   $model->manzana=$un_domicilio->manzana;
                        echo  $form->field($model, 'manzana')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Manzana'); } 
                ?>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'duplex')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Duplex'); }
                    else
                    {   $model->duplex=$un_domicilio->duplex;
                        echo  $form->field($model, 'duplex')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Duplex'); 
                    } 
                ?>                              
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'monoblock')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Monoblock'); }
                    else
                    {   $model->monoblock=$un_domicilio->monoblock;
                        echo  $form->field($model, 'monoblock')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Monoblock'); } 
                ?> 
                
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'piso')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Piso'); }
                    else
                    {   $model->piso=$un_domicilio->piso;
                        echo  $form->field($model, 'piso')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Piso'); } 
                ?>                 
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'dpto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Dpto'); }
                    else
                    {   $model->dpto=$un_domicilio->dpto;
                        echo  $form->field($model, 'dpto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Dpto'); } 
                ?>   
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'lote')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Lote'); }
                    else
                    {   $model->lote=$un_domicilio->lote;
                        echo  $form->field($model, 'lote')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Lote'); } 
                ?>                 
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->id==null)
                    { echo  $form->field($model, 'barrio')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Barrio'); }
                    else
                    {   $model->barrio=$un_domicilio->barrio;
                        echo  $form->field($model, 'barrio')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Barrio'); } 
                ?>    
                
            </div>

        </div> 
            <div class="row">   
                <div class="col-md-4">         
                    <?php
                        if ($un_domicilio->idlocalidad ==''){}
                        else
                        {
                            //echo 'el id localidad es: '.$model->id_localidad;
                            $para_id_una_prov=Sds_com_localidad::find() 
                            ->select(['idprovincia'])                                   
                            ->where(['idlocalidad' => $un_domicilio->idlocalidad])
                            ->one();  
                            $una_provincia=Sds_com_provincia::find() 
                            ->select(['descripcion'])                                   
                            ->where(['idprovincia' => $para_id_una_prov->idprovincia])
                            ->one();  

                            $model->laprovincia=$una_provincia->descripcion;
                            echo $form->field($model, 'laprovincia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Provincia');
                        }

                    ?>
                </div>
                <div class="col-md-4"> 
                    <?php
                    if ($un_domicilio->idlocalidad ==''){}
                    else
                    {
                        $loc = Sds_com_localidad::findOne($un_domicilio->idlocalidad);
                         echo $form->field($loc, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Localidad');
                    }
                    ?>
                    
                </div>               
                
            </div> 
                    
           
        </div>      
    <br>  
    OTROS
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-6">
                <div class="row">

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $categoria = Sds_com_configuracion::findOne($model->id_categoria);?>
                                    <?= $form->field($categoria, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Categoria') ?>                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <?php 
                                    if ($model->estado==1)
                                    {
                                        $model->el_estado="Espera Validación";
                                    }
                                    else
                                    {
                                        if ($model->estado==2)
                                        {
                                            $model->el_estado="Pendiente de Aprobación";
                                        }
                                        else
                                        {
                                            if ($model->estado==3)
                                            {
                                                $model->el_estado="Aceptada";
                                            } 
                                            else
                                            {
                                                $model->el_estado="Rechazada";
                                            }
                                        }
                                    }
                                ?>                                                             
                                    <?= $form->field($model, 'el_estado')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Estado') ?>                    
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-6">                                                   
                                CUIT
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">                        
                                <div class="row">                    
                                    <div class="col-md-12"> 
                                        <?= $form->field($model, 'cuit')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff'])->label(false) ?>
                                    </div>
                                </div>
                                <?php
                                    if ($un_rol_usuario == null) // es administrador
                                    { echo "
                                        <div class='row'>                    
                                            <div class='col-md-12'>";                                         
                                              
                                                    $url =  Url::to("https://seti.afip.gob.ar/padron-puc-constancia-internet/ConsultaConstanciaAction.do",true);
                                                    echo Html::a('<i class="far fa-check-circle"></i> Validar Cuit', $url, [
                                                                                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank','class' => 'btn btn-outline-primary',]);
                                                echo "
                                            </div>
                                        </div>";
                                    }

                                ?>
                        </div>
                        </div> 

                        
                </div>
                <div class="row">
                    <div class="col-md-12">    
                        <?php
                            if ($model->activo==1) {echo '*** La Empresa esta activa';}
                            else
                            if ($model->activo==0) {echo '*** La Empresa no esta activa';}
                        ?>       
                    </div>
                </div>
            </div>   
            <div class="col-md-6">    
                <div class="row">
                    <div class="col-md-12">  
                            USUARIO ASOCIADO
                            <?php $usuarioasociado = Mds_seg_usuario::findOne($model->idpersona);?>
                        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                        
                            <div class="row">
                            
                                <div class="col-md-12">                     
                                    
                                    <?php if ($usuarioasociado ==null)
                                    {   echo 'No tiene asociado un Usuario Asignado';
                                    }
                                    else
                                    {
                                        echo $form->field($usuarioasociado, 'user')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre de Usuario');    
                                    }
                                    ?>
                                    
                                </div>                    
                            </div>
                            <div class="row">                    
                                        <div class="col-md-12">
                                        <?php 
                                        if ($un_rol_usuario == null) // es administrador
                                        {
                                            echo Html::submitButton('Enviar Datos Cuenta', ['id'=>'boton_not','class' => 'submit','submit'=>array('NotificarEstado') ]);
                                        }      
                                                                                                                         
                                        ?>
                                            
                                            
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<?php
$script = <<<  JS

$('.btn-ajax-modal').click(function (){
       var elm = $(this),
           target = elm.attr('data-target'),
           ajax_body = elm.attr('value');

       $(target).modal('show')
           .find('.modal-content')
           .load(ajax_body);
});

JS;

$cadena_enviar='"index.php?r=mds_rum_empleador/enviar_datos&id='.$model->id.'"';
$js = <<< JS
    $("#boton_not").on("click", 
        function() 
        {   
            var cmb_users=$("#cmb_para_usuarios option:selected").text();
            if (cmb_users=="Seleccionar ...")
            {
                krajeeDialog.alert("No se pueden enviar los datos de la cuenta \\n  Aun no asoció un usuario a la Empresa");
            }
            else
            {
                krajeeDialog.confirm("¿Seguro desea enviar un email, con los datos de la cuenta, al usuario asociado?", 
                function (result) 
                {
                        if (result) 
                        {
                                // Aqui debe ir el codigo que llama a la accion del controlador                                
                                $.post($cadena_enviar, 
                                function(data) 
                                {                                                                          
                                    krajeeDialog.alert("Se ha enviado el email con exito.");
                                });
                        } 
                });
            }                                            
            return false;
        });
JS;
$this->registerJs($script);
$this->registerJs($js);
?>