<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;
use app\models\Mds_rum_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Mds_rum_domicilio;
use app\models\Mds_rum_formacionacademica;
use app\models\Mds_rum_experiencia;
use app\models\Mds_rum_capacitacion;
use app\models\Mds_rum_conocgenerales;
use app\models\Mds_rum_licconducir;
use app\models\Mds_rum_docadicional;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_provincia;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Mds_seg_usuario_rol;
$el_usuario = Yii::$app->user->identity;
// analizando el rol
$un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
->where(['idusuario' => $el_usuario->idusuario])
->andWhere(["idrol"=> 38] )
->one(); 


/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_persona */
/* @var $form yii\widgets\ActiveForm */


$una_com_persona = Sds_com_persona::findOne($model->id_com_persona);
$un_seg_usuario = Mds_seg_usuario::findOne($model->id_seg_usuario);

?>

<div class="mds-rum-persona-view">
    <?php
        if ($un_seg_usuario->activo=="0")
        { 
            echo "<p style='color:#FF0000';>Usuario Recien creado. Aun no activa su cuenta</p>";
        }
        /*if ($model->Labels!="200")
        { 
            echo "<p style='color:#FF0000';>No registra los datos mínimos requeridos (Datos Personales/Domicilio/Inf Complementaria)</p>";
        }
        else
        {
            if ($model->Labels!="111")
            { 
                echo "<p style='color:#FF0000';>No registra los datos mínimos requeridos (Datos Personales/Domicilio/Inf Complementaria)</p>";
            }

        }*/
?>
    <?php $form = ActiveForm::begin(); ?>    
    DATOS PERSONALES <?php   echo $model->id;  ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-9"> 

                <div class="row">
                    <div class="col-md-6">        
                            <?= $form->field($una_com_persona, 'nombre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                    </div>   
                    <div class="col-md-6">        
                            <?= $form->field($una_com_persona, 'apellido')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                    </div>  
                </div>

                <div class="row">
                    <div class="col-md-3">        
                        <?= $form->field($una_com_persona, 'documento')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                    </div> 
                    <div class="col-md-3">  
                        <?php  
                                $unafecha = explode ("-",$una_com_persona->fecha_nacimiento);
                                $fecha_nacimiento= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);    
                                $una_com_persona->fecha_nacimiento=$fecha_nacimiento;
                        ?>      
                            <?= $form->field($una_com_persona, 'fecha_nacimiento')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                    </div>  
                    <div class="col-md-3">  
                        <?php                          
                                $un_genero = Sds_com_configuracion::findOne($una_com_persona->genero);
                                $model->su_genero=$un_genero->descripcion;                                                        
                        ?> 
                        <?= $form->field($model, 'su_genero')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Genero'); ?>
                    </div> 
                    <div class="col-md-3">  
                            
                            <?= $form->field($model, 'hijos')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Hijos'); ?>
                    </div>                     
                </div> 


                <div class="row">
                    <div class="col-md-4">  
                        <?php  
                                if ($model->tienecuil ==0)
                                {
                                    $model->tiene_cuil='no tiene cuil';
                                }else
                                {
                                    $model->tiene_cuil=$model->precuil.'-'.$una_com_persona->documento.'-'.$model->postcuil;
                                }                                
                        ?>      
                            <?= $form->field($model, 'tiene_cuil')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Cuil'); ?>
                    </div>  
                    <div class="col-md-4">  
                        <?php
                        //echo $model->idestadocivil;
                        
                            if ($model->idestadocivil==0)
                            {
                                $model->estado_civil="desconocido";
                            }
                            else
                            {
                                $un_estado_civil = Sds_com_configuracion::findOne($model->idestadocivil);
                                $model->estado_civil= $un_estado_civil->descripcion; 
                            }
                            ?>
                            <?= $form->field($model, 'estado_civil')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Estado Civil'); ?>                      
                              
                            
                    </div>  
                    <div class="col-md-4">  
                        <?php  
                            $una_nacionalidad = Sds_com_configuracion::findOne($una_com_persona->nacionalidad);
                            $model->la_nacionalidad= $una_nacionalidad->descripcion;                         
                        ?>      
                            <?= $form->field($model, 'la_nacionalidad')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nacionalidad'); ?>
                    </div> 
                    
                </div> 


            </div>  
            <div class="col-md-3"> 
                <div class="row">
                    <div class='col-md-12' align="center";>                                                                       
                        <?php
                            if ($model->foto == null) { 
                                if ($un_genero->descripcion =='Femenino'){$tipofoto='sinfotof.jpg';   }
                                else {$tipofoto='sinfotom.jpg';   }
                                    
                                echo '<figcaption class="text-center">FOTO</figcaption>
                                    <img  width="100%"   src="';                                                       
                                echo Url::base() . '/uploads/cvs/'.$tipofoto ;
                                echo  '">';
                            }
                            else
                            {
                                echo '<figcaption class="text-center">FOTO</figcaption>
                                    <img  width="100%"   src="';                                                       
                                echo 'https://apisur.neuquen.gov.ar/image/rumbo/'.$model->foto ;
                                echo  '">';
                                   
                            }
                        ?>                                                           
                    </div>        
                </div>
            </div>                      
        <div>
    </div>          
