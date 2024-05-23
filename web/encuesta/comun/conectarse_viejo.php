<?php 
    class baseDatosVieja
	 {
        		
		 var $dirBaseDatos="";
         var $BaseDatos="";
         var $Usuario="";
         var $Password="";
		 
         var $Conexion_ID = 0;
	     var $Consulta_ID = 0;
	     var $Errno = 0;
	     var $Error = "";
	     var $_query="";
	     var $private_key = '3%MUFY+pAVMUQ=AhdI5E%SkE9RPPsZu4^YbnaCknYq=IJBA=KRfru8Wr1dgP';
		 
		 function Conectarse()
         { 
             if (!($this->Conexion_ID=mysql_pconnect($this->dirBaseDatos,$this->Usuario,$this->Password,TRUE)))
	         {
                  echo "Error conectando a la base de datos.";
                  exit();
	         }
	         if (!mysql_select_db($this->BaseDatos,$this->Conexion_ID))
	         {
		          echo "Error seleccionando la base de datos.";
		          exit();
	         }
                  @mysql_query("SET NAMES 'utf8'");
	         return $this->Conexion_ID;
         }
		 function fetch_assoc()
		 {
		     return @mysql_fetch_assoc($this->Consulta_ID);
		 }	 
		 //funcion para realizar la consulta
		 function select($sql)
		 {  
             if ($sql == "") 
			 {
				$this->Error = "No ha especificado una consulta SQL";
				return 0;
             }
             //ejecutamos la consulta
	  //   @mysql_query("SET NAMES 'utf8'");	
             $this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
 	         if (!$this->Consulta_ID) 
			 {
			     $this->Errno = mysql_errno();  // mysql_errno = codigo de error de mysql o 0 si no hay error
 	             $this->Error = mysql_error();  // mysql_error = texto de error de mysql o "" si no hay error	
             }
             /* Si hemos tenido �xito en la consulta devuelve 
             el identificador de la conexi�n, sino devuelve 0 */
             return $this->Consulta_ID;
         }
		 function registro()
		 {
		  //   $this->Consulta_ID = @mysql_fetch_array($this->Consulta_ID,MYSQL_ASSOC);
		     return @mysql_fetch_array($this->Consulta_ID, MYSQL_ASSOC);
		 }
		 function resvarID($sql)
		 {
			 $this->Consulta_ID = mysql_query("BEGIN", $this->Conexion_ID);
			 $this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
			 $identificacion =  mysql_insert_id();
			 $this->Consulta_ID = mysql_query("COMMIT", $this->Conexion_ID);
			 return  $identificacion;
		 }
		 function cerrar()
		 {
			@mysql_close($this->Conexion_ID);
		  }
		  function numero_filas()
		  {
		      return @mysql_num_rows($this->Consulta_ID);
		  }
		  
		  function registro_filas()
		  {
		      return @mysql_fetch_row($this->Consulta_ID);
		  }
		  function error()
		  {
		      return $this->Errno ;
		  }
		  
		  function abrirtransaccion(){
 
 			$this->Consulta_ID = mysql_query("BEGIN", $this->Conexion_ID);
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysql_errno();  // mysql_errno = codigo de error de mysql o 0 si no hay error
				$this->Error = mysql_error();  // mysql_error = texto de error de mysql o "" si no hay error
				
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
			
			return $this->Consulta_ID;
			
			}
			 ////////////////////////////////////////////////////////////////////////////////////
			   function deshacertransaccion(){
			
			$this->Consulta_ID = mysql_query("ROLLBACK", $this->Conexion_ID);
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysql_errno();
				$this->Error = mysql_error();
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
			
			return $this->Consulta_ID;
			  }
			
			 ////////////////////////////////////////////////////////////////////////////////////
			  function cerrartransaccion(){
			
			$this->Consulta_ID = mysql_query("COMMIT", $this->Conexion_ID);
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysql_errno();
				$this->Error = mysql_error();
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
			
			return $this->Consulta_ID;
			  }
              function set_names()
	      {
		  @mysql_query("SET NAMES 'utf8'");
	          return 1;		
              }	
	      function ultimo_id()
	      {  
	          $this->Consulta_ID = @mysql_query("SELECT LAST_INSERT_ID()", $this->Conexion_ID);
	          $id = '';
  	          if (!$this->Consulta_ID) 
		  {
		      $this->Errno = mysql_errno();  // mysql_errno = codigo de error de mysql o 0 si no hay error
 	              $this->Error = mysql_error();  // mysql_error = texto de error de mysql o "" si no hay error	
                  }
                  else
                  {
             	      $resultado = @mysql_fetch_array($this->Consulta_ID, MYSQL_ASSOC);
             	      $id = $resultado['LAST_INSERT_ID()'];
                  }             
                  return $id;
              }
	      public function insertarsql($table, $values) 
	      {
                  $this->_query = "INSERT INTO {$table} ("
                        .implode(', ', array_keys($values))
                        .') VALUES(';                       
                  foreach ($values as $key => $value) 
                  {
                    // $value = $this->sanitize($value);
                 
            	/* if(is_numeric($value))             	 
                 	$this->_query .= ', ' . $value;
                 else 
                 {*/
                 	 if (datecheck($value))
                 	     $this->_query .= ", '". fechaBase($value) . "'";
                 	 else                  	                  	 
                         $this->_query .= ", '". $value . "'";                 	 
                 //}            
                   }
        	   $this->_query = str_replace('(,', '(', $this->_query);
        	   $this->_query .=  ')';
        	   //return $this->_query;
        	   $this->select($this->_query);    
             }			
    }
?>
