<?
	class clase_stock 
	{
		var $id=0;
		var $nombre   ='';
		
		
		function clase_stock()
		{
			/*$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT nombre FROM sectores WHERE id=$id");
			$sectores = $bd->registro();
			$this->nombre = $sectores['nombre'];			
			$this->id = $id;
			return $this->nombre;*/
		}
		function actualiza_deposito($idsector,$iddroga,$idpresentacion,$cantidad,$campo)
		{			
			$bd = new baseDatos();
			$bd->Conectarse();
			//si vengo por aca es que estoy haciendo un ingreso al sector y una salida al stock real de farmcia
			//return $bd;
				$bd->select("SELECT * FROM depositos_sectores WHERE idsector=$idsector AND idcatalogo=$iddroga AND idcatalogo_presentacion=$idpresentacion");
				//si vengo por aca es que el sector ya tiene el producto
			//	return self::actualizar_stock($cantidad,$idsector,$iddroga,$idpresentacion,$campo);
				if ($bd->numero_filas() != 0)
				{							
					if ( self::actualizar_stock($cantidad,$idsector,$iddroga,$idpresentacion,$campo) != 0)
					    return 1;
					else 
					    return 0;					
				}
				else //si vengo por aca es que no esta ese producto en dicho sector por lo tanto lo debo insertar
				{
					if ($bd->select("INSERT INTO depositos_sectores(idsector,idcatalogo,idcatalogo_presentacion) VALUES($idsector,$iddroga,$idpresentacion)"))
					{
						if (self::actualizar_stock($cantidad,$idsector,$iddroga,$idpresentacion,$campo))
					    	return 1;
						else 
					    	return 0;
					}
					else 
					    return 0;
				}
		
			
		}
		function actualizar_stock($cantidad,$idsector,$iddroga,$idpresentacion,$campo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    //return $bd;
                        $cantidad = str_replace(',','.',$cantidad);
			if ($bd->select("UPDATE depositos_sectores SET $campo = $campo + $cantidad WHERE idsector=$idsector AND idcatalogo=$iddroga AND idcatalogo_presentacion=$idpresentacion"))
			    return 1;
			else 
			    return 0;
		}		
		function actualiza_stock_logs($idprofesional,$iddroga,$idpresentacion,$cantidad_ingreso_egreso_campo,$idsector,$idpedido,$idgeneral,$idindicacion,$cantidad)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    if ($cantidad_ingreso_egreso_campo == 'cantidad_egreso')
		        $cant_egreso = $cantidad;
		    else 
		        $cant_ingreso = $cantidad;
			if ($bd->select("INSERT INTO stock_logs(idprofesional,idcatalogo,idcatalogo_presentacion,cantidad_ingreso,cantidad_egreso,idsector,idpedido,idgeneral,idindicacion) 
			                VALUES($idprofesional,$iddroga,$idpresentacion,'$cant_ingreso','$cant_egreso',$idsector,'$idpedido','$idgeneral','$idindicacion')"))
			    return 1;
			else 
			    return 0;
		}
	}
?>
