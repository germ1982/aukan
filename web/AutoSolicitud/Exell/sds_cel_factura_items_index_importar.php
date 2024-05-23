<!DOCTYPE html>
<html lang="en">

<head>
	<!-- <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>	 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
        crossorigin="anonymous">
    <title>Convert excel to JSON Object</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <style>
        .button {
        width: 150px;
        }

          .button span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
        }

        .button span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
        }

        .button:hover span {
        padding-right: 25px;
        }

        .button:hover span:after {
        opacity: 1;
        right: 0;
        }
    </style>
</head>
<?php 
    include_once('../Lib/FuncionesComunes.php');
    $data = data_submitted();
    //print_object($data);
    echo "<div style='background-color:#EBEBEB;'>";
        $idfactura = $data->idfactura;
        echo "<input type='hidden' id='Varidfactura' name='Varidfactura' value='$idfactura'>";
        //echo "importar para factura id: $idfactura";
    echo "</div>"
?>
<body>

		<input type="file" id="input" accept=".xls,.xlsx" >
        <br><br>

		<button class="button"id="button"><span>Importar</span></button>

		<br><br><pre id="estado">Estado: esperando seleccion de archivo</pre>
		<hr>
		<pre id="jsondata"></pre>



</body>

<script src="sds_cel_factura_items_importar.js"></script>

</html>

  