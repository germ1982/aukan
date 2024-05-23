<?php
	class clase_pulseras
    {    			
		var $print_server = '';
		var $post ='';
        function clase_pulseras($tipo)
        {
         //   if ($tipo == 1)            
        	$this->print_server = "http://192.168.1.16/admin/cgi-bin/function.cgi";
        /*    else 
            {
                if ($tipo == 3)            
        	    $this->print_server = "http://192.168.0.141/admin/cgi-bin/function.cgi";
		else
                    $this->print_server = "http://192.168.0.145/admin/cgi-bin/function.cgi";
	    }*/
        }
        function calibrar($tipo)
        {
          //  if ($tipo == 1)            
        	$this->print_server = "http://192.168.1.16/admin/cgi-bin/calibrate.cgi";
         /*   else 
            {
                if ($tipo == 3)            
        	    $this->print_server = "http://192.168.0.141/admin/cgi-bin/function.cgi";
		else
                    $this->print_server = "http://192.168.0.145/admin/cgi-bin/function.cgi";
	    }*/
        }
        function enviar()
        {        	
        	$ch = curl_init();
               // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false) ;
			curl_setopt($ch, CURLOPT_URL,$this->print_server);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
                        //curl_setopt($ch, CURLOPT_TIMEOUT,1000);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                   // curl_setopt($ch, CURLOPT_USERPWD, ""); 
			$result=curl_exec($ch);
			curl_close ($ch);
			return $result;
        }
        function post_asigna($post)
        {
        	$this->post = $post;
        }
        function post()
        {
        	return $this->post;
        }
        function crear_script($tipo,$idepisodio,$idpaciente,$idpedido)
        {
        	$bandera = 1;
        	
        			$pac = new clase_pacientes($idpaciente);
        			$os =  new clase_obra_social();
        			//$epi = new clase_episodio($idepisodio);
                                $detalle_pedido = new clase_pedidos_estudio_detalle($idpedido);
                                $pedido = new clase_pedidos_estudio($detalle_pedido->idpedido_estudio());
                                $nome = new clase_nomenclador($detalle_pedido->idnomenclador());
        			
    				
					$episodio = $idepisodio;
				    $nombre = $pac->nombre();
				    $fecha = devolverFechaNormal($pac->fecha_nacimiento());
				    $edad = Edad(devolverFechaNormal($pac->fecha_nacimiento()));
				    $genero = $pac->sexo();
				    $hc = $idpaciente;
				    $os = $os->nombreObraSocial($pedido->idosocial());
				    //$fechah = devolverFechaNormal($epi->fecha_ingreso());
				    $generoh = $pac->sexo();
				    $pulsera_id = $idpaciente;
				
				    // Son distintos par�metros seg�n la plantilla
				    
				    	if ($tipo == 1)
                                        {
                                            $template = file_get_contents("/var/www/html/traumatologia/pulseras/script-medicamentos-80x40.txt");
					        $script = sprintf(
					            $template,
					            devolverCaracterImpresoraEtiquetas($nombre),
                                                    $pac->documento(),
                                                    $idpaciente,
                                                    $os,
                                                    devolverCaracterImpresoraEtiquetas($nome->descripcion()),
                                                    devolverFechaNormal($pedido->fecha())    
					        );
                                        }
				    	else 
				    	{
					        // Plantilla Simple
					        $template = file_get_contents("/var/www/html/avicenna/pulseras/template-simple.txt");
					        $script = sprintf(
					            $template,
					            $episodio,
					            $nombre,
					            $fecha,
					            $edad,
					            $genero,
					            $os,
					            $hc,
					            $pulsera_id,
					            $pulsera_id
					        );
				    	}
				    
                 
					$temporal = fopen("/var/www/html/traumatologia/pulseras/temp.txt", "w") or $bandera = 0;
				    fwrite($temporal, $script);
				    fclose($temporal);
    				
    				return $bandera;
        		}
        	
       
    }
?>
