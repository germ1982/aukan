<?
	class clase_medicamentos_comerciales 
	{
		var $id=0;
		var $descripcion   ='';
		var $precio = '';
		var $cantidad='';
			
		function clase_medicamentos_comerciales() { }
	    function obtener_valor_kairos($codigo_droga,$codigo_presentacion)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT precio FROM medicamentos_comerciales_presentaciones WHERE cod_medic='$codigo_droga' AND 
			             idmedicamento_comercial_presentacion=$codigo_presentacion");
			$arreglo = $bd->registro();
			$this->precio = $arreglo['precio'];
			return $this->precio; 
			//$this->cantidad=$arreglo['cantidad'];
		}
		function actualizar_valores_kairos($fdesde_cierre,$fhasta_cierre)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//$bd->set_names();
			$bd->select("SELECT codigo_droga,idpresentacion,cantidad,idfactura_internacion_medicamento_detalle 
			            FROM factura_internacion RIGHT JOIN factura_internacion_medicamentos_detalle on 
			            factura_internacion.movimiento_internacion=factura_internacion_medicamentos_detalle.movimiento_internacion
			            WHERE fecha_cierre='".fechaBase($fdesde_cierre)."'");
			while ($arreglo = $bd->registro())
			{
				$this->obtener_valor_kairos($arreglo['codigo_droga'],$arreglo['idpresentacion']);
				$precio = $arreglo['cantidad'] * $this->precio;				
				$bd->select("UPDATE factura_internacion_medicamentos_detalle  
			            SET prec_unit='$precio' WHERE 
			            idfactura_internacion_medicamento_detalle=".$arreglo['idfactura_internacion_medicamento_detalle']);
			}
			return 1;
		}	
	    function devolverCodigos($iddroga,$idpresentacion)
		{			
			$kairos = new clase_webservice_kairos();
			$soap= $kairos->conectarse();
			//el primer parametro es el lugar
			$arreglo = $soap->call("devolverCodigosSegunKairos", array("idlugar" => 1,"codigo"=>$iddroga,"idpresentacion"=>$idpresentacion)); 
			$a[0] = $arreglo[0];
			$a[1] = $arreglo[1];
			return $a;			
		}		              
	}
?>