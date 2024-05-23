<!DOCTYPE html>
<html lang="en">

<head>
	<link href="../Css/Registros.css" rel="stylesheet" type="text/css" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<title>Convert excel to JSON Object</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
	<script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>
</head>

<body>
	<?php

	include_once('../Lib/FuncionesComunes.php');
	$data = data_submitted();
	//print_object($data);
	echo "<div style='background-color:#EBEBEB;'>";
	echo "<h2>";
	echo "Importar Excel con licencias";
	echo "</h2>";

	if (isset($data->idusuario)) {
		$UserId = $data->idusuario;
	} else {
		$UserId = 17;
	}

	echo "<input type='hidden' id='VarIdUsuarioCarga' name='VarIdUsuarioCarga' value='$UserId'>";

	$UserValue = getDatoPorId("mds_seg_usuario", "idusuario", "user", $UserId);
	//echo "<FONT SIZE=1>Usando: $UserValue</font>";
	echo "</div>"
	?>


	<hr>
	<input type="file" id="input" accept=".xls,.xlsx">
	<button id="button">Importar</button>
	<!-- <button type="button" name="Volver" onclick = "location='../index.php?r=mds_hor_licencia'" >Volver</button> -->
	<input type="button" onclick="history.back()" name="Volver" value="Volver">
	<br><br>
	<pre id="estado">Estado: esperando seleccion de archivo</pre>
	<hr>
	<pre id="jsondata"></pre>



</body>
<script src="excel.js"></script>

</html>