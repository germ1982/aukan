<?php

      include "conectarse.php";     
	  $bd = new baseDatos();
       
      //  Make connection to database 
      //  If no connection made, display error Message          
      $dblink = $bd->Conectarse();

      // Select the database name to be used or else print error message if unsuccessful*/

      function devolver_obras_sociales($dblink)
	  {
	      
	      $consulta = "SELECT nombre, idfinanciacion 
									 FROM financiacion
									 ORDER BY nombre asc"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_prof=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_prof["nombre"];
			 $indiceGrupo=$arreglo_prof["idfinanciacion"];
			 echo "<option value=\"$indiceGrupo\">$nombre</option>";
				            
		  }    
				//	   return $datosGrupos;
	  }
	  function devolverProfesionales($dblink)
	  {
	      
	      $consulta = "SELECT nombre, idprofesional 
									 FROM profesionales 
									 ORDER BY nombre ASC"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_prof=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_prof["nombre"];
			 $indiceGrupo=$arreglo_prof["idprofesional"];
			 echo "<option value=\"$indiceGrupo\">$nombre</option>";
				            
		  }    
				//	   return $datosGrupos;
	  }
	  function devolverLocalidades($dblink)
	  {
	      $consulta = "SELECT nombre 
					  FROM localidades
					  ORDER BY nombre ASC"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_loca=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_loca["nombre"];
			 echo "<option value=\"$nombre\">$nombre</option>";
				            
		  }   
	  }
	  function devolverProvincias($dblink)
	  {
	      $consulta = "SELECT nombre 
					  FROM provincias
					  ORDER BY nombre ASC"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_pro=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_pro["nombre"];
			 echo "<option value=\"$nombre\">$nombre</option>";
				            
		  }   
	  }
	  function devolverProvinciasLocalidad($dblink)
	  {
	      $consulta = "SELECT nombre,codigo_provincia  
					  FROM provincias
					  ORDER BY nombre ASC"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_pro=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		    $indice = $arreglo_pro["codigo_provincia"];
		     $nombre=$arreglo_pro["nombre"];
			 echo "<option value=\"$indice\">$nombre</option>";
				            
		  }   
	  }
	  function devolverProvincia($idprov,$dblink)
	  {
	      
	      $consulta = "SELECT nombre
			          FROM provincias WHERE codigo_provincia = $idprov";		          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		  $arreglo_prov=@mysql_fetch_array($datosGrupos,$dblink);		 
	      $nombre=$arreglo_prov["nombre"];		
		  return $nombre;
	  }
	  function devolver_obra_social($idosocial,$dblink)
	  {
	      
	      $consulta = "SELECT nombre
			          FROM financiacion WHERE idfinanciacion = $idosocial"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		  $arreglo_prof=@mysql_fetch_array($datosGrupos,$dblink);		 
	      $nombre=$arreglo_prof["nombre"];		
		  return $nombre;
	  }
