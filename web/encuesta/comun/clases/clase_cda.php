<?
	class clase_cda 
	{
		var $id=0;
		var $nombre   ='';
		var $con_id_prefijo = '';
		var $xsl = '';
		var $rep_cda_loinc = '';
		var $rep_cda_displayname = '';
		var $rep_cda_titulo = '';
		var $rep_service_event = '';
		var $rep_performer = '';
		var $functionCode = '';
		var $rep_section_titulo = '';
		var $rep_templateid = '';
		var $rep_body = '';
		var $rep_structurebody_titulo = '';
		var $rep_code_structurebody = '';
		var $authenticator = '';
		
		function clase_cda($tipo,$que_es)
		{
			//$bd = new baseDatos();
			//$bd->Conectarse();
			if ($tipo == 1) //si es 1 quiere decir que es algun tipo de evolucion
			{
			    $this->con_id_prefijo = 'EVO';
         		$this->xsl = 'cdaHCD01.xsl';
         		$this->rep_cda_loinc = '34133-9';
         		$this->rep_cda_displayname = 'Summarization of episode note';
         		$this->rep_cda_titulo = 'Resumen de consulta';
       			$this->rep_service_event = 'PCPR';
         		$this->rep_performer = 'PRF';
         		//como es una evolucion va functionCode
                $this->functionCode = "<functionCode code='PCP' codeSystem='2.16.840.1.113883.5.88'/>";   
                $this->rep_section_titulo = 'EVOLUCION';   
                $this->rep_templateid = '2.16.840.1.113883.10.20.1.5';
                $this->rep_code_structurebody = '47420-5';   
                $this->rep_body = "<templateId root='2.16.840.1.113883.10.20.1.5'/>
        		                   <code code='47420-5' codeSystem='2.16.840.1.113883.6.1'/>";
                $this->rep_structurebody_titulo = 'EVOLUCION';
			}
			if ($tipo == 2) //es ingreso
			{
				$this->con_id_prefijo = 'INGRESOEPI';
         		$this->xsl = 'cdaHCHojaCero01.xsl';
         		$this->rep_cda_loinc = '47039-3';
         		$this->rep_cda_displayname = 'Admission history and physical note';
         		$this->rep_cda_titulo = 'HC de ingreso';
       			$this->rep_service_event = 'PCPR';
         		$this->rep_performer = 'PRF';

         		//como es un ingreso va functionCode
                $this->functionCode = "<functionCode code='PCP' codeSystem='2.16.840.1.113883.5.88'/>";   
                $this->rep_section_titulo = 'HCINGRESO';   
                $this->rep_templateid = '2.16.840.1.113883.10.20.1.5';
                $this->rep_code_structurebody = '47420-5';   
                $this->rep_body = "<templateId root='2.16.840.1.113883.10.20.1.3'/>
        		                   <code code='46240-8' codeSystem='2.16.840.1.113883.6.1'/>";
                $this->rep_structurebody_titulo = 'HC de INGRESO';
			}      
			if ($tipo == 3) //es problemas
			{
				$this->con_id_prefijo = 'PRO';
         		$this->xsl = 'cdaHCD01.xsl';
         		$this->rep_cda_loinc = '34133-9';
         		$this->rep_cda_displayname = 'Summarization of episode note';
         		$this->rep_cda_titulo = 'Resumen de consulta';
       			$this->rep_service_event = 'PCPR';
         		$this->rep_performer = 'PRF';
         		//como es una evolucion va functionCode
                $this->functionCode = "<functionCode code='PCP' codeSystem='2.16.840.1.113883.5.88'/>";   
                $this->rep_section_titulo = 'PROBLEMAS';   
                $this->rep_templateid = '2.16.840.1.113883.10.20.1.5';
                $this->rep_code_structurebody = '11450-4';   
                $this->rep_body = "<templateId root='2.16.840.1.113883.10.20.1.5'/>
        		                   <code code='11450-4' codeSystem='2.16.840.1.113883.6.1'/>";
                $this->rep_structurebody_titulo = 'PROBLEMAS';
			}  
			if ($tipo == 4) //si es 4 quiere decir que es laboratorio
			{
			    $this->con_id_prefijo = 'LAB';
         		$this->xsl = 'cda36505.xsl';
         		$this->rep_cda_loinc = '26436-6';
         		$this->rep_cda_displayname = 'ALL LABORATORY STUDIES';
         		$this->rep_cda_titulo = 'INFORME DE LABORATORIO';
       			$this->rep_service_event = 'PCPR';
         		$this->rep_performer = 'PPRF';
         		//como es una evolucion va functionCode
                $this->functionCode = "<functionCode code='PCP' codeSystem='2.16.840.1.113883.5.88'/>";   
                $this->rep_section_titulo = 'EVOLUCION';   
                $this->rep_templateid = '2.16.840.1.113883.10.20.1.5';
                $this->rep_code_structurebody = '47420-5';   
                $this->rep_body = "<templateId root='2.16.840.1.113883.10.20.1.5'/>
        		                   <code code='47420-5' codeSystem='2.16.840.1.113883.6.1'/>";
                $this->rep_structurebody_titulo = 'EVOLUCION';
			}  
			if ($tipo == 5) //es balance por ahora nada mas, hay que ver si no es signos vitales tambien
			{
				$this->con_id_prefijo = 'BAL';
         		$this->xsl = 'cdaHCBalance01.xsl';
         		$this->rep_cda_loinc = '28623-7';
         		$this->rep_cda_displayname = 'Nursing Subsequent Visit Evaluation Note';
         		$this->rep_cda_titulo = 'Hojas de balance';
       			$this->rep_service_event = 'PCPR';
         		$this->rep_performer = 'PRF';

         		//como es un ingreso va functionCode
                $this->functionCode = "<functionCode code='PCP' codeSystem='2.16.840.1.113883.5.88'/>";   
                $this->rep_section_titulo = 'BALANCE';   
                $this->rep_templateid = '2.16.840.1.113883.10.20.1.5';
                $this->rep_code_structurebody = '47420-5';   
                $this->rep_body = "<templateId root='2.16.840.1.113883.10.20.1.3'/>
        		                   <code code='46240-8' codeSystem='2.16.840.1.113883.6.1'/>";
                $this->rep_structurebody_titulo = 'HOJAS DE BALANCE';
			}  
			                         			
		}
		function con_id_prefijo()
		{			
			return $this->con_id_prefijo;
		}
		function xsl()
		{
			return $this->xsl;
		}
		function rep_cda_loinc()
		{
			return $this->rep_cda_loinc;
		}
		function rep_cda_displayname()
		{
			return $this->rep_cda_displayname;
		}
		function rep_cda_titulo()
		{
			return $this->rep_cda_titulo;
		}
		function rep_service_event()
		{
			return $this->rep_service_event;
		}
		function rep_performer()
		{
			return $this->rep_performer;
		}
		function functionCode()
		{
			return $this->functionCode;
		}
		function rep_section_titulo()
		{
			return $this->rep_section_titulo;
		}
		function rep_templateid()
		{
			return $this->rep_templateid;
		}
		function rep_code_structurebody()
		{
			return $this->rep_code_structurebody;
		}
		function rep_body()
		{
			return $this->rep_body;
		}
		function rep_structurebody_titulo()
		{
			return $this->rep_structurebody_titulo;
		}
		
	}
?>