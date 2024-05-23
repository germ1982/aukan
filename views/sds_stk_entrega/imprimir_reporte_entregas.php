<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_recepcion;

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
}

$consulta_responsables = "";
if (is_array($responsable)) {
    $responsables = array();
    foreach ((array)$responsable as $resp) {
        array_push($responsables, "'" . $resp . "'");
    }
    $responsable_filter = implode(",", $responsables);
} else {
    $responsable_filter = $responsable;
}

if ($responsable_filter) {
    $consulta_responsables = ' idcontacto in (' . $responsable_filter . ')';
}

$contactos_resp = $consulta_responsables ? Mds_org_contacto::find()->where($consulta_responsables)->all() : "";

if($id_orden_compra)
{
    $orden_compra = Sds_stk_orden_compra::findOne($id_orden_compra);
    $proveedor = Sds_com_configuracion::findOne($orden_compra->proveedor);
    $info_header = "Orden de Compra $orden_compra->numero Fecha: ".date_format(date_create($orden_compra->fecha_emision), "d/m/Y") ." Expediente: $orden_compra->expediente Proveedor: $proveedor->descripcion";
}
else
{
    $info_header = "Desde: ".date_format(date_create($fecha_desde), "d/m/Y")."- Hasta: ".date_format(date_create($fecha_hasta), "d/m/Y");
}

if($idrecepcion)
{
    $recepcion = Sds_stk_recepcion::findOne($idrecepcion);
    $aux = $recepcion->expediente ? "Expediente $recepcion->expediente":"";
    $info_header = "Entregas de Recepcion numero $idrecepcion $aux";

}

?>
<html>

<body>
    <img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
    <br><br>
    <div style="text-align: center;">
        <h2><b>REPORTE DE ENTREGAS</b></h2>
        <h3><b><?=$info_header?></b></h3>
        <?php if ($contactos_resp) : ?>
            <h5><b>Responsables:
                    <?php
                    foreach ($contactos_resp as $contacto) {
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        echo " | $persona->apellido, $persona->nombre";
                    }
                    ?>
                </b>
            </h5>
        <?php endif; ?>
    </div>
    <?= $tabla ?>
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