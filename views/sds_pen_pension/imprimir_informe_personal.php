<?php
use app\models\Sds_com_persona;
use yii\helpers\Html;
use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_pen_pension;

$idpension = $_GET['idpension'];
$model = Sds_pen_pension::findOne($idpension);

function crear_linea($label,$contenido)
    {
        echo "<br><b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
    }

function crear_titulo_recuadro($label,$ancho)
    {
        echo"
        <div class='row'>
            <div class='col-xs-$ancho'> 
                <div style='border-radius: 5px; box-shadow: 5px 5px black;background-color: black;'>
                    <div style='border-radius: 5px;background-color: #c3c3c3; padding:3px;'>
                        $label
                    </div>
                </div>
            </div>
        </div>";
    }

    function get_direccion($model)
        {
            $direccion = "";

            if($model->idbarrio)
                {
                    $barrio = Sds_com_barrio::findOne($model->idbarrio);
                    $direccion = "Barrio $barrio->nombre";
                }

            if($model->calle)
                {
                    if(!($direccion==''))
                        {
                            $direccion = "$direccion, ";
                        }
                    if($model->numero)
                        {
                            $direccion = $direccion."Calle $model->calle, Numero $model->numero";
                        }
                    else
                        {
                            $direccion = $direccion."Calle $model->calle, Sin numero";
                        }
                }

            if($model->manzana)
                {
                    if(!($direccion==''))
                        {
                            $direccion = "$direccion, ";
                        }
                    $direccion = $direccion."Manzana $model->manzana";
                }
            
            if($model->casa)
                {
                    if(!($direccion==''))
                        {
                            $direccion = "$direccion, ";
                        }
                    $direccion = $direccion."Casa $model->casa";
                }

            if($model->lote)
                {
                    if(!($direccion==''))
                        {
                            $direccion = "$direccion, ";
                        }
                    $direccion = $direccion."Lote $model->lote";
                }
            if($model->departamento)
                {
                    if(!($direccion==''))
                        {
                            $direccion = "$direccion, ";
                        }
                    $direccion = $direccion."Departamento $model->departamento";
                }

            return $direccion;
        }

?>


