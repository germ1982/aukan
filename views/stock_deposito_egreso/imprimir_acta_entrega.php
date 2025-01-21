<?php

use app\controllers\Stock_deposito_egreso_detalleController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use app\models\StockDepositoEgreso;
use app\models\StockDepositoEgresoDetalle;

$idegreso = $_GET['idegreso'];
$model = StockDepositoEgreso::findOne($idegreso);

function crear_linea($label, $contenido)
{
    echo "<br><b> $label: </b><span style='font-size: 12px;'>$contenido</span>";
}

function crear_label_articulo($articulo, $cantidad, $unidad)
{
    echo "<div class='col-xs-12' style='width: 100%'><ul><li><span style='font-size: 11px;'>$articulo: <b>$cantidad $unidad</b></span></li></ul></div>";
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
} ?>

<html>

<body>

    <img src="img/membrete_subsecretaria_familia_2025.png" width="100%" alt="Subsecretaría de Desarrollo Social">

    <br><br>
    <div style="text-align: center;">
        <h5><b>ACTA ENTREGA DE INSUMOS DE DEPOSITO</b></h5>
    </div>

    <div class="row" style='padding-left: 50px;'>
        <?= crear_linea('Fecha', date('d/m/Y', strtotime($model->fecha))); ?>
    </div>
    <hr>


    <div class="row" style="margin-top: -20px;">
        <div class="col-xs-6" style='padding-left: 50px;'>
            <?php
            $persona = Persona::findOne($model->idpersona_solicitante);
            $config = Configuracion::findOne($persona->documento_tipo);
            $aux = "$persona->apellido, $persona->nombre";
            crear_linea('Solicitante', $aux);

            ?>
        </div>

        <div class="col-xs-4">
            <?php
            $aux = "$config->descripcion $persona->documento";
            crear_linea('Documento', $aux);

            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" style='padding-left: 50px;'>
            <?= $model->id_dispositivo_destino ? crear_linea('Destino',OrganismoDispositivo::get_dispositivo($model->id_dispositivo_destino)->descripcion):''?>
        
        </div>
    </div>




    <hr style='margin-bottom: 1px'>
    <div style='padding-left: 35px;'>
        <?php crear_linea('Articulos', ''); ?>
    </div>


    <div class="row">

        <?php

        $consulta = "   SELECT e.iddetalle,
                            a.idarticulo,
                            concat( ct.descripcion ,' ', cm.descripcion ,' ' ,a.modelo ,' ' , a.descripcion) as descripcion,
                            e.cantidad,
                            cum.descripcion as unidad_medida
                        FROM stock_deposito_egreso_detalle e 
                        JOIN articulo a on e.idarticulo = a.idarticulo
                        join configuracion ct on ct.id_configuracion=a.idtipo
                        join configuracion cm on cm.id_configuracion=a.idmarca
                        join configuracion cum on cum.id_configuracion=a.id_unidad_medida
                        WHERE e.idegreso = $model->idegreso 
                        order by ct.descripcion,cm.descripcion,a.modelo,cum.descripcion,a.descripcion";

        //$articulos = Articulo::findBySql($consulta)->all();

        $articulos = StockDepositoEgresoDetalle::findBySql($consulta)->all();

        $ban = 1;
        echo "<br>";
        echo "<div class='col-xs-12' style='padding-left: 25px;'>";

        foreach ($articulos as $a) {
            crear_label_articulo("$a->descripcion por $a->unidad_medida", $a->cantidad, '');
        }
        echo "</div>";

        ?>

        <div style='padding-left: 50px;'>
            <?php $model->observacion ? crear_linea('Observaciones', $model->observacion) : ''; ?>
        </div>

    </div>
    <hr>
    <div style="padding-left: 45px; padding-right: 45px;">
        <div class="row">

            <div class="col-xs-5" style='text-align: left;'>
                <?php
                $idpersona = Empleado::findOne($model->idempleado_autorizacion)->idpersona;
                $persona = Persona::findOne($idpersona);
                crear_linea('Autorización', "$persona->apellido $persona->nombre");
                ?>
            </div>
            <div class="col-xs-2" style='text-align: center; padding-top: 30px'>
                ................<br>FIRMA
            </div>
            <div class="col-xs-2" style='text-align: center;padding-left:60px;padding-top: 30px'>
                ................<br>SELLO
            </div>
        </div>
        <hr>
        <div class="row">

            <div class="col-xs-5" style='text-align: left;'>
                <?php
                $idpersona = Empleado::findOne($model->idempleado_despacha)->idpersona;
                $persona = Persona::findOne($idpersona);
                crear_linea('Entrega', "$persona->apellido $persona->nombre");
                ?>
            </div>
            <div class="col-xs-2" style='text-align: center; padding-top: 30px'>
                <!-- ................<br>FIRMA -->
            </div>
        </div>
        <hr>
        <div class="row">

            <div class="col-xs-5" style='text-align: left;'>
                <?php
                //$idpersona = Empleado::findOne($model->idempleado_despacha)->idpersona;
                $persona = Persona::findOne($model->idpersona_recibe);
                crear_linea('Retira', "$persona->apellido $persona->nombre");
                ?>
            </div>
            <div class="col-xs-2" style='text-align: center; padding-top: 30px'>
                ................<br>FIRMA
            </div>

        </div>
    </div>

    <hr>


</body>

<footer style="position: fixed; left: 0;bottom: 0px;width: 100%; font-size:8px">
    <div class="row">
        <div class="col-xs-12" style="text-align: center;">
            <p>
                Direccion General de Deposito y Comunicaciones <br> Telefono: 449-8989
            </p>
        </div>
    </div>
</footer>

</html>