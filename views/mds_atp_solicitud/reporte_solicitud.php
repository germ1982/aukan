<?php

$id = $_GET['id'];



$query = new yii\db\Query;
$query->from(["mds_atp_solicitud"])
	->where(["id" => $id]);

$command = $query->createCommand();
$solicitud_datos = $command->queryOne();


$tienefotodni= ($solicitud_datos['foto_dni'] != null);
$tienefotodnidorso= ($solicitud_datos['foto_dnidorso'] != null) ;
$tienefotocertificado= ($solicitud_datos['foto_certificado'] != null);     
$tienetutorfotodni=  ($solicitud_datos['tutor_foto_dni'] != null) ;
$tienetutorfotodnidorso= ($solicitud_datos['tutor_foto_dnidorso'] != null);


 
function CalculaEdad( $fecha ) {list($Y,$m,$d) = explode("-",$fecha);return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );}
$fecha = $solicitud_datos['fecha_nacimiento'];
$edad=CalculaEdad( $fecha );
$anio = substr($fecha, 0, 4);
$mes  = substr($fecha, 5, 2);
$dia = substr($fecha, 8, 2);
$fecha = "$dia/$mes/$anio";
?>
<html>
<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
	</div>
<h1>DATOS BENEFICIARIO ATPCen</h1>
<table>
        <tbody>
          <tr>
            <td class="service"><b>Tipo de Documento </b> <?php echo $solicitud_datos['tipo_documento']; ?> </td>
			<td class="desc"><b>Documento </b> <?php echo $solicitud_datos['documento']; ?></td>
            <td class="desc"><b>Cuil </b><?php echo $solicitud_datos['cuil']; ?> </td>            
		  </tr>
		  <tr>
            <td class="service"><b>Nombre </b><?php echo $solicitud_datos['nombre']; ?></td>
			<td class="desc"><b>Apellido </b><?php echo $solicitud_datos['apellido']; ?> </td> 
			<td class="desc"><b>Código Interno </b><?php echo $solicitud_datos['id']; ?> </td>           
		  </tr>
		  <tr>

			<?php $sexo=$solicitud_datos['sexo']; if ($sexo=='m'){$sexo='masculino';} else {$sexo='femenino';}   ?>
			<td class="service"><b>Sexo </b><?php echo $sexo; ?></td>
			<?php  $fn= $solicitud_datos['fecha_nacimiento'];  $fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-',  $fn))); ?> 
			<td class="desc"><b>Fecha de Nacimiento </b><?php echo $fecha_nacimiento ; ?></td>
			<td class="desc">
			<blockquote class="blockquote" id="blockedad">
				<p><?php 
						if ($fn != null)
						{ $edad=CalculaEdad($fn); 
							echo 'Edad: '; if ($edad==1){echo $edad.' año';}else{echo $edad.' años';}									
						} else { $edad=110;}                                      
					?></p> 
					<?php if ($edad<18){ echo ' Se requiere un tutor'; }
						else { echo ' No se requiere tutor'; }
					?> </blockquote>   
			</td>
		  </tr>
		  <tr>
            <td class="service"><b>Telefono </b> <?php echo $solicitud_datos['telefono']; ?> </th>
			<td class="desc"><b>Telefono Alternativo </b> <?php echo $solicitud_datos['telefono_alternativo']; ?></th>
            <td class="desc"><b>Email </b><?php echo $solicitud_datos['email']; ?> </th>            
		  </tr>
		  <tr> 
			<td class="service"><b>Estado </b><?php echo $solicitud_datos['estado']; ?></td>
			<td class="desc"><b>Localidad </b><?php echo $solicitud_datos['localidad']; ?></td>	
			<td></td>
		  </tr>
		  <tr> 
			<td class="service" colspan='3'><b>Direccion </b><?php echo $solicitud_datos['direccion']; ?></td>		
		  </tr>
		  <tr> 
			<?php $cargafam=$solicitud_datos['carga_grupo_familiar']; if ($cargafam=='6'){$cargafam='6 o mas';} ?>
			<td class="service"><b>Carga Grupo familiar </b><?php echo $cargafam; ?></td>
			<?php $ingresofam=$solicitud_datos['ingreso_grupo_familiar']; 
					if ($ingresofam=='1'){$ingresofam='menos de $10000';}
					else if ($ingresofam=='2'){$ingresofam='entre $10000 y $20000';}
					else if ($ingresofam=='3'){$ingresofam='entre $20000 y $30000';}
					else if ($ingresofam=='4'){$ingresofam='entre $30000 y $40000';}
					else if ($ingresofam=='5'){$ingresofam='entre $40000 y $50000';}
					else if ($ingresofam=='6'){$ingresofam='más de $50000';}
					?>
			<td class="desc"  colspan="2"><b>Ingreso Grupo Familiar </b><?php echo $ingresofam; ?></td>	
		  </tr>
		  <?php    				
				if ( ($tienefotodni==false) || ($tienefotodnidorso == false))
				{  echo '<tr>';
					if ( $tienefotodni==false)
					{
						echo  '<td class="service"><b>Dni Frente </b> Sin foto </td>';
						if ( $tienefotodnidorso==false)
						{  echo  '<td class="desc"><b>Dni Dorso </b> Sin foto </td><td></td>';}
						else
						{ echo '<td class="desc"></td><td></td>';}
					}
					else
					{
						if ( $tienefotodnidorso==false)
						{  echo  '<td class="service"><b>Dni Dorso </b> Sin foto </td><td></td><td></td>';}
					}
					echo '</tr>';
				}
		  ?>		  
		</tbody>
				
