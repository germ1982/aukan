<?php

use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use app\models\StockInformaticaEgreso;
use app\models\StockInformaticaEgresoDetalle;

$idegreso = $_GET['idegreso'];

$model = StockInformaticaEgreso::findOne($idegreso);

$persona = Persona::findOne($model->idpersona_solicitante);

$config = Configuracion::findOne($persona->documento_tipo);

?>

<!DOCTYPE html>
<html lang="es">



<body style="
    font-family: Arial, sans-serif;
    font-size:11px;
    color:#000;
    margin:0;
    padding:0 25px;
">

<!-- MEMBRETE -->

<div style="text-align:center; margin-top:10px;">

    <img
        src="img/membrete_subsecretaria_familia_2025.png"
        width="100%"
    >

</div>

<div style="width:100%; text-align:right; margin-top:15px; font-size:12px; line-height:1.4;">
    
    <div>
        <b>ACTA N°</b>
        <?= str_pad($model->idegreso, 5, '0', STR_PAD_LEFT) ?>
    </div>

    <div>
        <b>Fecha:</b>
        <?= date('d/m/Y', strtotime($model->fecha)) ?>
    </div>

</div>
<!-- TITULO -->

<div style="
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin-top:25px;
    margin-bottom:25px;
">

    ACTA DE ENTREGA

</div>

<!-- TEXTO -->

<div style="
    font-size:12px;
    margin-bottom:20px;
    line-height:18px;
    text-align:justify;
">

    En la ciudad de Neuquén, se procede a realizar la entrega de los
    materiales que se detallan a continuación.

</div>
<?php

$empleadoSolicitante = Empleado::find()
    ->where(['idpersona' => $persona->idpersona])
    ->one();

?>
<!-- DATOS -->

<div style="
    font-size:14px;
    font-weight:bold;
    margin-bottom:10px;
">

    DATOS DE LA PERSONA QUE RECIBE

</div>

<table width="100%" cellpadding="3" cellspacing="0">

<tr>

    <td width="180">
        <b>Nombre y Apellido:</b>
    </td>

    <td>
        <?= $persona->apellido . ', ' . $persona->nombre ?>
    </td>

</tr>

<tr>

    <td>
        <b>DNI:</b>
    </td>

    <td>
        <?= $persona->documento ?>
    </td>

</tr>

<tr>

    <td>
        <b>Legajo:</b>
    </td>

    <td>
        <?= $empleadoSolicitante ? $empleadoSolicitante->legajo : '-' ?>
    </td>

</tr>

<?php if ($model->id_dispositivo_destino): ?>

<tr>

    <td>
        <b>Sector / Oficina:</b>
    </td>

    <td>
        <?= OrganismoDispositivo::get_dispositivo($model->id_dispositivo_destino)->descripcion ?>
    </td>

</tr>

<?php endif; ?>

</table>

<!-- DETALLE -->

<div style="
    font-size:14px;
    font-weight:bold;
    margin-top:20px;
    margin-bottom:10px;
">

    DETALLE DE LA ENTREGA

</div>

<?php

$consulta = "

SELECT
    e.cantidad,
    ct.descripcion as rubro,
    cm.descripcion as producto,
    CONCAT(a.modelo,' ',a.descripcion) as descripcion

FROM stock_informatica_egreso_detalle e

JOIN articulo a
ON a.idarticulo = e.idarticulo

JOIN configuracion ct
ON ct.id_configuracion = a.idtipo

JOIN configuracion cm
ON cm.id_configuracion = a.idmarca

WHERE e.idegreso = $model->idegreso

";

$articulos = Yii::$app->db->createCommand($consulta)->queryAll();

?>

<!-- TABLA -->

<table
    width="100%"
    cellpadding="4"
    cellspacing="0"
    style="
        border-collapse:collapse;
        margin-top:10px;
    "
>

<thead>

<tr>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        RUBRO
    </th>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        PRODUCTO
    </th>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        DESCRIPCIÓN
    </th>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        EXPEDIENTE
    </th>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        OC
    </th>

    <th style="
        border:1px solid #000;
        background:#DDDDDD;
        font-size:10px;
        text-align:center;
    ">
        CANT.
    </th>

</tr>

</thead>

<tbody>

<?php foreach($articulos as $a): ?>

<tr>

    <td style="
        border:1px solid #000;
        font-size:10px;
    ">
        <?= $a['rubro'] ?>
    </td>

    <td style="
        border:1px solid #000;
        font-size:10px;
    ">
        <?= $a['producto'] ?>
    </td>

    <td style="
        border:1px solid #000;
        font-size:10px;
    ">
        <?= $a['descripcion'] ?>
    </td>

    <td style="
        border:1px solid #000;
        font-size:10px;
        text-align:center;
    ">
        EX-2026
    </td>

    <td style="
        border:1px solid #000;
        font-size:10px;
        text-align:center;
    ">
        1792/2026
    </td>

    <td style="
        border:1px solid #000;
        font-size:10px;
        text-align:center;
    ">
        <?= $a['cantidad'] ?>
    </td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<!-- TEXTO FINAL -->

<div style="
    margin-top:20px;
    text-align:justify;
    line-height:18px;
    font-size:12px;
">

    La recepción de los materiales arriba detallados se realiza de plena conformidad,
    dejando constancia de que los mismos se encuentran en perfecto estado de
    conservación y funcionamiento.

</div>

<!-- FIRMAS -->

<?php

$idpersona2 = Empleado::findOne($model->idempleado_despacha)->idpersona;
$personaDespacha = Persona::findOne($idpersona2);

?>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:90px;">

<tr>

<!-- RECIBE -->
<td width="50%" align="center">

    <!-- LINEA FIRMA REAL (MPDF SAFE) -->
    <table width="260" cellpadding="0" cellspacing="0" style="margin:0 auto 8px auto;">
        <tr>
            <td style="border-top:1px solid #000;"></td>
        </tr>
    </table>

    <b>Firma Recibí Conforme</b><br>

    <?= $persona->apellido . ', ' . $persona->nombre ?><br>

    DNI: <?= $persona->documento ?>

</td>

<!-- ENTREGA -->
<td width="50%" align="center">

    <!-- LINEA FIRMA REAL (MPDF SAFE) -->
    <table width="260" cellpadding="0" cellspacing="0" style="margin:0 auto 8px auto;">
        <tr>
            <td style="border-top:1px solid #000;"></td>
        </tr>
    </table>

    <b>Firma Responsable Entrega</b><br>

    <?= $personaDespacha->apellido . ', ' . $personaDespacha->nombre ?><br>

    DNI: <?= $personaDespacha->documento ?>

</td>

</tr>

</table>

</body>

</html>