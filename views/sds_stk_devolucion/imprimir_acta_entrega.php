<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use yii\helpers\Html;
use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_devolucion;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_orden_compra;

$identrega = $_GET['identrega'];
$model = Sds_stk_entrega::findOne($identrega);




//esto siguiente busca si imprime la fecha segun el organismo...
/* $usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null; */


$devolucion = Sds_stk_devolucion::findOne($identrega);
$responsable_entrega = Mds_seg_usuario::findOne($devolucion->responsable_entrega);
$id_organismo_responsable_entrega = $responsable_entrega ? $responsable_entrega->organismo_stock : 0;
$destinatario_contacto = Mds_org_contacto::findOne($devolucion->destinatario);
$destinatario_persona = Sds_com_persona::findOne($destinatario_contacto->idpersona);

function crear_linea($label, $contenido)
{
    echo "<br><b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
}

function crear_label_articulo($articulo, $cantidad, $unidad)
{
    echo "<div class='col-xs-5' style='width: 45%'><ul><li><span style='font-size: 11px;'>$articulo: <b>$cantidad $unidad</b></span></li></ul></div>";
}

function crear_label_herramienta($herramienta)
{
    echo "<div class='col-xs-5' style='width: 45%'><ul><li><span style='font-size: 11px;'>$herramienta</span></li></ul></div>";
}

function crear_titulo_recuadro($label, $ancho)
{
    echo "
        <div class='row'>
            <div class='col-xs-$ancho'> 
                <div style='border-radius: 5px; box-shadow: 5px 5px black;background-color: black;'>
                    <div style='border-radius: 5px;background-color: #c3c3c3; padding:3px;'>
                        $label
                    </div>
                </div>
            </div>
        </div>";
}?>

<html>
<body>

    <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">

    <br><br>
    <div style="text-align: center;">
        <h5><b>ENTREGA </b></h5>
    </div>

    <?php 
        crear_linea('Fecha', date('d/m/Y', strtotime($devolucion->fecha_hora_entrega))) ; 
    ?><br>
    <hr>
    <?php crear_titulo_recuadro('SOLICITANTE', 4); ?>

    <div class="row">
        <div class="col-xs-6" style='padding-left: 50px;'>
            <?php    
                $aux = "$destinatario_persona->apellido, $destinatario_persona->nombre";
                crear_linea('Persona', $aux);
            ?>
        </div>

        <div class="col-xs-4">
            <?php
                $config = Sds_com_configuracion::findOne($destinatario_persona->documento_tipo);
                $aux = "$config->descripcion $destinatario_persona->documento";
                crear_linea('Documento', $aux);
            ?>
        </div>
    </div>



    <hr>
    <?php crear_titulo_recuadro('Herramienta', 4); ?>

    <div class="row">

        <?php
        $articulo = Sds_stk_articulo::findOne($devolucion->idarticulo);

        echo "<br>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";
            crear_label_herramienta($articulo->descripcion);
        echo "</div>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";
            ($devolucion->observaciones_entrega!=''?
                crear_label_articulo('Observaciones', $devolucion->observaciones_entrega, '')
                :null
            );
        echo "</div>";



        ?>

    </div>
    <hr>
    <br><br><br><br>
    <div style='border:1px solid black;padding:10px;border-radius: 5px;text-align: center;'>
        <strong>AUTORIZACION:</strong>
        <br>

        <?php
        echo "<span style='font-size: 20px;'>$responsable_entrega->apellido $responsable_entrega->nombre</span>";
        ?><br><br>

        <div class="row">
            <div class="col-xs-5" style='text-align: center;'>
                ....................................<br>FIRMA
            </div>
            <div class="col-xs-5" style='text-align: center;padding-left:60px;'>
                ....................................<br>SELLO
            </div>
        </div>
    </div>
    <br>
    <div style='padding-left:0px;text-align: center;border:1px solid black;border-radius: 5px;'>
        <strong>ENTREGA</strong><br>
        <div style='padding-left:50px;text-align: center'>
            <div class="row" style='text-align: justify; text-justify: inter-word;'>
                <div class="col-xs-5" style='text-align: left;'>
                    RETIRA: <?php

                                echo "<span style='font-size: 11px;'>$destinatario_persona->apellido $destinatario_persona->nombre</span>";
                                echo "<br>DNI: <span style='font-size: 11px;'>$destinatario_persona->documento</span><br>";
                            ?>
                </div>
                <div class="col-xs-5" style='text-align: left;padding-left:50px;'>
                    ENTREGA: <?php
                                echo "<span style='font-size: 11px;'>$responsable_entrega->apellido $responsable_entrega->nombre</span>";
                                echo "<br>DNI: <span style='font-size: 11px;'>$responsable_entrega->dni</span><br>";
                                ?>
                </div>
            </div>
        </div><br>
    </div>
    <br><br><br>
    <div style='border: 3px double black;padding:10px;text-align: center;'>
        <span style='font-size: 11px;'>
            Se informa que de acuerdo a lo reglamentado en el CAPITULO II, Art.81, de la Ley Administración financiera y Control N° 2141, Ud., dispone de 10 (diez) días para proceder a realizar la RENDICION, con fotocopoa de DNI legible y firma del beneficiario, de la mercaderia que le es entregada con la presente Acta.
        </span>
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