</table>
<?php       
	if ( $tienefotodni || $tienefotodnidorso)
	{
		echo '
		<div style="text-align:center;">
			<table  style="margin: 0 auto;">';
			if ($tienefotodni)
			{ echo '<tr >
						<td td class="desc" style="background-color:#FFFFFF">DNI FRENTE <br>
							<img style="display:block;  width:70%;height:70%;"  src="'.$solicitud_datos['foto_dni'].'" />'; 
						echo '</td></tr>';
			}	
			if ($tienefotodnidorso)
			{ echo '<tr>
						<td td class="desc" style="background-color:#FFFFFF">DNI DORSO <br>   
							<img style="display:block; width:70%;height:70%;"  src="'.$solicitud_datos['foto_dnidorso'].'" />'; 							
							echo '</td></tr>';
			}
							 
		echo '									
			</table>
		</div>';		
	}

	if ($tienefotocertificado)
	{   
		echo '<img align=center style="display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100%;" id="base64image" src="'.$solicitud_datos['foto_certificado'].'" />'; 
	
	}

	if ($edad<18)
	{ 
		echo '<h1>DATOS TUTOR ATPCen</h1>';
		echo '<table>
						<tbody>
						<tr>
							<td class="service"><b>Tipo Documento </b>'; echo $solicitud_datos['tutor_tipo_documento']; echo '</td>
							<td class="desc"><b>Documento </b>'; echo $solicitud_datos['tutor_documento']; echo '</td>
							<td class="desc"><b>Cuil </b>';echo $solicitud_datos['tutor_cuil']; echo '</td>            
						</tr>
						<tr>
							<td class="service"><b>Nombre </b>'; echo $solicitud_datos['tutor_nombre']; echo '</td>
							<td class="desc"><b>Apellido </b>'; echo $solicitud_datos['tutor_apellido']; echo '</td> 
							<td class="desc"></td>           
						</tr>
						<tr>';
							$sexo=$solicitud_datos['tutor_sexo']; if ($sexo=='m'){$sexo='masculino';} else {$sexo='femenino';}   
							echo '<td class="service"><b>Sexo </b>'; echo $sexo; echo '</td>';
							$fn= $solicitud_datos['tutor_fecha_nacimiento'];  $fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-',  $fn)));  
							echo '<td class="desc"><b>Fecha Nacimiento </b>';echo $fecha_nacimiento ; echo '</td>';
							echo '<td class="desc"><b>Parentesco </b>';echo $solicitud_datos['tutor_parentesco'];echo '</td>';
						echo '</tr>';						  				
						if ( ($tienetutorfotodni==false) || ($tienetutorfotodnidorso == false))
						{  echo '<tr>';
							if ( $tienetutorfotodni==false)
							{
								echo  '<td class="service"><b>Dni Tutor Frente </b> Sin foto </td>';
								if ( $tienetutorfotodnidorso==false)
								{  echo  '<td class="desc"><b>Dni Dorso </b> Sin foto </td><td></td>';}
								else
								{ echo '<td class="desc"></td><td></td>';}
							}
							else
							{
								if ( $tienetutorfotodnidorso==false)
								{  echo  '<td class="service"><b>Dni Tutor Dorso </b> Sin foto </td><td></td><td></td>';}
							}
							echo '</tr>';
						}
					echo '							
						</tbody>								
				</table>';				
								
				if ( $tienetutorfotodni || $tienetutorfotodnidorso)
				{
					echo '
					<div style="text-align:center;">
						<table  style="margin: 0 auto;">';
						if ($tienetutorfotodni)
						{ echo '<tr >
									<td td class="desc" style="background-color:#FFFFFF">DNI TUTOR FRENTE <br>
										<img style="display:block;  width:70%;height:70%;"   src="'.$solicitud_datos['tutor_foto_dni'].'" />'; 
									echo '</td></tr>';
						}	
						if ($tienetutorfotodnidorso)
						{ echo '<tr>
									<td td class="desc" style="background-color:#FFFFFF">DNI TUTOR DORSO <br>   
										<img style="display:block; width:70%;height:70%;"   src="'.$solicitud_datos['tutor_foto_dnidorso'].'" />';											
										echo '</td></tr>';
						}										 
					echo '									
						</table>
					</div>';		
				}								
	} 

?>


</body>
</html>