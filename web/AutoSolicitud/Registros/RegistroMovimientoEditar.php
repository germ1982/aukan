<?php
	require_once '../../config/db.php';
	include_once('../Lib/FuncionesComunes.php');
?>

<!doctype html>
<html>
  <head>
		<link href="../Css/awesompleteOriginal.css" rel="stylesheet" type="text/css"/>
        <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
		<script src="../Lib/awesompleteO.min.js"></script>
		<script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
		<script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>

    <body onload="setcontroles()">
       <!--  <datalist id="ListaTecnicos"> <?php //GenerarListadoTecnicos() ?> </datalist> -->
        <h2>Editar Movimiento</h2>
        <hr>
        Fecha:
        <input type="date" id="InputFechaInicio" name="InputFechaInicio" autocomplete="off"/><br>
        
        Movimiento :<br>
        <textarea id="InputMovimientoDescripcion" name="InputMovimientoDescripcion" rows=3 placeholder="Descripcion del movimiento" style="width:800px;"></textarea>

        <br>Tecnico:
        <select id="InputTecnico">
            <?php GenerarListadoTecnicos(); ?>
        </select>
        <hr>
        <form id="FormIdIncidencia" method="post" action="SetMovimiento.php" onSubmit="return ValidarDatos();">
			<button type="button" name="Cancelar" onclick = "DestinoCancelar()">Cancelar</button>
			<button type="submit" name="Guardar">Guardar</button>
			<?php AbrirIncidencia(); ?>
		</form>

  </body>

</html>

<script type="text/javascript">

    function setcontroles()
		{
                MostrarFecha("InputFechaInicio", document.getElementById("VarFecha").value);
                document.getElementById("InputMovimientoDescripcion").value = document.getElementById("VarDescripcion").value;	
                document.getElementById("InputTecnico").value = document.getElementById("VarIdTecnico").value;
        }
    function DestinoCancelar()
		{
			var IdTipo = document.getElementById("VarRegistroIdTipo").value;
            var IdDerivador = document.getElementById("VarIdUsuario").value;
            var IdRegistro = document.getElementById("VarIdRegistro").value;
            var IncidenciaRelacionada = document.getElementById("VarIncidenciaRelacionada").value;
            if (IncidenciaRelacionada==1)
                {
                    location.href="Incidencia.php?idusuario="+IdDerivador + "&idregistro="+IdRegistro;
                }
            else
                {
                    location.href="RegistroEditar.php?VarTipo=" + IdTipo + "&idusuario="+IdDerivador + "&VarIdRegistro="+IdRegistro;
                }
        }
    function ValidarDatos()   
        {
            aux = document.getElementById("InputFechaInicio").value;//esta por lo general ya viene
				if (aux=="")
					{
						alert("Falta fecha");
						return false;
                    }
            aux = document.getElementById("InputMovimientoDescripcion").value;
                if (aux=="")
					{
						alert("Falta Descripcion del movimiento");
						return false;
                    }
            PrepararVariables();
        }
    function PrepararVariables()
        {
            document.getElementById("VarFecha").value = document.getElementById("InputFechaInicio").value;
            document.getElementById("VarIdTecnico").value = document.getElementById("InputTecnico").value;
            document.getElementById("VarDescripcion").value = document.getElementById("InputMovimientoDescripcion").value;
        }
</script>
<?php 
    function AbrirIncidencia()
        {
            require_once '../../config/db.php';
            include_once('../Lib/FuncionesComunes.php');
            $data = data_submitted();
            //print_object($data);
            $IdMovimiento = $data->VarIdMovimiento;
            echo "<br><input type='hidden' id='VarIdRegistro' name='VarIdRegistro' value='$data->VarIdRegistro'><br>";
            echo "<br><input type='hidden' id='VarIdMovimiento' name='VarIdMovimiento' value='$IdMovimiento'><br>";
            echo "<br><input type='hidden' id='VarIdUsuario' name='VarIdUsuario' value='$data->VarIdUsuario'><br>";
            echo "<br><input type='hidden' id='VarRegistroIdTipo' name='VarRegistroIdTipo' value='$data->VarRegistroIdTipo'><br>";
            echo "<br><input type='hidden' id='VarIncidenciaRelacionada' name='VarIncidenciaRelacionada' value='$data->VarIncidenciaRelacionada'><br>";

            $consulta = "select * from sds_reg_movimiento Where idmovimiento = $IdMovimiento";
            //echo $consulta; 
            $AuxDato="";
            $dbh = new BaseDatos();
            $dbh->Iniciar();
            $result = $dbh->Select($consulta);
            $result = $dbh->Registro();
            if (!$result) 
                {
                    echo "<p>Error en la consulta.</p>"; 
                }
            else 
                {	
                    $date = date_create($result['fecha']);
                    $AuxDato=date_format($date, 'd/m/Y');
                    echo "<input type='hidden' id='VarFecha' name='VarFecha' value='$AuxDato'><br>";

                    $AuxDato = $result["idtecnico"]; 
                    echo "<input type='hidden' id='VarIdTecnico' name='VarIdTecnico' value='$AuxDato'><br>";

                    $AuxDato = $result["descripcion"];
                    echo "<input type='hidden' id='VarDescripcion' name='VarDescripcion' value='$AuxDato'><br>";
                }	
            
            $dbh->Cerrar();
            $dbh = NULL;
        }
    function GenerarListadoTecnicos() 
        {
            include_once('../Lib/FuncionesComunes.php');
            //en la siguiente consulta se cambio a iditem=33 para que funcione en la base troncal de alla
            $consulta = "SELECT * FROM mds_seg_usuario WHERE idusuario IN (SELECT idusuario FROM mds_seg_usuario_rol WHERE idrol in (select idrol from mds_seg_permiso where iditem=33)) order by user";
            LlenarCombo($consulta, 'idusuario', 'user');	
        }
?>