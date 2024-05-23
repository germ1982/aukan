<?
	class clase_tesauro 
	{
		
		
		function clase_tesauro()
		{
			
		}		
		function insertar($tabla_insertar,$descriptionid,$texto,$subsetid,$id,$id_input)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			if ($bd->select("INSERT INTO $tabla_insertar(texto_tesauro,descriptionid,subsetid,$id_input) values('$texto',$descriptionid,$subsetid,$id)"))
			    return 1;
			else
			    return 0;
		}
		function mostrarTextoTesauro($id,$id_input,$contenedor,$tabla,$ver,$idprofesional)
   		{
       		$bd = new baseDatos();
	   		$bd->Conectarse();
	   		$base = new baseDatos();
	   		$base->Conectarse();
		
	   		$bd->select("SELECT * FROM $tabla WHERE $id_input=$id");
	   		$contenido = "<table>";
	   		$i = 0;
	  		while ($arreglo=$bd->registro())
	   		{	       		
	       		if ($ver ==0)
		   		{
		   			$contenido .= "<tr>						
                 					<td>
				    				<input id='descripcion$i' value='".$arreglo['texto_tesauro']."' class='inputText' size='80'>
			     					</td>				
			     					<td>";
		   		}
		   		else
		       		$contenido .= "<tr>						
                 					<td>".$arreglo['texto_tesauro']."</td>				
			     					<td>"; 
				if ($ver == 0) {
		   			$contenido.="		 
			         <img src=\"../../imagenes/dbDelete.png\" style=\"cursor:pointer\" id=\"imgCheck0\" onclick=\"
					 if(confirm('Esta seguro de querer eliminar este registro?'))
					 {
					 	xajax_borrarTextoTesauro(".$arreglo['id'].",$id,'id','$tabla',$ver,'$contenedor','$id_input',$idprofesional)
					 }	\" alt=\"Borrar\" />";
                 }				 					 
		  		 $contenido.="</td>			    				 
				 </tr>";  
                  $i++;				 
	         }
	         $contenido .= "</table>";
	         return $contenido;
       }
	    function borrarTextoTesauro($id,$id_input,$tabla)
		{
			$bd = new baseDatos();
	    	$bd->Conectarse();
	    	$queryDelete = "DELETE FROM $tabla WHERE $id_input=$id";				  
	    	if ($bd->select($queryDelete))
		    	return 1;
	    	else
		    	return 0;
		}
	    function todos_textos($tabla,$idtabla_foraneo,$idtabla_input,$select,$order)
		{			
			$bd = new baseDatos();
	    	$bd->Conectarse();
	    	$bd->select("SELECT $select FROM $tabla WHERE $idtabla_input=$idtabla_foraneo $order");
			$esp = new clase_listar();
			
			//return $filas;				
    		for($i=0;$i<=$bd->numero_filas();$i++) 
    		{
    			$fila = $bd->registro(); 
    			$esp->introducirElemento($fila[$select]); 
    		}
			return $esp;			
		}
	}
?>