<html>
    <body>

        <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">

        <br><br><div style="text-align: center;"><h5><b>INFORME DE PENSIONADO LEY 809 </b></h5></div>

        <?php crear_linea('Fecha',date('d/m/Y'));?><br><hr>
        <?php crear_titulo_recuadro('DATOS PERSONALES',4);?>

        <div class="row">
            <div class="col-xs-6" style='padding-left: 50px;'> 
                <?php
                    $persona = Sds_com_persona::findOne($model->idpersona);
                    $config = Sds_com_configuracion::findOne($persona->documento_tipo);
                    $aux = "$config->descripcion $persona->documento";
                    crear_linea('Documento',$aux);

                    $aux = "$persona->apellido, $persona->nombre";
                    crear_linea('Persona',$aux);

                    $fecha = date_format(date_create($persona->fecha_nacimiento), 'd/m/Y');
                    crear_linea('Fecha De Nacimiento',$fecha);

                    


                    
                    
                ?>
            </div>

            <div class="col-xs-4">
                <?php
                    $config = Sds_com_configuracion::findOne($persona->nacionalidad);
                    crear_linea('Nacionalidad',$config->descripcion);

                    crear_linea('Legajo RH',$model->legajo_rh);

                    $localidad = Sds_com_localidad::findOne($model->idlocalidad);
                    crear_linea('Localidad',$localidad->descripcion);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12" style='padding-left: 50px;'> 
                <?php
                    $direccion = get_direccion($model);
                    echo "<b> Direccion: </b><span style='font-size: 12px;'>$direccion</span>";
                ?>
            </div>
        </div>

        <hr>
        <?php crear_titulo_recuadro('DATOS DE PENSION',4);?>
        
        <div class="row">
            <div class="col-xs-6" style='padding-left: 50px;'> 
                <?php
                    $config = Sds_com_configuracion::findOne($model->programa);
                    crear_linea('Programa',$config->descripcion);

                    crear_linea('Legajo',$model->legajo);

                    $config = Sds_com_configuracion::findOne($model->estado);
                    crear_linea('Estado',$config->descripcion);

                    $fecha = date_format(date_create($model->fecha_carga), 'd/m/Y');
                    crear_linea('Fecha Carga',$fecha);
                ?> 
            </div>
            <div class="col-xs-4"> 
                <?php
                    crear_linea('Expediente',$model->expediente);

                    crear_linea('Resolucion',$model->resolucion);

                    $aux = $model->tramite_nacion;

                    if($aux==1)
                        {$aux = 'SI';}
                    else
                        {$aux = 'NO';}

                    crear_linea('Tramite Nacion',$aux);

                    if($model->lugar_pago)
                    {
                        $config = Sds_com_configuracion::findOne($model->lugar_pago);
                        $aux = $config->descripcion;

                    }
                    crear_linea('Lugar de Pago',$aux);

                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12" style='padding-left: 50px;'> 
                <?php
                    echo "<b> Notas: </b><span style='font-size: 12px;'>$model->notas</span>";
                ?>
            </div>
        </div>
        <hr>
            <?php crear_titulo_recuadro('DATOS DE OTORGAMIENTO',4);?>
            
            <div class="row">
                <div class="col-xs-6" style='padding-left: 50px;'> 
                    <?php

                        $fecha = date_format(date_create($model->fecha_otorgado), 'd/m/Y');
                        crear_linea('Fecha',$fecha);

                        crear_linea('Numero',$model->numero_otorgado);
                    ?> 
                </div>
                <div class="col-xs-4"> 
                    <?php
                        crear_linea('Año',$model->anio_otorgado);
                        $aux='';

                        if($model->tipo_otorgado)
                            {
                                $config = Sds_com_configuracion::findOne($model->tipo_otorgado);
                                $aux = $config->descripcion;

                            }
                        
                        crear_linea('Tipo',$aux);
                    ?>
                </div>
            </div>
            <hr>
            <?php crear_titulo_recuadro('DATOS DE BAJA',4);?>
            
            <div class="row">
                <div class="col-xs-6" style='padding-left: 50px;'> 
                    <?php

                        $fecha = date_format(date_create($model->fecha_baja), 'd/m/Y');
                        crear_linea('Fecha',$fecha);

                        crear_linea('Numero',$model->numero_baja);
                        $aux = $model->transferida;

                        if($aux==1)
                            {$aux = 'SI';}
                        else
                            {$aux = 'NO';}

                        crear_linea('Transferida',$aux);
                        
                    ?> 
                </div>
                <div class="col-xs-4"> 
                    <?php

                        crear_linea('Año',$model->anio_baja);
                        $aux='';

                        if($model->tipo_baja)
                            {
                                $config = Sds_com_configuracion::findOne($model->tipo_baja);
                                $aux = $config->descripcion;
                            }
                        crear_linea('Tipo Baja',$aux);

                        $aux='';

                        if($model->causa_baja)
                            {
                                $config = Sds_com_configuracion::findOne($model->causa_baja);
                                $aux = $config->descripcion;
                            }
                        crear_linea('Causa Baja',$aux);
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12" style='padding-left: 50px;'> 
                    <?php
                        $aux = $model->transferida;
                        if($aux==1)
                            {
                                if($model->persona_transferida)
                                    {
                                        $persona = Sds_com_persona::findOne($model->persona_transferida);
                                        $config = Sds_com_configuracion::findOne($persona->documento_tipo);
                                        $aux = "$persona->apellido, $persona->nombre, $config->descripcion $persona->documento";
                                        echo "<b> Persona Transferida: </b><span style='font-size: 12px;'>$aux</span>";
                                    }
                                
                            }
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12" style='padding-left: 50px;'> 
                    <?php
                        echo "<b> Oservaciones: </b><span style='font-size: 12px;'>$model->observaciones_baja</span>";
                    ?>
                </div>
            </div>


    </body>

    <footer style="position: fixed; left: 0;bottom: 0px;width: 100%; font-size:8px">
        <div class="row">
            <div class="col-xs-12" style="text-align: center;">
                <p>
                    Direccion General de Informatica y Comunicaciones <br> Telefono: 449-8989
                </p>
            </div>
        </div>
    </footer>
</html>

