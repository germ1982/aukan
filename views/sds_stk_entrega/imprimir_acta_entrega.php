<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use yii\helpers\Html;
use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_orden_compra;

$identrega = $_GET['identrega'];
$model = Sds_stk_entrega::findOne($identrega);




//esto siguiente busca si imprime la fecha segun el organismo...
$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$id_organismo = $usuario ? $usuario->organismo_stock : 0;
$imprime_fecha = $id_organismo ? Mds_org_organismo::findOne($id_organismo)->imprime_fecha_acta : 0;

//esto siguiente por si tiene orden de compra
$consulta = "SELECT oc.idordencompra, oc.numero as numero
            FROM sds_stk_entrega_item ei
            join sds_stk_recepcion_item ri on  ei.recepcion_item = ri.idrecepcionitem
            join sds_stk_orden_compra_item oci on ri.idordencompraitem = oci.idordencompraitem
            join sds_stk_orden_compra oc on oc.idordencompra = oci.idordencompra
            where ei.identrega = $identrega";
$orden_compra = Sds_stk_orden_compra::findBySql($consulta)->one();

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

    <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">

    <br><br>
    <div style="text-align: center;">
        <h5><b>ENTREGA <?=$model->referente ? 'INTERMEDIA' : 'FINAL'?></b></h5>
    </div>

    <?php 
        $imprime_fecha ? crear_linea('Fecha', date('d/m/Y')) : ''; 
        $orden_compra ? crear_linea('Orden de Compra', $orden_compra->numero):"";
        //crear_linea('Orden de Compra', $orden_compra->numero);
    ?><br>
    <hr>
    <?php crear_titulo_recuadro('SOLICITANTE', 4); ?>

    <div class="row">
        <div class="col-xs-6" style='padding-left: 50px;'>
            <?php
            $persona = Sds_com_persona::findOne($model->idpersona);
            $config = Sds_com_configuracion::findOne($persona->documento_tipo);
            $aux = "$persona->apellido, $persona->nombre";
            crear_linea('Persona', $aux);
            
            if(Yii::$app->user->identity->organismo_stock!=Mds_seg_usuario::ORG_STK_INFORMATICA){
                $aux = $persona->domicilio_calle ? $persona->domicilio_calle : "...................................";
                $aux = $persona->domicilio_numero ? "$aux al $persona->domicilio_numero" : $aux;
                crear_linea('Calle', $aux);

                $aux = "...................................";
                crear_linea('Telefono', $aux);
            }
            ?>
        </div>

        <div class="col-xs-4">
            <?php
            $aux = "$config->descripcion $persona->documento";
            crear_linea('Documento', $aux);

            if(Yii::$app->user->identity->organismo_stock!=Mds_seg_usuario::ORG_STK_INFORMATICA){
                $aux = $persona->idlocalidad ? Sds_com_localidad::findOne($persona->idlocalidad)->descripcion : "...................................";
                //$aux = Sds_com_localidad::findOne($persona->idlocalidad)->descripcion;
                crear_linea('Localidad', $aux);
            }
            ?>
        </div>
    </div>



    <hr>
    <?php crear_titulo_recuadro('Articulos', 4); ?>

    <div class="row">

        <?php
        /* $consulta = "SELECT a.descripcion as articulo,  ei.cantidad as cantidad, c.descripcion as unidad_medida
                                    FROM sds_stk_entrega_item ei 
                                    INNER JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                                    INNER JOIN sds_stk_articulo a on ri.idarticulo = a.idarticulo
                                    INNER JOIN sds_com_configuracion c on a.unidad_medida = c.idconfiguracion
                                    WHERE ei.identrega = $model->identrega 
                                    order by a.descripcion;"; */
        
        $consulta = "SELECT a.descripcion as articulo,  ei.cantidad as cantidad, c.descripcion as unidad_medida
                                    FROM sds_stk_entrega_item ei 
                                    INNER JOIN sds_stk_articulo a on ei.idarticulo = a.idarticulo
                                    INNER JOIN sds_com_configuracion c on a.unidad_medida = c.idconfiguracion
                                    WHERE ei.identrega = $model->identrega 
                                    order by a.descripcion;";
        $articulos = Sds_stk_entrega_item::findBySql($consulta)->all();

        $ban = 1;
        echo "<br>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";
        foreach ($articulos as $a) {
            crear_label_articulo($a->articulo, $a->cantidad, $a->unidad_medida);
        }
        echo "</div>";
        echo "<div class='col-xs-12' style='padding: 0px;'>";
            ($model->observaciones!=''?
                crear_label_articulo('Observaciones', $model->observaciones, '')
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
        $idpersona = Mds_org_contacto::findOne($model->idcontacto)->idpersona;
        $persona = Sds_com_persona::findOne($idpersona);
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
                            if ($model->persona_retira != null) {
                                $persona = Sds_com_persona::findOne($model->persona_retira);
                                echo "<span style='font-size: 11px;'>$persona->apellido $persona->nombre</span>";
                                echo "<br>DNI: <span style='font-size: 11px;'>$persona->documento</span><br>";
                            }
                            ?>
                </div>
                <div class="col-xs-5" style='text-align: left;padding-left:50px;'>
                    ENTREGA: <?php
                            if ($model->contacto_entrega != null) {
                                $idpersona = Mds_org_contacto::findOne($model->contacto_entrega)->idpersona;
                                $persona = Sds_com_persona::findOne($idpersona);
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