/*	  function devolverTipoDroga($idtd,$dblink)
	  {
	      
	      $consulta = "SELECT descripcion
			          FROM tipo_droga WHERE codigo = $idtd";		          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		  $arreglo_td=@mysql_fetch_array($datosGrupos,$dblink);		 
	      $nombre=$arreglo_td["descripcion"];		
		  return $nombre;
	  }
	  function devolverTiposDrogas($dblink)
	  {
	      
	      $consulta = "SELECT *
					  FROM tipo_droga"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_td=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_td["descripcion"];
			 $indiceGrupo=$arreglo_td["codigo"];
			 echo "<option value=\"$indiceGrupo\">$nombre</option>";
				            
		  }    
				//	   return $datosGrupos;
	  }*/
	  function devolverEspecialidad($idesp,$dblink)
	  {
	      
	      $consulta = "SELECT nombre
			          FROM especialidades WHERE codigo_especialidad = $idesp"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		  $arreglo_esp=@mysql_fetch_array($datosGrupos,$dblink);		 
	      $nombre=$arreglo_esp["nombre"];		
		  return $nombre;
	  }
	  function devolverEspecialidades($dblink)
	  {
	      
	      $consulta = "SELECT *
					  FROM especialidades"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_esp=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_esp["nombre"];
			 $indiceGrupo=$arreglo_esp["codigo_especialidad"];
			 echo "<option value=\"$indiceGrupo\">$nombre</option>";
				            
		  }    
				//	   return $datosGrupos;
	  }
	  function devolverPlanSalud($idplan,$dblink)
	  {
	      
	      $consulta = "SELECT descripcion
			          FROM planes_de_salud WHERE codigo_plan = $idplan"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		  $arreglo_plan=@mysql_fetch_array($datosGrupos,$dblink);		 
	      $nombre=$arreglo_plan["descripcion"];		
		  return $nombre;
	  }
	  function devolverPlanesSalud($dblink)
	  {
	      
	      $consulta = "SELECT descripcion, codigo_plan 
									 FROM planes_de_salud"; 									          		  
          $datosGrupos = @mysql_query($consulta,$dblink);
		   while($arreglo_plan=@mysql_fetch_array($datosGrupos,$dblink))
		 { 
		     $nombre=$arreglo_plan["descripcion"];
			 $indiceGrupo=$arreglo_plan["codigo_plan"];
			 echo "<option value=\"$indiceGrupo\">$nombre</option>";
				            
		  }    
				//	   return $datosGrupos;
	  }
	  function devolverDiagnostico($id,$db)
	  {
	      $consulta = "SELECT descripcion, codigo FROM cide10 WHERE codigo = '$id'";
		  $datosDirecciones = @mysql_query($consulta,$db);
		  $arreglo=@mysql_fetch_array($datosDirecciones,MYSQL_ASSOC);
		  return $arreglo["descripcion"];
			     
	  }
	  function devolverPaciente($id,$db)
	  {
	      $consulta = "SELECT nombre FROM pacientes WHERE idpaciente = $id";
		  $datosDirecciones = @mysql_query($consulta,$db);
		  $arreglo=@mysql_fetch_array($datosDirecciones,MYSQL_ASSOC);
		  return $arreglo["nombre"];
	  }
	  function devolverProfesional($id,$db)
	  {
	      $consulta = "SELECT nombre FROM profesionales WHERE idprofesional = $id";
		  $datosDirecciones = @mysql_query($consulta,$db);
		  $arreglo=@mysql_fetch_array($datosDirecciones,MYSQL_ASSOC);
		  return $arreglo["nombre"];
	  }
	  function devolverCirugia($thisIdosocial,$id,$db)
	  {
	      $consulta = "SELECT descripcion, codigo FROM obra_social_nomenclador WHERE idosocial=$thisIdosocial and codigo = '$id'";
		  $datosDirecciones = @mysql_query($consulta,$db);
		  $arreglo=@mysql_fetch_array($datosDirecciones,MYSQL_ASSOC);
		  return $arreglo["descripcion"];
			     
	  }
	  function devolverCirugias($thisIdosocial,$dblink)
	  {
	      $consulta = "SELECT descripcion, codigo 
					  FROM obra_social_nomenclador WHERE idosocial=$thisIdosocial  order by descripcion asc";
          $datosGrupos = @mysql_query($consulta,$dblink);		
		  while($arreglo_nom=@mysql_fetch_array($datosGrupos,$dblink))
		  { 
		      $nombre=$arreglo_nom["descripcion"];
			  $indiceGrupo=$arreglo_nom["codigo"];
			  echo"<option value=\"$indiceGrupo\">$nombre</option>";				            
          }		
	  }
	  function generarSelect($dblink,$tabla,$primerCodigo,$segundoCodigo,$ordenado)
	  {
	      if ($ordenado != "")
	          $datos = @mysql_query("SELECT * FROM $tabla order by $ordenado",$dblink);
		  else
		      $datos = @mysql_query("SELECT * FROM $tabla",$dblink); 	  
		  while ( $arreglo = @mysql_fetch_array($datos,MYSQL_ASSOC) )
		  {
		      echo "<option value='".$arreglo[$primerCodigo]."'>".$arreglo[$segundoCodigo]."</option>";
		  }
	  }
	  function devolverDescripcion($dblink,$tabla,$descripcion,$codigo_busqueda,$valor_codigo,$comillas)
	  {
	      //si es 0 comillas es que tiene comillas el codigo		  
	      if ($comillas == 0)
	          $datos = @mysql_query("SELECT $descripcion FROM $tabla WHERE $codigo_busqueda='$valor_codigo'",$dblink);
		  else
		      $datos = @mysql_query("SELECT $descripcion FROM $tabla WHERE $codigo_busqueda=$valor_codigo",$dblink); 	  
          $arreglo = @mysql_fetch_array($datos,MYSQL_ASSOC);
		  echo $arreglo[$descripcion];
	  }
?>
