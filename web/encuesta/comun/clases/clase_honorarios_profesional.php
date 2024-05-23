<?
	class clase_honorarios_profesional 
	{
		var $honorario = 0;
		var $codigo    = 0;
		var $idosocial = 0;
		var $categoria = 0;
		var $quien_es  = 0;
		var $idprofesional = 0;
		var $fecha     = '';		
		
		function clase_honorarios_profesional($codigo,$idosocial,$categoria,$quien_es,$idprofesional,$fecha,$que_es)
		{
			$this->codigo = $codigo;
			$this->idosocial = $idosocial;
			$this->categoria = $categoria;
			$this->quien_es = $quien_es;
			$this->idprofesional = $idprofesional;
			$this->fecha = $fecha; 
			$this->que_es = $que_es;
		}
		function calcularHonorarios()
		{
	    	$base = new baseDatos();
			$base->Conectarse();
			$base->select("SELECT * FROM financiacion WHERE idfinanciacion=".$this->idosocial);
			$os = $base->registro();
			if ($os['fecha_vencimiento'] == '0000-00-00' || $os['fecha_vencimiento'] == NULL)
			{	
				$select_valores_galenos = "importe";
				$hon_traumatologicos_a = $os['honorarios_traumatologico_categoria_a'];
				$hon_traumatologicos_b = $os['honorarios_traumatologico_categoria_b'];
				$hon_cirujanos_a = $os['honorarios_cirujano_categoria_a'];
				$hon_cirujanos_b = $os['honorarios_cirujano_categoria_b'];
				if ($this->quien_es == 'C') //si es cirujano
		    	    $base->select("SELECT gasto,honmed,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);
				if ($this->quien_es == 'A') //si es anestesista
	    			$base->select("SELECT gasto,honane,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);	
				if ($this->quien_es == 'A1' || $this->quien_es == 'A2') //si es ayud primero o segundo, usan el mismo honorario
			    	$base->select("SELECT gasto,honayu,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);		
				$practica = $base->registro();					
				$gasto = $practica['gasto'];
				if ($this->quien_es == 'C')									    	
				    $honmed = $practica['honmed'];					
				if ($this->quien_es == 'A')
				    $honane = $practica['honane'];
				if ($this->quien_es == 'A1')
					$honayu = $practica['honayu'];					
			}    
			else
			{
				//aca debo verificar los rangos de fecha correspondiente
				if ((fechaBase($this->fecha) <= $os['fecha_vencimiento']) && $this->fecha != '00/00/0000' && $this->fecha != '')
				{										
					$select_valores_galenos = "importe_anterior as importe";
					//	aca compruebo si las categorias cambiaron de valor
					if ($os['honorarios_traumatologico_categoria_a_anterior'] != '0.00' && $os['honorarios_traumatologico_categoria_a_anterior'] != '')
					    $hon_traumatologicos_a = $os['honorarios_traumatologico_categoria_a_anterior'];
					if ($os['honorarios_traumatologico_categoria_b_anterior'] != '0.00' && $os['honorarios_traumatologico_categoria_b_anterior'] != '')
				    	$hon_traumatologicos_b = $os['honorarios_traumatologico_categoria_b_anterior'];			
                	if ($os['honorarios_cirujano_categoria_a_anterior'] != '0.00' && $os['honorarios_cirujano_categoria_a_anterior'] != '')
				    	$hon_cirujanos_a = $os['honorarios_cirujano_categoria_a_anterior'];    
					if ($os['honorarios_cirujano_categoria_b_anterior'] != '0.00' && $os['honorarios_cirujano_categoria_b_anterior'] != '')
				    	$hon_cirujanos_b = $os['honorarios_cirujano_categoria_b_anterior'];      				    
				    if ($this->quien_es == 'C') //si es cirujano
		    		    $base->select("SELECT gasto_anterior,gasto,honmed_anterior,honmed,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);
					if ($this->quien_es == 'A') //si es anestesista
	    				$base->select("SELECT gasto_anterior,gasto,honane_anterior,honane,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);	
					if ($this->quien_es == 'A1' || $this->quien_es == 'A2') //si es ayud primero o segundo, usan el mismo honorario
			    		$base->select("SELECT gasto_anterior,gasto,honayu_anterior,honayu,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);		
					$practica = $base->registro();
					if ($practica['gasto_anterior'] != '' && $practica['gasto_anterior'] != '0.00')
				       	$gasto = $practica['gasto_anterior'];
				    else
				       	$gasto = $practica['gasto'];
					if ($this->quien_es == 'C')
					{	
				    	if ($practica['honmed_anterior'] != '' && $practica['honmed_anterior'] != '0.00')
				        	$honmed = $practica['honmed_anterior'];
				    	else
				        	$honmed = $practica['honmed'];
					}
					if ($this->quien_es == 'A')
					{
				    	if ($practica['honane_anterior'] != '' && $practica['honane_anterior'] != '0.00')
				        	$honane = $practica['honane_anterior'];
				    	else
				        	$honane = $practica['honane'];
					}
					if ($this->quien_es == 'A1')
					{
				    	if ($practica['honayu_anterior'] != '' && $practica['honayu_anterior'] != '0.00')
				        	$honayu = $practica['honayu_anterior'];
				    	else
				        	$honayu = $practica['honayu'];
					}
					
				}
				else
				{							
					$select_valores_galenos = "importe";	
					$hon_traumatologicos_a = $os['honorarios_traumatologico_categoria_a'];
					$hon_traumatologicos_b = $os['honorarios_traumatologico_categoria_b'];
					$hon_cirujanos_a = $os['honorarios_cirujano_categoria_a'];
					$hon_cirujanos_b = $os['honorarios_cirujano_categoria_b'];
					if ($this->quien_es == 'C') //si es cirujano
		    		    $base->select("SELECT gasto,honmed,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);
					if ($this->quien_es == 'A') //si es anestesista
	    				$base->select("SELECT gasto,honane,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);	
					if ($this->quien_es == 'A1' || $this->quien_es == 'A2') //si es ayud primero o segundo, usan el mismo honorario
			    		$base->select("SELECT gasto,honayu,tipgal FROM obra_social_nomenclador WHERE codigo='".$this->codigo."' AND idosocial=".$this->idosocial);		
					$practica = $base->registro();					
				    $gasto = $practica['gasto'];
					if ($this->quien_es == 'C')									    	
				        $honmed = $practica['honmed'];					
					if ($this->quien_es == 'A')
				        $honane = $practica['honane'];
					if ($this->quien_es == 'A1')
					   	$honayu = $practica['honayu'];					   	
				}
			}
			$base->select("SELECT * FROM profesionales WHERE idprofesional=".$this->idprofesional);
			$prof = $base->registro();
			
		
			if ($practica['tipgal'] != 13 && $practica['tipgal'] != 14)
			{
				$base->select("SELECT que_es,codigo_galeno_asociado FROM tipos_galenos WHERE cod_gal=".$practica['tipgal']);
				$tipo_galeno = $base->registro();
				if ($this->que_es == 'I' || $this->que_es == '')
				{
					$base->select("SELECT $select_valores_galenos FROM valores_galenos WHERE idosocial=".$this->idosocial." AND codigo_galeno=".$practica['tipgal']);		
					$galeno = $base->registro();
				    if ($galeno['importe'] != 0 && $galeno['importe'] != '')
				        $valores_gasto = $galeno['importe'];
				    else
                                    {
					//como no esta en Internacion el codigo voy a ambulatorio+
					$base->select("SELECT $select_valores_galenos FROM valores_galenos WHERE idosocial=".$this->idosocial." AND codigo_galeno=".$tipo_galeno['codigo_galeno_asociado']);
					$galeno_asociado = $base->registro();
					$valores_gasto = $galeno_asociado['importe'];	
				    }
				}
				else
				{				    
				    $base->select("SELECT $select_valores_galenos FROM valores_galenos WHERE idosocial=".$this->idosocial." AND codigo_galeno=".$practica['tipgal']);
				    $galeno_asociado = $base->registro();
				    if ($galeno_asociado['importe'] != 0 && $galeno_asociado['importe'] != '')
					$valores_gasto = $galeno_asociado['importe'];
				    else
				    {
					//como no esta en ambilatortio lo busco en internacion
					//como no esta en Internacion el codigo voy a ambulatorio+
					$base->select("SELECT $select_valores_galenos FROM valores_galenos WHERE idosocial=".$this->idosocial." AND codigo_galeno=".$tipo_galeno['codigo_galeno_asociado']);
					$galeno_asociado = $base->registro();
					$valores_gasto = $galeno_asociado['importe'];	
				    }
				}
				//$base->select("SELECT importe as importegal FROM valores_galenos WHERE idosocial=$idosocial AND codigo_galeno=".$practica['tipgal']);
				//$valores_gasto = $base->registro();
	//			return $practica['tipgal'];
				if ($this->quien_es == 'C') //si es cirujano				
		   			$g = $honmed*$valores_gasto;				
				if ($this->quien_es == 'A') //si es anestesista
		    		$g = $honane*$valores_gasto;
				if ($this->quien_es == 'A1' || $this->quien_es == 'A2') //si es ayud primero o segundo, usan el mismo honorario
		    		$g = $honayu*$valores_gasto;
		    		
			}
			else
			{
				//si vengo por acá es que tengo seleccionado honorarios traumatologicos
				if ($practica['tipgal'] == 13)
				{ 
					if ($this->quien_es == 'C') 
					{
						if ($prof['idcategoria'] == 'A') //categoria A
		   					$g = $honmed*$hon_traumatologicos_a;				
						if ($prof['idcategoria'] == 'B') //categoria B
		    				$g = $honmed*$hon_traumatologicos_b;
					}
					else
					{
						if ($this->idosocial == 11) $g =  $honayu*$hon_traumatologicos_a;
							else
						{
							if ($prof['idcategoria'] == 'A') //categoria A
		   						$g = $honayu*$hon_traumatologicos_a;				
							if ($prof['idcategoria'] == 'B') //categoria B
		    					$g = $honayu*$hon_traumatologicos_b;
						}
					}
					
				}
				else
				{
					//si vengo por acá es que selecciono galeno cirujano
					if ($this->quien_es == 'C') 
					{
						if ($prof['idcategoria'] == 'A') //categoria A						
		   					$g = $honmed*$hon_cirujanos_a;				
						if ($prof['idcategoria'] == 'B') //categoria B
		    				$g = $honmed*$hon_cirujanos_b;		    				
					}
					else
					{
						if ($prof['idcategoria'] == 'A') //categoria A
		   					$g = $honayu*$hon_cirujanos_a;				
						if ($prof['idcategoria'] == 'B') //categoria B
		    				$g = $honayu*$hon_cirujanos_b;
					}
					
				}
			}    			 
		
			//devolvermos el valor con todo los calculos hechos
			$base->cerrar();			
			if ($g == 0 || $g == "") 
		    	return 0;
			else 
		    	return redondeado($g,2);		
		}	
	}
?>
