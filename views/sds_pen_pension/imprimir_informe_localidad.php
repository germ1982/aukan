<?php
use app\models\Sds_com_persona;
use yii\helpers\Html;
use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_pen_pension;
use yii\grid\GridView;
use app\models\Mds_seg_usuario;
use app\models\Sds_reg_movimiento;
use app\models\Sds_reg_registro;
use yii\data\ActiveDataProvider;

$idlocalidad = $_GET['idlocalidad'];
$idbarrio=$_GET['idbarrio'];
$programa=$_GET['programa'];
$estado=$_GET['estado'];

$where = "";

if($idlocalidad)
    { $where = " where pen.idlocalidad = $idlocalidad";}

if($idbarrio)
    {
        if($where)
            {$where = "$where and idbarrio = $idbarrio";}
        else
            {$where = " where idbarrio = $idbarrio";}
    }

if($programa)
    {
        if($where)
            {$where = "$where and programa = $programa";}
        else
            {$where = " where programa = $programa";}
    }

if($estado)
    {
        if($where)
            {$where = "$where and estado = $estado";}
        else
            {$where = " where estado = $estado";}
    }

$sql = "SELECT pen.*, persona.apellido, persona.nombre 
    FROM sds_pen_pension pen INNER JOIN sds_com_persona persona ON pen.idpersona=persona.idpersona
    $where ORDER BY persona.apellido, persona.nombre";


function crear_linea($label,$contenido)
    {
        echo "<b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
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

        <br><br><div style="text-align: center;"><h5><b>INFORME DE PENSIONADOS LEY 809 </b></h5></div>

        <?php crear_linea('Fecha',date('d/m/Y'));?>
<hr>
        <div class="row">
            <div class="col-xs-7"> 
                <?php
                    if($idlocalidad) 
                        {
                            $model_localidad = Sds_com_localidad::findOne($idlocalidad);
                            $aux = "$model_localidad->descripcion";
                        }
                    else
                        {$aux = 'Todas';}
                    crear_linea('Localidad',$aux);

                    if($idbarrio) 
                        {
                            $model_barrio = Sds_com_barrio::findOne($idbarrio);
                            $aux = "$model_barrio->nombre";
                        }
                    else
                        {$aux = 'Todos';}
                    crear_linea('<br>Barrio',$aux)
                ?>
            </div>
            <div class="col-xs-3"> 
                <?php
                    if($programa) 
                        {
                            $model_programa = Sds_com_configuracion::findOne($programa);
                            $aux = "$model_programa->descripcion";
                        }
                    else
                        {$aux = 'Todos';}
                    crear_linea('Programa',$aux);

                    if($estado) 
                        {
                            $model_estado = Sds_com_configuracion::findOne($estado);
                            $aux = "$model_estado->descripcion";
                        }
                    else
                        {$aux = 'Todos';}
                    crear_linea('<br>Estado',$aux)
                ?>
            </div>
        </div>
<hr>
        <div class="row">
            <div class="col-xs-12">
                <?php
                        $style = 'font-size:10px; border: 1px solid #ccc;';
                        $dataProvider = new ActiveDataProvider(['query' => Sds_pen_pension::findBySql($sql),]);  
                        echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'summary' => '',
                                'id' => 'grilla_pensiones',
                                'columns' => [
                                    [
                                        'label' => 'Tip',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $persona = Sds_com_persona::findOne($model->idpersona);
                                            $config = Sds_com_configuracion::findOne($persona->documento_tipo);
                                            return "$config->descripcion";
                                        },
                                    ],
                                    [
                                        'label' => 'Numero',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $persona = Sds_com_persona::findOne($model->idpersona);
                                            return "$persona->documento";
                                        },
                                    ],
                                    [
                                        'label' => 'Apellido',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $persona = Sds_com_persona::findOne($model->idpersona);
                                            return "$persona->apellido";
                                        },
                                    ],
                                    [
                                        'label' => 'Nombres',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $persona = Sds_com_persona::findOne($model->idpersona);
                                            return "$persona->nombre";
                                        },
                                    ],
                                    [
                                        'label' => 'Legajo',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            return "$model->legajo";
                                        },
                                    ],
                                    [
                                        'label' => 'Programa',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $config = Sds_com_configuracion::findOne($model->programa);
                                            if($config)
                                                {
                                                    return "$config->descripcion";
                                                }
                                            else
                                                {
                                                    return "";
                                                }
                                        },
                                    ],
                                    [
                                        'label' => 'Fecha Norma',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $fc='';
                                            if($model->fecha_carga)
                                                {$fc = $model->fecha_carga;}
                                            if($model->fecha_otorgado)
                                                {$fc = $model->fecha_otorgado;}
                                            if($model->fecha_baja)
                                                {$fc = $model->fecha_baja;}
                                            $fc = date_create($fc);
                                            $fc = date_format($fc, 'd/m/Y');
                                            return $fc;
                                        },
                                    ],
                                    [
                                        'label' => 'Estado',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $config = Sds_com_configuracion::findOne($model->estado);
                                            if($config)
                                                {
                                                    return "$config->descripcion";
                                                }
                                            else
                                                {
                                                    return "";
                                                }
                                        },
                                    ],
                                    [
                                        'label' => 'Lugar de pago',
                                        'contentOptions' => ['style' => $style],
                                        'value' => function ($model) {
                                            $config = Sds_com_configuracion::findOne($model->lugar_pago);
                                            if($config)
                                                {
                                                    return "$config->descripcion";
                                                }
                                            else
                                                {
                                                    return "";
                                                }
                                        },
                                    ],
                                ],
                            ]);
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