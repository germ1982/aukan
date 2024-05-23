<?php

use yii\widgets\DetailView;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\Sds_ris_persona;
use app\models\Sds_com_persona;
use app\models\Sds_ris_risneu;
use yii\helpers\Html;
use app\models\Sds_com_configuracion;
use app\models\Sds_ris_risneu_alimentacion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_barrio;
use app\models\Sds_com_calle;
use yii\widgets\Pjax;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use app\models\Sds_ris_personaSearch;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atpcen_encuesta */
function CalculaEdad( $fecha ) 
{
    list($Y,$m,$d) = explode("-",$fecha);return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}
/*$fecha = $model->fecha_nacimiento;
$edad=CalculaEdad( $fecha );
$anio = substr($fecha, 0, 4);
$mes  = substr($fecha, 5, 2);
$dia = substr($fecha, 8, 2);
$fecha = "$dia/$mes/$anio";*/

$un_risneu_=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
$un_risneu_rest=Sds_ris_risneu::find()->where('idrisneu="'.$model->id_risneu.'" ')->one();
$model_persona=Sds_com_persona::find()->where(' idpersona="'.$un_risneu_->idpersona.'" ')->one();
$grupo_familiar=Sds_ris_persona::find()->where('idrisneu="'.$model->id_risneu.'" ')->all();
$this->title = 'ATPCen :: Ver Encuesta Nro '.$model->id_atpcen;
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="mds-atpcen-encuesta-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body"> 
<?php $form = ActiveForm::begin(); ?>
<div class="mds-atp-solicitud-view">

DATOS DE LA ENCUESTA
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">    
    <div class="row">    
        <div class="col-md-2">
            <?php
                if ($model->fecha_hora_entrevista != null) 
                {                                                             
                    $fn=$model->fecha_hora_entrevista;
                    $model->fecha_hora_entrevista = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora_entrevista)));
                }else
                { 
                    $fn=null;
                    $model->fecha_hora_entrevista="desconocida";
                }   
            ?>
            <?= $form->field($model, 'fecha_hora_entrevista')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Fecha Entrevista") ?> 
        </div>   
        <div class="col-md-2">
            
            <?= $form->field($model, 'hora_entrevista')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Hora Entrevista") ?> 
        </div>   
        <div class="col-md-3">
            
            <?= $form->field($model, 'entrevistador')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Entrevistador") ?> 
        </div> 
        <div class="col-md-3">
            <?php
                    if ($model->id_localidad_entrevista==null)
                    {$model->loc_prov_e='no registra'; }
                    else
                    {
                        $una_localidad_e = Sds_com_localidad::find()->where(['idlocalidad' => $model->id_localidad_entrevista])->one();
                        $una_provincia_e = Sds_com_provincia::find()->where(['idprovincia' => $una_localidad_e->idprovincia])->one(); 
                        $model->loc_prov_e=$una_localidad_e->descripcion." (".$una_provincia_e->descripcion.")";

                    }
                       
            ?>
            <?= $form->field($model, 'loc_prov_e')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Localidad de la Entrevista") ?> 
        </div> 
        
    </div>
</div>
<br>



