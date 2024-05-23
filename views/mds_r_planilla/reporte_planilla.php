<?php
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_plantilla;
use app\models\Sds_gis_capa;
use app\models\Mds_r_planilla;
use app\models\Mds_r_diagnostico;
use app\models\Mds_r_ejidos;
use app\models\Sds_gis_capa_item;

?>
<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">PLANILLA DE DIAGNOSTICOS</h4>					
		</div>
		<table>
			<tr style="background-color: #dddddd;">
				<th class="titulo" colspan="4">
					<h5>DATOS DE LA PLANILLA #<?= $model->idplanilla ?> </h5>
				</th>
			</tr>
			<tr>
				<td valign="top" colspan="4">
					<b>Organismo: </b><span></span>
					<?php
						$tipo = Sds_com_configuracion::findOne($model->idorganismo);
						echo $tipo->descripcion;    
					?>
					
				</td>
			</tr>
			<tr>
				<td valign="top" style="width: 10%">
					<b>Mes: </b><span></span>
					<?php						
						echo $model->mes;   
					?>
				</td>
				<td valign="top" style="width: 10%">
					<b>Año: </b></span>
					<?php
						echo $model->anio;   
					?>
				</td>
				<td valign="top" style="width: 30%">
					<b>Fecha de Registro: </b>
					<span>
						<?php
							$fc = date_create($model->fechacarga);
							$fc = date_format($fc, 'd/m/Y');
							echo $fc;
						?>
					</span>
				</td>
				<td valign="top" style="width: 25%">
					<b>Periodo: </b></span>
					<?php
					    if ($model->periodo==0) {$model->periodo="Mensual";}
						else 
						{
							if ($model->periodo==1)
							{
								$model->periodo="Trimestral";
							}
							else
							{
								if ($model->periodo==2)
								{
									$model->periodo="Semestral";
								}
								else
								{
									$model->periodo="Desconocido";
								}
							}																			
						}
						echo $model->periodo;   
					?>
				</td>
			</tr>	
			<tr>
				<td valign="top" colspan="4">
					<b>Total Variables Dimensión: </b><span></span>
					<?php

						$variables=Mds_r_variable_dimension::find()
									->where(['idplanilla' => $model->idplanilla]) 
									->andWhere(["activo" => 1])                       
									->all();
						echo count($variables); 
					?>					
				</td>						
			</tr>
		</table>
			<hr style="margin: 25px 0 0 0">
		<?php
				//print_r($variables);print_r("<br><br>");
			foreach ($variables as $una_variable) 
			{
				//print_r($una_variable);print_r("<br><br>");

				echo '
				<table>			
					<tr>
						<td valign="top" style="width: 50%" colspan="1">
							<b>Variable: </b><span></span>';													

								$tipo = Sds_com_configuracion::findOne($una_variable->idvariable);
								echo $tipo->descripcion;               
							
					echo'		
						</td>
					
						<td valign="top" style="width: 25%" colspan="2">
							<b>Dimensión: </b><span></span>';
							$tipo = sds_com_configuracion_tipo::findOne($una_variable->iddimension);
							echo $tipo->descripcion;    
						echo '	
							
						</td>												
					</tr>	
					<tr>
						<td valign="top" style="width: 25%" '; if ($una_variable->mapear){echo 'colspan="2"';} 
						echo '>';																											
							if ($una_variable->mapear) 
							{
								echo '<b>Mapear</b>';
					
							} 
							else {echo '<b>No Mapear</b>';} 
							 echo '<span></span>';
							if ($una_variable->mapear) 
							{
								echo '&nbsp;&nbsp;&nbsp;<b>Tipo de Mapa: </b><span></span>';
								$un_mapa=Sds_com_configuracion::find()
								->where(['idconfiguracion' => $una_variable->tipomapa])                        
								->one();   
								$una_variable->tipomapa=$un_mapa->descripcion;
								echo $una_variable->tipomapa;								
								
							}    
							
					echo'		
						</td>';																		
							
						echo '	
						<td valign="top" style="width: 60%">																											
							<b>Origen: </b><span></span>';
							//$tipo = sds_com_configuracion::findOne($una_variable->origen);
							//echo $tipo->descripcion;     														

							$plantilla = Mds_r_plantilla::find()->where(['idtipoplantilla' => $model->idplantilla])->asArray()->one();
           
							$cad_return="";    

							if ($plantilla['origen']== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
							{
									$gis_capa = Sds_gis_capa::find()->where(['idcapa' => $plantilla['id_gis_capa']])->asArray()->one();
									$cad_return=" Dispositivo -> ".$gis_capa['descripcion'];
									
								
							}    
							else
							{  $cad_return=" Localidades.";
							}                   
							
							echo $cad_return;
						echo'		
						</td>		
																	
					</tr>	';
				
					$all_diagnosticos=Mds_r_diagnostico::find()
					->where(['idvardimension' => $una_variable->idvardimension])   
					->andWhere(["activo" => 1])                       
					->all();

					echo '
					</table>';

					echo '
					

					<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8; display:block;" >
					Diagnósticos
					<div class="row">
						<div class="col-md-12">';														
						$i = 1;
						echo '<table class="table">
						<thead>
							<tr>
							<th scope="col">#</th>
							<th scope="col">Dispositivo</th>
							<th scope="col">Dimensión</th>
							<th scope="col">Valor</th>
							<th scope="col">Fecha/Hora carga</th>
							</tr>
						</thead>
						<tbody>';
						foreach ($all_diagnosticos as $un_diagnostico) {

							echo '<tr>';
							echo '<td>'.$i.'</td>';							
							echo '<td>';
							$result="";
							if ($una_variable->origen==Mds_r_variable_dimension::ORIGEN_LOCALIDADES)
							{  // BUSCAR EL EJIDO
								$result=( Mds_r_ejidos::findOne($un_diagnostico->idejido))->ejido;                         
							}
							else
							{
								if ($una_variable->origen==Mds_r_variable_dimension::ORIGEN_DISPOSITIVO)
								{
									$result=( Sds_gis_capa_item::findOne($un_diagnostico->iddispositivo))->descripcion;                        
								}
							}
				
							echo $result; 
							echo '</td>';	
							echo '<td>';
							$tipo = Sds_com_configuracion::findOne($un_diagnostico->valor_dimension);
							echo $tipo->descripcion;         
							echo '</td>';
							echo '<td>';
							echo $un_diagnostico->valor;
							echo '</td>';
							echo '<td>';

							$fecha=$un_diagnostico->fecha;   
							$sep = explode(' ',$un_diagnostico->fecha);  
							$fecha = explode('-',$sep[0]);  
							$hora = $sep[1];  
							$fecha_final = $fecha[2].'/'.$fecha[1].'/'.$fecha[0]; 

							echo $fecha_final.' '.$hora;
							echo '</td>';
							$i++;
							echo '</tr>';	
						}

						echo '</tbody>
						</table>
						</div>
					</div>
				</div>
				
				<br>';
						echo '<hr style="margin: 25px 0 0 0">';
	

        	}
		?>
		








	</div>
</body>

</html>