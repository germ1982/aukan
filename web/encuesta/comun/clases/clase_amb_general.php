<?
	class clase_amb_general 
	{
		var $id=0;
		var $nombre   ='';
		
		
		function clase_amb_general()
		{
			
		}
		function eliminar_fila($id,$id_tabla,$tabla)
		{			
			$bd = new baseDatos();
			$bd->Conectarse();
			
			if ($bd->select("DELETE FROM $tabla WHERE $id_tabla=$id"))
			    return 1;
			else 
			    return 0;
		}
	}
?>