1. Datos del entrevistado/a - Persona con enfermedad celiaca:
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <div class="row">
        <div class="col-md-3"> 
            <?= $form->field($model_persona, 'nombre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                     
        </div>          
        <div class="col-md-3">
            <?= $form->field($model_persona, 'apellido')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?> 
        </div> 
        <div class="col-md-2">
            <?= $form->field($model_persona, 'documento')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("DNI")?> 
        </div> 
        
        <div class="col-md-2">
            <?php $conf_nac=Sds_com_configuracion::findOne($model_persona->nacionalidad);?>
            <?= $form->field($conf_nac, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Nacionalidad")?> 
        </div>
        <div class="col-md-2"> 
            <?php $conf_gen=Sds_com_configuracion::findOne($model_persona->genero);?>
            <?= $form->field($conf_gen, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Genero") ?>                     
        </div>    
        
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Email")?> 
        </div>   
        <div class="col-md-2">
            <?php
                if ($model_persona->fecha_nacimiento != null) 
                {                                                             
                    $fn=$model_persona->fecha_nacimiento;
                    $model_persona->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model_persona->fecha_nacimiento)));
                }else
                { 
                    $fn=null;
                    $model_persona->fecha_nacimiento="desconocida";
                }   
            ?>
            <?= $form->field($model_persona, 'fecha_nacimiento')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Fecha de Nacimiento") ?> 
        </div>   
        <div class="col-md-2">
            <div class="row">                                              
                                                
                <blockquote class="blockquote" id="blockedad">
                <p><?php 
                    if ($fn != null)
                    { 
                        $edad=CalculaEdad($fn); 
                        echo 'Edad: '; if ($edad==1){echo $edad.' año';}else{echo $edad.' años';                        
                    }
                                                    
                    } else { $edad=110;}                                      
                ?></p> 
                <?php if ($edad<18){ $requiere_tutor=true; echo '<footer class="blockquote-footer">Se requiere un tutor</footer>'; }
                    else { $requiere_tutor=false; echo '<footer class="blockquote-footer">No se requiere tutor</footer>'; }
                ?> </blockquote>    
                                                
            </div>   
        </div>  
        <div class="col-md-2">
            
            <?= $form->field($model, 'telefono_contacto1')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Telefono 1")?> 
        </div>
        <div class="col-md-2">             
            <?= $form->field($model, 'telefono_contacto2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label("Telefono 2") ?>                     
        </div>      
    </div>
    DOMICILIO <br>
        <div class="row">
            
            <div class="col-md-3">
                <?php
                    $barrio = Sds_com_barrio::find()->where(['idbarrio' => $un_risneu_rest->idbarrio])->one();
                    $model->idlocalidad = $barrio->idlocalidad;                    
                    $una_localidad = Sds_com_localidad::find()->where(['idlocalidad' => $model->idlocalidad])->one();
                    $una_provincia = Sds_com_provincia::find()->where(['idprovincia' => $una_localidad->idprovincia])->one();
                    
                    $model->loc_prov=$una_localidad->descripcion." (".$una_provincia->descripcion.")";                    
                    $un_area=Sds_com_configuracion::findOne($un_risneu_rest->area);                   
                    $una_calle=Sds_com_calle::findOne($un_risneu_rest->calle); 
                    $una_calle_int=Sds_com_calle::findOne($un_risneu_rest->calle_interseccion); 
                ?>  
                <?= $form->field($model, 'loc_prov')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Localidad")?>                 
            </div>
            <div class="col-md-2">                
                <?= $form->field($una_localidad, 'codigo_postal')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Código Postal")?>                 
            </div>
            <div class="col-md-2">                
                <?= $form->field($barrio, 'nombre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Barrio")?>                 
            </div>
            <div class="col-md-2">   
                       
                <?= $form->field($un_area, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Area")?>                 
            </div>
            
        </div>
        <div class="row">
                <div class="col-md-3">                          
                    <?= $form->field($una_calle, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Calle")?>                 
                </div>
                <div class="col-md-3">                          
                    <?= $form->field($una_calle_int, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Calle intersección")?>                 
                </div>
                <div class="col-md-3">                          
                    <?= $form->field($un_risneu_rest, 'calle_numero')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Número")?>                 
                </div>
        </div>
        <div class="row">
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'casa')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Casa")?>                 
                </div>
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'torre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Torre")?>                 
                </div>
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'piso')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Piso")?>                 
                </div>


                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'depto')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("depto")?>                 
                </div>
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'manzana')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Manzana")?>                 
                </div>
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'parcela')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Parcela")?>                 
                </div>

                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'lote')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Lote")?>                 
                </div>
                <div class="col-md-1">                          
                    <?= $form->field($un_risneu_rest, 'pilar')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Pilar")?>                 
                </div>
        </div>
        <div class="row">
            <div class='col-md-6' align="center";>
            DNI FRENTE
            <?php
                if ($model->frente_dni==null)
                {
                    $model->frente_dni='no_registra.jpg';
                }
                else
                {
                    $exists1 = file_exists( 'uploads/atpcen/'.$model->frente_dni );
                    if ($exists1){} else {$model->frente_dni='no_registra.jpg'; }
                }
                if ($model->dorso_dni==null)
                {
                    $model->dorso_dni='no_registra.jpg';
                
                }
                else
                {
                    $exists2 = file_exists( 'uploads/atpcen/'.$model->dorso_dni );
                    if ($exists2){} else {$model->dorso_dni='no_registra.jpg'; }
                }
            ?>
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; height:265px;' id='base64image' src='uploads/atpcen/<?php  echo $model->frente_dni;?>' />                    
            </div>
            <div class='col-md-6' align="center";> 
            DNI DORSO    
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; height:265px;' id='base64image' src='uploads/atpcen/<?php  echo $model->dorso_dni;?>' />   
            </div>
        </div> <br>
        <div class="row" style='display:<?= $requiere_tutor ? "block" : "none" ?> '  >
            <div class="col-md-12">
                <br>DATOS DEL TUTOR
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($model, 'tipo_documento_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Tipo Documento Tutor") ?>                                                                               
                    </div>                                                                                   
                    <div class="col-md-2">
                        <?= $form->field($model, 'documento_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("DNI Tutor") ?> 
                    </div>      
                    <div class="col-md-2">
                        <?= $form->field($model, 'cuil_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Cuil Tutor") ?> 
                    </div>     
                    <div class="col-md-2">
                        <?php
                            if ($model->sexo_tutor=='F'){$model->sexo_tutor="Femenino"; }
                            else{  
                                    if ($model->sexo_tutor=='M'){$model->sexo_tutor="Masculino"; }
                                    else
                                    {
                                        if ($model->sexo_tutor=='I'){$model->sexo_tutor="Indefinido"; }
                                    }
                            }
                            
                        ?>   
                        <?= $form->field($model, 'sexo_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Genero Tutor") ?>                                             
                    </div>  
                    <div class="col-md-2">
                        <?php
                            if ($model->fecha_nac_tutor != null) {
                                $model->fecha_nac_tutor = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nac_tutor)));
                            }
                        ?>
                        <?= $form->field($model, 'fecha_nac_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Fecha Nac. Tutor") ?>                                             
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'parentezco_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Parentezco Tutor") ?>                  
                    </div>  
                    
                </div>
                <div class="row"> 
                    <div class="col-md-3">                
                        <?= $form->field($model, 'nombre_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Nombre Tutor") ?>                                             
                    </div>
                    <div class="col-md-3">                
                        <?= $form->field($model, 'apellido_tutor')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Apellido Tutor") ?>                                             
                    </div>
                </div>
            </div>
        </div>

</div>
<br>
2. Datos del Grupo Familiar:
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">    
    <div class="row">    
        <div class="col-md-12"> 
        <br>         
            <div class="table-responsive"> 
                <table class="table table-hover table-striped"> 
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Apellido y Nombre</th>
                            <th scope="col">Vinculo</th>
                            <th scope="col">Edad</th>
                            <th scope="col">DNI</th>
                            <th scope="col">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cont=1;
                            foreach($grupo_familiar as $un_familiar)
                            {
                                $id_persona_fam=$un_familiar->idpersona;
                                $model_persona_fam=Sds_com_persona::find()->where(' idpersona="'.$id_persona_fam.'" ')->one();
                                $parentezco_fam=Sds_com_configuracion::findOne($un_familiar->parentezco);
                                $fecha_nac_fam=$model_persona_fam->fecha_nacimiento;
                                $edad_fam=CalculaEdad( $fecha_nac_fam );
                            
                                $sit_cony_fam=Sds_com_configuracion::findOne($un_familiar->situacion_conyugal);
                                
                                $ult_anio_fam=Sds_com_configuracion::findOne($un_familiar->ultimo_ano_aprobado);
                                $cobertura_fam=Sds_com_configuracion::findOne($un_familiar->cobertura_salud);
                                $discapacidad_fam=Sds_com_configuracion::findOne($un_familiar->discapacidad);
                                $observacion_fam="Situacion Conyugal: ".$sit_cony_fam->descripcion."<br>Ult. año aprob.: ".$ult_anio_fam->descripcion."<br>Cobertura salud: ".$cobertura_fam->descripcion."<br>Discapacidad: ".$discapacidad_fam->descripcion;
                                //print_r($un_familiar->escolaridad);
                                //print_r($model_persona_fam->nombre." / ");
                                echo '
                                <tr>
                                    <th scope="row">'.$cont.'</th>
                                    <td>'.$model_persona_fam->nombre.' '.$model_persona_fam->apellido.'</td>
                                    <td>'.$parentezco_fam->descripcion.'</td>
                                    <td>'.$edad_fam.'</td>
                                    <td>'.$model_persona_fam->documento.'</td>
                                    <td>'.$observacion_fam.'</td>
                                </tr> ';  
                                $cont++;  

                            }
                        ?>                                                                          
                    </tbody>
                </table>
            </div>
        </div>        
    </div>

</div>
<br>

3. Ubicación del núcleo de convivencia (Situación Habitacional):
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    A.- Vivienda:
    <div class="row">    
        <div class="col-md-2">
             <?php $conf_propiedad=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_propiedad);?>
            <?= $form->field($conf_propiedad, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("1.Régimen de tenencia")?>             
        </div>
        <div class="col-md-2">
            <?php $conf_tipo_viv=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_tipo);?>
            <?= $form->field($conf_tipo_viv, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("2.Tipo de vivienda")?>                
        </div>
        <div class="col-md-3">
            <?php
                if ($model->condiciones=="suficiente"){$model->condiciones="suficiente para el número de integrantes";}
                else {$model->condiciones="insuficiente para el numero de integrantes";}
            ?>
            <?= $form->field($model, 'condiciones')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("3. Condiciones de habitabilidad")?>                
        </div>
        <div class="col-md-3">
            <?php $conf_bano=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_bano);?>
            <?= $form->field($conf_bano, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("4.Baño")?>             
        </div>
    </div>
    5.- Provisión de servicios básicos:
    <div class="row">    
        <div class="col-md-2">
             <?php $conf_agua=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_agua);?>
            <?= $form->field($conf_agua, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("a.Agua")?>             
            
        </div>
        <div class="col-md-2">
            <?php $conf_iluminacion=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_iluminacion);?>
            <?= $form->field($conf_iluminacion, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("b.Electricidad")?>                
        </div>
        <div class="col-md-2">
            <?php $conf_gas=Sds_com_configuracion::findOne($un_risneu_rest->vivienda_combustible_cocina);?>
            <?= $form->field($conf_gas, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("b.gas")?>                
        </div>
        
    </div>

</div>
<br>
4. Situación económica:
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">    
    a. Ingresos familiares total: (importe aproximado)
    <div class="row">    
        
    </div>
    b.- Fuentes de ingreso:
    <div class="row">    
       
        
    </div>

</div>
<br>
5. Salúd de la persona con celiaquía:
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">       
    <div class="row">     
        <div class="col-md-2"> 
            <?php if ($model->tiene_obra_social==0){$model->tiene_obra_social="no";} else {$model->tiene_obra_social="si";} ?>                            
            <?= $form->field($model, 'tiene_obra_social')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("a.¿Tiene obra social? ")?>   
        </div> 
        <div class="col-md-2">             
            <?= $form->field($model, 'obra_social')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("¿Cual? ")?>   
        </div> 
        <div class="col-md-3">             
            <?= $form->field($model, 'establecimiento_salud')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("b.¿Establecimiento de salud al que concurre? ")?>   
        </div>                      
    </div>
    c.- En relación a la enfermedad:
    <div class="row">           
        <div class="col-md-2"> 
            <?php if ($model->tiene_biopsia==0){$model->tiene_biopsia="no";} else {$model->tiene_biopsia="si";} ?>                                        
            <?= $form->field($model, 'tiene_biopsia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("1.¿Tiene biopsia? ")?> 
        </div>  
        <div class="col-md-2"> 
            <?php
                $model->fecha_diagnostico = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_diagnostico)));
            ?>            
            <?= $form->field($model, 'fecha_diagnostico')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Fecha de diagnóstico")?> 
        </div> 
        <div class="col-md-3"> 
            <?php if ($model->concurre_a_control==0){$model->concurre_a_control="no";} else {$model->concurre_a_control="si";} ?>                                        
            <?= $form->field($model, 'concurre_a_control')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("2.¿Concurre a control médico? ")?> 
        </div> 
        <div class="col-md-3"> 
            <?php if ($model->frecuencia==0){$model->frecuencia="anual";} else if ($model->frecuencia==1){$model->frecuencia="cada 2-3 años";}else if ($model->frecuencia==2){$model->frecuencia="cada 3 años o más";} ?>                                        
            <?= $form->field($model, 'frecuencia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("¿Con que frecuenciao? ")?> 
        </div> 
    </div>
    <div class="row"> 
        <div class="col-md-4"> 
            <?php if ($model->integrante_celiaco==0){$model->integrante_celiaco="no";} else {$model->integrante_celiaco="si";} ?>                                        
            <?= $form->field($model, 'integrante_celiaco')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("d.¿En el grupo familia, hay algun otro/a integrante con enfermedad celiaca? ")?> 
        </div>  
    </div>
            <?php
                $mostrar_biopsia=false;
                if ($model->estudio_biopsia!=null)
                {
                    $exists3 = file_exists( 'uploads/atpcen/'.$model->estudio_biopsia );
                    if ($exists3){$mostrar_biopsia=true;} else { }
                    
                }                
                
            ?>
    <div class="row" style="height:500px; display:<?= $mostrar_biopsia ? 'block' : 'none' ?> " >     
        <div class='col-md-12' align="center";> <br>
            IMAGEN BIOPSIA   
            <img  style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='uploads/atpcen/<?php  echo $model->estudio_biopsia;?>' />                   
            <br>
        </div>   
    </div> 
</div>
<br>
6. En relación a la alimentación
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">       
    <div class="row">     
    <?php
    $tipos_alimentacion = Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ALIMENTACION);
    $risneu_alims = Sds_ris_risneu_alimentacion::find()->where(['idrisneu' => $model->id_risneu])->all();
    foreach ($tipos_alimentacion as $tipo_alim) 
    {
        $checked = "";
        foreach ($risneu_alims as $ris_alim) 
        {
            if ($ris_alim->alimentacion == $tipo_alim->idconfiguracion)
             {
                $checked = "checked";
                break;
            }
        }
        echo "<div class='col-md-3'>";
        echo '<div class="form-group ">' .
            '<input type="checkbox" tabindex="1" 
                name="Sds_ris_risneu[tipo_alim][]" value=' . $tipo_alim->idconfiguracion . ' ' . $checked . ' readonly="readonly"  onclick="javascript: return false;"/> 
                <label>' . $tipo_alim->descripcion . '</label>' .
            '<div class="help-block"></div>' .
            '</div>';

        echo "</div>";
    }
    if ($model->tarjeta_atpcen==0){$check_tarjeta="";}else{$check_tarjeta=" checked ";}
    echo "<div class='col-md-3'>";
        echo '<div class="form-group ">' .
            '<input type="checkbox" tabindex="1" 
                name="tarjeta_atpcen" value=' . $model->tarjeta_atpcen . ' ' . $check_tarjeta . ' readonly="readonly"  onclick="javascript: return false;"/> 
                <label>6. Tarjeta para celiacos (ATPCN)</label>' .
            '<div class="help-block"></div>' .
            '</div>';

        echo "</div>";
    if ($model->modulo_alimento==0){$check_modulo="";}else{$check_modulo=" checked ";}
    echo "<div class='col-md-3'>";
        echo '<div class="form-group ">' .
            '<input type="checkbox" tabindex="1" 
                name="modulo_alimento" value=' . $model->modulo_alimento . ' ' . $check_modulo . ' readonly="readonly"  onclick="javascript: return false;"/> 
                <label>7. Módulos de alimentos</label>' .
            '<div class="help-block"></div>' .
            '</div>';

        echo "</div>";
    ?>               
    </div>
    <div class="row">  
        <div class="col-md-4">             
            <?= $form->field($model, 'organismo_asiste')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("¿Que organismo lo/la asiste?")?>   
        </div> 
        <div class="col-md-4">             
            <?= $form->field($model, 'cantidad_asistencia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Cantidad")?>   
        </div> 
        <div class="col-md-4">   
            <?php
            if ($model->periocidad_asistencia==0){$model->periocidad_asistencia='diario';}
            else{if ($model->periocidad_asistencia==1){$model->periocidad_asistencia='semanal';}
            else{if ($model->periocidad_asistencia==2){$model->periocidad_asistencia='mensual';}
            else{if ($model->periocidad_asistencia==3){$model->periocidad_asistencia='bimensual';}
            else{if ($model->periocidad_asistencia=4){$model->periocidad_asistencia='semestral';}
            else{if ($model->periocidad_asistencia=5){$model->periocidad_asistencia='anual';}
            else{if ($model->periocidad_asistencia=6){$model->periocidad_asistencia='otro';}}}}}}}
            ?>
        
            <?= $form->field($model, 'periocidad_asistencia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Periocidad")?>   
        </div> 
    </div>
</div>
<br>

7. Capacitación/talleres
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">       
    <div class="row"> 
        <div class="col-md-12">             
            a.- En relación a la salud y alimentación: ¿ Le interesa recibir informaciónm sobre alimentación sana libre de TACC (preparación), distintos usos de los alimentos secos y frescos, preparación de la huerta familiar o comunitaria?            
        </div> 
    </div>
    <div class="row"> 
        <div class="col-md-2">                         
            <?php if ($model->interes_capacitacion==0){$model->interes_capacitacion="no";} else {$model->interes_capacitacion="si";} ?>                            
            <?= $form->field($model, 'interes_capacitacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label(false)?>   
        </div> 
    </div>
    <div class="row">  
        <div class="col-md-8"> 
            <?= $form->field($model, 'capacitacion_solicitada')->textarea(['rows' => 6,"readOnly"=>true])->label("Capacitaciones solicitadas:") ?>
        </div>                                                                                                                   
    </div>
</div>
<br>
8. Observaciones.- Datos a ser completados por el encuestador
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  
    <div class="row">  
        <div class="col-md-8"> 
            Considera que el titular del beneficio se encuentra en situación de vulnerabilidad social?:
        </div>                                                                                                                   
    </div>  
    <div class="row"> 
        <div class="col-md-2">                         
            <?php if ($model->vulnerabilidad_social==0){$model->cad_vulnerabilidad="no";} else {$model->cad_vulnerabilidad="si";} ?>                            
            <?= $form->field($model, 'cad_vulnerabilidad')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label(false)?>   
        </div> 
    </div>     
    <div class="row"> 
        <div class="col-md-12">             
            Datos considerados pertinentes:  
        </div> 
    </div>    
    <div class="row">    
        <div class="col-md-8"> 
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6,"readOnly"=>true])->label(false) ?>
        </div>                                                                                                                   
    </div>
</div>
</div>
<div class="row" style="padding-top: 2%">
            <div class="col-md-1">            
                            <!--<a class="btn btn-info" href="javascript:history.back(1)">Volver </a>-->
                            <a class="btn btn-info" href="mds/web/index.php?r=mds_atpcen_encuesta">Volver </a>
            </div>            
        </div>
<?php ActiveForm::end(); ?>

</div>
            </section>
        </div>
    </div>  