</div>
</div><br>
    CONTACTO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    
        <div class="row">
            <div class="col-md-4"> 
                <?= $form->field($un_seg_usuario, 'mail')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Email'); ?>                
            </div>
            <div class="col-md-4"> 
                <?= $form->field($model, 'telfijo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Telefono 1'); ?>                
            </div>
            <div class="col-md-4"> 
                <?= $form->field($model, 'telcel')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Telefono 2'); ?>                
            </div>
        </div>
    </div> <br>
    DOMICILIO
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <?php
            
            if ($model->iddomicilio==0)
            {
                /*$un_domicilio= new Mds_rum_domicilio;
                $un_domicilio->calle='';
                $un_domicilio->numero='';
                $un_domicilio->idlocalidad='';
                $un_domicilio->manzana='';
                $un_domicilio->duplex='';
                $un_domicilio->monoblock='';
                $un_domicilio->piso='';
                $un_domicilio->dpto='';
                $un_domicilio->lote='';
                $un_domicilio->barrio=''; */
                echo '
                <div >   
                    <div class="row">
                        <div class="col-md-12"> 
                            No registra Domicilio
                        </div>
                    </div>
                </div>';

            }
            else
            {
                $un_domicilio = Mds_rum_domicilio::findOne($model->iddomicilio);
                echo '
                
                            <div class="row">
                        <div class="col-md-5">';                 
                            echo $form->field($un_domicilio, "calle")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Calle"); 
                        echo '</div>
                        <div class="col-md-5">'; 
                           echo $form->field($un_domicilio, "numero")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Numero");                 
                        
                        echo '</div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-5">';
                                        
                                if ($un_domicilio->idlocalidad=="")
                                {
                                    $model->loc_domicilio="";
                                    $model->prov_dom='desconocido';
                                }
                                else
                                {
                                    $una_localidad = Sds_com_localidad::findOne($un_domicilio->idlocalidad);
                                    $model->loc_domicilio=$una_localidad->descripcion;
                                    $el_id_provincia_dom=$una_localidad->idprovincia;
                                    $una_prov_dom = Sds_com_provincia::findOne($el_id_provincia_dom);
                                    $model->prov_dom=$una_prov_dom->descripcion;
                                }                    
                        
                            echo  $form->field($model, "loc_domicilio")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Localidad");
                        echo '</div>    
                        <div class="col-md-5">';
                                                               
                            echo  $form->field($model, "prov_dom")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Provincia");
                        echo '</div>';  
                        echo '
                        
                    </div>
                    <div class="row">
                        <div class="col-md-4"> ';
                            echo $form->field($un_domicilio, "manzana")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Manzana");         
                        
                        echo '</div>
                        <div class="col-md-4">';
                            echo $form->field($un_domicilio, "duplex")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Duplex"); 
                        echo '</div>
                        <div class="col-md-4">'; 
                            echo $form->field($un_domicilio, "monoblock")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Monoblock");
                        echo '</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"> ';
                            echo $form->field($un_domicilio, "piso")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Piso"); 
                        echo '</div>
                        <div class="col-md-3"> ';
                            echo $form->field($un_domicilio, "dpto")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Dpto");                
                        echo '</div>
                        <div class="col-md-3">'; 
                            echo $form->field($un_domicilio, "lote")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Lote");                
                        echo '</div>
                        <div class="col-md-3"> ';
                            echo $form->field($un_domicilio, "barrio")->textInput(["maxlength" => true,"readOnly"=>true,"style"=>"background-color:#ffffff"])->label("Barrio");
                        echo '</div>
                    </div>
                
                
                ';


            }
            
    ?>
        
    </div> <br>
   
    FORMACION ACADEMICA
    <?php                           
        $formaciones=Mds_rum_formacionacademica::find()                                           
            ->where(['idpersona' => $model->id])
            ->all();            
        if ($formaciones==null)
        {
            echo '
            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                <div class="row">
                    <div class="col-md-12"> 
                        No registra Formación Academica
                    </div>
                </div>
            </div>';
        }  
        else
        {   echo '<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';
            $i=1;
            foreach ($formaciones as $una_formacion) { 
                echo 'Formacion '.$i.' <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';                                                    
                echo '<div class="row">';
                    echo '<div class="col-md-4"> ';
                        echo $form->field($una_formacion, 'nombre_instituto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Institución');                 
                    echo '</div>';
                    echo '<div class="col-md-4"> ';
                        $el_nivel=Sds_com_configuracion::findOne($una_formacion->nivel);
                        $una_formacion->nivel=$el_nivel->descripcion;
                        echo $form->field($una_formacion, 'nivel')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nivel Institución');                 
                    echo '</div>';
                    echo '<div class="col-md-4"> ';
                        $el_tipo_nivel=Sds_com_configuracion::findOne($una_formacion->tipodelnivel);
                        $una_formacion->tipodelnivel=$el_tipo_nivel->descripcion;
                        echo $form->field($una_formacion, 'tipodelnivel')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Tipo Institución');                 
                    echo '</div>';
                    
                echo '</div>';

                echo '<div class="row">';
                    echo '<div class="col-md-6"> ';
                        echo $form->field($una_formacion, 'detalle')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Detalle');                 
                    echo '</div>';
                    echo '<div class="col-md-3"> ';
                        echo $form->field($una_formacion, 'tiempocursado')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Periodo de Cursado');                 
                    echo '</div>';
                    echo '<div class="col-md-3"> ';
                        if ($una_formacion->culmino==0)
                        { 
                            $una_formacion->estadoculmino='En curso/ Sin culminar';
                        }
                        else 
                        { 
                            $una_formacion->estadoculmino='Culminado'; 
                        }
                        echo $form->field($una_formacion, 'estadoculmino')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Estado');                 
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="col-md-12"> ';
                        echo  $form->field($una_formacion, 'observacion')->textarea(['rows' => 4, "readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Observación');
                        
                    echo '</div>';
                echo '</div>';    
                $i++;
                echo '</div><br>';
            } 

            echo '</div>';
        }         
    ?>

<br>
EXPERIENCIA LABORAL
    <?php                           
        $experiencias=Mds_rum_experiencia::find()                                           
            ->where(['idpersona' => $model->id])
            ->all();            
        if ($experiencias==null)
        {
            echo '
            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                <div class="row">
                    <div class="col-md-12"> 
                        No registra Experiencias Laborales
                    </div>
                </div>
            </div>';
        }  
        else
        {   echo '<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';
            $i=1;
            foreach ($experiencias as $una_experiencia) { 
                echo 'Experiencia Laboral '.$i.' <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';                                                    
                echo '<div class="row">';
                    echo '<div class="col-md-6"> ';
                        echo $form->field($una_experiencia, 'entidad')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Lugar de trabajo');                 
                    echo '</div>';
                    echo '<div class="col-md-6"> ';
                        echo $form->field($una_experiencia, 'puesto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Puesto o Actividad');                 
                    echo '</div>';
                    
                echo '</div>';
                echo '<div class="row">';

                $lugarpaisexp=$una_experiencia->lugarpaisexp;

                if ($lugarpaisexp=='0') //argentina 0
                {
                    if (($una_experiencia->idlocalidad ==null) || ($una_experiencia->idlocalidad==''))
                    {

                    }
                    else
                    {

                        $una_localidad2 = Sds_com_localidad::findOne($una_experiencia->idlocalidad);
                        $model->una_localidad=$una_localidad2->descripcion;

                        $el_id_provincia_exp=$una_localidad2->idprovincia;
                        $una_prov_exp = Sds_com_provincia::findOne($el_id_provincia_exp);
                        $model->prov_exp=$una_prov_exp->descripcion;


                        echo '<div class="col-md-4"> ';
                        echo $form->field($model, 'una_localidad')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Localidad');                 
                        echo '</div>';
                        echo '<div class="col-md-4"> ';
                        echo $form->field($model, 'prov_exp')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Provincia');                 
                        echo '</div>';
                    }                                                             
                }
                else // extranjero
                {                        
                    echo '<div class="col-md-8"> ';
                    echo $form->field($una_experiencia, 'descripcionpaisexp')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Capacitación en el extranjero');                 
                    echo '</div>';                                                     
                }


                                                   
                                    

                    echo '<div class="col-md-4"> ';
                        echo $form->field($una_experiencia, 'periodo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Periodo');                 
                    echo '</div>';
                echo '</div>';
                






                echo '<div class="row">';
                    echo '<div class="col-md-12"> ';
                        echo  $form->field($una_experiencia, 'descripcion')->textarea(['rows' => 4, "readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Descripción');
                        
                    echo '</div>';
                echo '</div>';    
                $i++;
                echo '</div><br>';
            } 
            echo '</div>';
        }         
    ?>

<br>
CAPACITACIONES
    <?php                           
        $capacitaciones=Mds_rum_capacitacion::find()                                           
            ->where(['idpersona' => $model->id])
            ->all();            
        if ($capacitaciones==null)
        {
            echo '
            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                <div class="row">
                    <div class="col-md-12"> 
                        No registra Capacitaciones 
                    </div>
                </div>
            </div>';
        }  
        else
        {   echo '<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';
            $i=1;
            foreach ($capacitaciones as $una_capacitacion) { 
                echo 'Capacitación '.$i.' <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';                                                    
                echo '<div class="row">';
                    echo '<div class="col-md-4"> ';
                        echo $form->field($una_capacitacion, 'nombrecap')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre de la Capacitación');                 
                    echo '</div>';
                    echo '<div class="col-md-4"> ';
                        echo $form->field($una_capacitacion, 'lugarcapacitacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Lugar de la Capacitación');                 
                    echo '</div>';
                    echo '<div class="col-md-4"> ';
                    $unafecha = explode ("-",$una_capacitacion->fechacapacitacion);
                    $fechacapacitacion= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);    
                    $una_capacitacion->fechacapacitacion=$fechacapacitacion;

                        echo $form->field($una_capacitacion, 'fechacapacitacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Fecha de la Capacitación');                 
                    echo '</div>';
                    
                echo '</div>';
                echo '<div class="row">';
                    

                    $lugarpais=$una_capacitacion->lugarpais;

                    if ($lugarpais=='0') //argentina 0
                    {
                        if (($una_capacitacion->idlocalidad ==null) || ($una_capacitacion->idlocalidad==''))
                        {

                        }
                        else
                        {
                            $una_localidad2 = Sds_com_localidad::findOne($una_capacitacion->idlocalidad);
                            $model->una_localidad=$una_localidad2->descripcion;

                            $el_id_provincia_cap=$una_localidad2->idprovincia;
                            $una_prov_cap = Sds_com_provincia::findOne($el_id_provincia_cap);
                            $model->prov_cap=$una_prov_cap->descripcion;
                            echo '<div class="col-md-4"> ';
                            echo $form->field($model, 'una_localidad')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Localidad');                 
                            echo '</div>';
                            echo '<div class="col-md-4">';
                            echo $form->field($model, 'prov_cap')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Provincia');                 
                            echo '</div>';
                        }                                                             
                    }
                    else // extranjero
                    {                        
                        echo '<div class="col-md-8"> ';
                        echo $form->field($una_capacitacion, 'descripcionpais')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Capacitación en el extranjero');                 
                        echo '</div>';                                                     
                    }
                                                                                                                                             
                    
                    echo '<div class="col-md-4"> ';
                        echo $form->field($una_capacitacion, 'organizador')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Organizador');                 
                    echo '</div>';
                echo '</div>';
                
                echo '<div class="row">';
                    echo '<div class="col-md-12"> ';
                    if ($una_capacitacion->certificada==0)
                    { 
                        $una_capacitacion->estadocertificacion='No Certificada';
                    }
                    else 
                    { 
                        $una_capacitacion->estadocertificacion='Certificada'; 
                    }
                    echo $form->field($una_capacitacion, 'estadocertificacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Certificacion');                                                          
                        
                    echo '</div>';
                echo '</div>';    
                echo '<div class="row">';
                    echo '<div class="col-md-12"> ';
                        echo  $form->field($una_capacitacion, 'descripcion')->textarea(['rows' => 4, "readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Descripción');
                        
                    echo '</div>';
                echo '</div>';    
                $i++;
                echo '</div><br>';
            } 
            echo '</div>';
        }         
    ?>

<br>
CONOCIMIENTOS GENERALES
    <?php                           
        $conocgrales=Mds_rum_conocgenerales::find()                                           
            ->where(['idpersona' => $model->id])
            ->all();            
        if ($conocgrales==null)
        {
            echo '
            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                <div class="row">
                    <div class="col-md-12"> 
                        No registra Conocimientos Generales
                    </div>
                </div>
            </div>';
        }  
        else
        {   echo '<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';
            $i=1;
            foreach ($conocgrales as $un_cg) { 
                echo 'Conocimiento General '.$i.' <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';                                                    
                echo '<div class="row">';
                    echo '<div class="col-md-4"> ';
                        $una_detallecg = Sds_com_configuracion::findOne($un_cg->iddetalle);
                        $un_cg->detalle_cg= $una_detallecg->descripcion;                                                  
                        echo $form->field($un_cg, 'detalle_cg')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Detalle Conocimiento:');                 
                    echo '</div>';
                    echo '<div class="col-md-4"> ';
                        $un_nivelcg = Sds_com_configuracion::findOne($un_cg->idnivelcg);
                        $un_cg->nivel_cg= $un_nivelcg->descripcion;      

                        echo $form->field($un_cg, 'nivel_cg')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nivel');                 
                    echo '</div>';                   
                    
                echo '</div>';
                   
                echo '<div class="row">';
                    echo '<div class="col-md-12"> ';
                        echo  $form->field($un_cg, 'descripcion')->textarea(['rows' => 4, "readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Descripción');
                        
                    echo '</div>';
                echo '</div>';    
                $i++;
                echo '</div><br>';
            } 
            echo '</div>';
        }         
    ?> <br>

INFORMACIÓN COMPLEMENTARIA
    <?php                           
        $docadicionales=Mds_rum_docadicional::find()                                           
            ->where(['id' => $model->iddocadicional])
            ->one();            
        if ($docadicionales==null)
        {
            echo '
            <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                <div class="row">
                    <div class="col-md-12"> 
                        No registra Documentacion Adicional
                    </div>
                </div>
            </div>';
        }  
        else
        {   echo '<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  ';
           
                
                   echo '<div class="row">';
                        echo '<div class="col-md-2"> ';
                            if ($docadicionales->libsanitaria == 1) { $docadicionales->poseelibreta='tiene';}
                            else { $docadicionales->poseelibreta='no tiene';}                                                                      
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Libreta Sanitaria');                 
                        echo '</div>';
                        echo '<div class="col-md-2"> ';
                            if ($docadicionales->tienelibretaconstruct == 1) { $docadicionales->poseelibreta='tiene';}
                            else { $docadicionales->poseelibreta='no tiene';}                                                                      
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Libreta Constr.');                 
                        echo '</div>';
                        echo '<div class="col-md-3"> ';
                            if ($docadicionales->disponibilidadviaje == 1) { $docadicionales->poseelibreta='tiene';}
                            else { $docadicionales->poseelibreta='no tiene';}                                                                      
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Disponibilidad de viaje');                 
                        echo '</div>';
                        echo '<div class="col-md-2"> ';
                            if ($docadicionales->vehiculopropio == 1) { $docadicionales->poseelibreta='tiene';}
                            else { $docadicionales->poseelibreta='no tiene';}                                                                      
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Vehiculo propio');                 
                        echo '</div>';
                        echo '<div class="col-md-3"> ';
                        
                            $disphor = Sds_com_configuracion::findOne($docadicionales->iddisphoraria);
                            $docadicionales->poseelibreta= $disphor->descripcion;   
                                                                                                
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Dispo. Horaria');                 
                        echo '</div>';
                    echo '</div>'; // del row
                    echo '<div class="row">';
                        echo '<div class="col-md-12"> ';
                            if ($docadicionales->tienelicconducir == 0) 
                            { $docadicionales->poseelibreta='no tiene';
                            
                            }
                            else 
                            { 
                                $una_licencia = Mds_rum_licconducir::findOne($docadicionales->idlicconducir);
                                $docadicionales->poseelibreta=$una_licencia->clase.'-'.$una_licencia->subclase.': '. $una_licencia->descripcion;
                            
                            }                                                                      
                            echo $form->field($docadicionales, 'poseelibreta')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Licencia de Conducir');                 
                        echo '</div>';
                    echo '</div>'; // del row                    
                    if ($docadicionales->estsupmax =='')
                    {
                        if ($docadicionales->oficioprincipal ==''){}
                            else
                            {echo '<div class="row">';
                                echo '<div class="col-md-6"> ';                                                                                         
                                    echo $form->field($docadicionales, 'oficioprincipal')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Oficio Principal');                 
                                echo '</div>';
                                echo '</div>'; // del row
                            }
                    }
                    else
                    {
                        echo '<div class="row">';
                            echo '<div class="col-md-6"> ';
                                                                                         
                                echo $form->field($docadicionales, 'estsupmax')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Estudios Superiores');                 
                            echo '</div>';
                            if ($docadicionales->oficioprincipal ==''){}
                            else
                            {
                                echo '<div class="col-md-6"> ';                                                                                         
                                    echo $form->field($docadicionales, 'oficioprincipal')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Oficio Principal');                 
                                echo '</div>';
                            }
                            
                        echo '</div>'; // del row

                    }
                   
                    echo '<div class="row">';
                        echo '<div class="col-md-12"> ';
                            echo  $form->field($docadicionales, 'habilidades')->textarea(['rows' => 4, "readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Habilidades');
                            
                        echo '</div>';
                echo '</div>';    
                echo '</div>'; // principal
           
           
        }         
    ?>  
    <?php
           if ($un_rol_usuario == null) // es administrador
           {
               echo '<br> HERRAMIENTAS AUXILIARES
               <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">   
                   <div class="row">
                       <div class="col-md-12">';                           
                                   echo Html::submitButton('Reenviar Correo de Validación', ['id'=>'boton_reenviar','class' => 'submit','submit'=>array('NotificarEstado') ]);                            
                       echo '</div>
                   </div>
               </div>';
           }
    ?>
    
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<?php
$cadena_enviar='"index.php?r=mds_rum_persona/reenviar_confirmacion&id='.$model->id.'"';
$js = <<< JS
    $("#boton_reenviar").on("click", 
        function() 
        {   
            //var cmb_users=$("#cmb_para_usuarios option:selected").text();
            // (cmb_users=="Seleccionar ...")
            /*{
                krajeeDialog.alert("No se pueden enviar los datos de la cuenta \\n  Aun no asoció un usuario a la Empresa");
            }
            else
            {*/
                krajeeDialog.confirm("¿Seguro desea reenviar el email de confirmación de registro al usuario?", 
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
            //}                                            
            return false;
        });
JS;
$this->registerJs($js);
?>