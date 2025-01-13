<?php

use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\Localidades;
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use yii\helpers\Html;
use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Mds_seg_usuario;
use app\models\Persona;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_orden_compra;
use app\models\StockInformaticaEgreso;
use app\models\Usuarios;

$idegreso = $_GET['idegreso'];
$model = StockInformaticaEgreso::findOne($idegreso);

function crear_linea($label, $contenido)
{
    echo "<br><b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
}

function crear_label_articulo($articulo, $cantidad, $unidad)
{
    echo "<div class='col-xs-5' style='width: 45%'><ul><li><span style='font-size: 11px;'>$articulo: <b>$cantidad $unidad</b></span></li></ul></div>";
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

    <img src="img/membrete_subsecretaria_familia_2025.png" width="100%" alt="Subsecretaría de Desarrollo Social">

    <br><br>
    <div style="text-align: center;">
        <h5><b>ACTA ENTREGA DE INSUMOS INFORMATICOS</b></h5>
    </div>
    <br><br>

    <?=crear_linea('Fecha', $model->fecha);?>

        <br>
    <hr>
    <?php crear_titulo_recuadro('SOLICITANTE', 4); ?>

    <div class="row">
        <div class="col-xs-6" style='padding-left: 50px;'>
            <?php
            $persona = Persona::findOne($model->idpersona_solicitante);
            $config = Configuracion::findOne($persona->documento_tipo);
            $aux = "$persona->apellido, $persona->nombre";
            crear_linea('Persona', $aux);
            
            ?>
        </div>

        <div class="col-xs-4">
            <?php
            $aux = "$config->descripcion $persona->documento";
            crear_linea('Documento', $aux);

            ?>
        </div>
    </div>



    <hr>
    <?php crear_titulo_recuadro('Articulos', 4); ?>

    <div class="row">

        <?php
       
        $consulta = "   SELECT e.iddetalle,
                            a.idarticulo,
                            concat( ct.descripcion ,' ', cm.descripcion ,' ' ,a.modelo ,' ' , cum.descripcion ,' ', a.descripcion) as descripcion,
                            e.cantidad
                        FROM stock_informatica_egreso_detalle e 
                        JOIN articulo a on e.idarticulo = a.idarticulo
                        join configuracion ct on ct.id_configuracion=a.idtipo
                        join configuracion cm on cm.id_configuracion=a.idmarca
                        join configuracion cum on cum.id_configuracion=a.id_unidad_medida
                        WHERE e.idegreso = $model->idegreso 
                        order by ct.descripcion,cm.descripcion,a.modelo,cum.descripcion,a.descripcion";
        $articulos = Articulo::findBySql($consulta)->all();

        $ban = 1;
        echo "<br>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";

        foreach ($articulos as $a) {
            crear_label_articulo($a->descripcion, '5', 'a');
        }
        echo "</div>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";
            ($model->observacion!=''?
                crear_label_articulo('Observaciones', $model->observacion, '')
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
        $idpersona = Empleado::findOne($model->idempleado_autorizacion)->idpersona;
        $persona = Persona::findOne($idpersona);
        echo "<span style='font-size: 20px;'>$persona->apellido $persona->nombre</span>";
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
                            if ($model->idpersona_recibe != null) {
                                $persona = Persona::findOne($model->idpersona_recibe);
                                echo "<span style='font-size: 11px;'>$persona->apellido $persona->nombre</span>";
                                echo "<br>DNI: <span style='font-size: 11px;'>$persona->documento</span><br>";
                            }
                            ?>
                </div>
                <div class="col-xs-5" style='text-align: left;padding-left:50px;'>
                    ENTREGA: <?php
                            if ($model->idempleado_despacha != null) {
                                $idpersona = Empleado::findOne($model->idempleado_despacha)->idpersona;
                                $persona = Persona::findOne($idpersona);
                                echo "<span style='font-size: 11px;'>$persona->apellido $persona->nombre</span>";
                                echo "<br>DNI: <span style='font-size: 11px;'>$persona->documento</span><br>";
                            }
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