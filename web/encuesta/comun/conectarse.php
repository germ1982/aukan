<?php 
require __DIR__ . '/../../../vendor/autoload.php';

    class baseDatos
	 {

         var $Conexion_ID = 0;
	     var $Consulta_ID = 0;
	     var $Errno = 0;
	     var $Error = "";
	     var $_query="";
		 
		 function Conectarse()
         { 
			$dirBaseDatos=msdyt;
			$BaseDatos=env('DB_SUR_NAME');
			$Usuario='motu';
			$Password='motu';
			//$private_key = env('ENCUESTA_PRIVATE_KEY');
            /*  if (!($this->Conexion_ID=mysqli_connect($dirBaseDatos,$Usuario,$Password,false)))
	         {
                  echo "Error conectando a la base de datos.";
                  exit();
	         }
	         if (!mysqli_select_db($this->Conexion_ID,$BaseDatos))
	         {
		          echo "Error seleccionando la base de datos.";
		          exit();
	         } */
                 mysqli_query($this->Conexion_ID,"SET NAMES 'utf8'");
	         return $this->Conexion_ID;
         }
		 function fetch_assoc()
		 {
		     return @mysqli_fetch_assoc($this->Consulta_ID);
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
             $this->Consulta_ID = @mysqli_query($this->Conexion_ID,$sql);
 	         if (!$this->Consulta_ID) 
			 {
			     $this->Errno = mysqli_errno($this->Conexion_ID);  // mysql_errno = codigo de error de mysql o 0 si no hay error
 	             $this->Error = mysqli_error($this->Conexion_ID);  // mysql_error = texto de error de mysql o "" si no hay error	
             }
             /* Si hemos tenido �xito en la consulta devuelve 
             el identificador de la conexi�n, sino devuelve 0 */
             return $this->Consulta_ID;
         }
		 function registro()
		 {
		  //   $this->Consulta_ID = @mysql_fetch_array($this->Consulta_ID,MYSQL_ASSOC);
                    return @mysqli_fetch_array($this->Consulta_ID,MYSQLI_ASSOC);        
                 }
		 function resvarID($sql)
		 {
			 $this->Consulta_ID = mysqli_query($this->Conexion_ID,"BEGIN");
			 $this->Consulta_ID = @mysqli_query($this->Conexion_ID,$sql);
			 $identificacion =  mysqli_insert_id();
			 $this->Consulta_ID = mysqli_query($this->Conexion_ID,"COMMIT");
			 return  $identificacion;
		 }
		 function cerrar()
		 {
			@mysqli_close($this->Conexion_ID);
		  }
		  function numero_filas()
		  {
		      return @mysqli_num_rows($this->Consulta_ID);
		  }
		  
		  function registro_filas()
		  {
		      return @mysqli_fetch_row($this->Consulta_ID);
		  }
		  function error()
		  {
		      return $this->Errno ;
		  }
		  
		  function abrirtransaccion(){
 
 			$this->Consulta_ID = mysqli_query($this->Conexion_ID,"BEGIN");
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysqli_errno($this->Conexion_ID);  // mysql_errno = codigo de error de mysql o 0 si no hay error
				$this->Error = mysqli_error($this->Conexion_ID);  // mysql_error = texto de error de mysql o "" si no hay error
				
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
		
			return $this->Consulta_ID;
			
			}
			 ////////////////////////////////////////////////////////////////////////////////////
			   function deshacertransaccion(){
			
			$this->Consulta_ID = mysqli_query($this->Conexion_ID,"ROLLBACK");
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysqli_errno($this->Conexion_ID);
				$this->Error = mysqli_error($this->Conexion_ID);
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
			
			return $this->Consulta_ID;
			  }
			
			 ////////////////////////////////////////////////////////////////////////////////////
			  function cerrartransaccion(){
			
			$this->Consulta_ID = mysqli_query($this->Conexion_ID,"COMMIT");
			
			 
				if (!$this->Consulta_ID) {
				
				$this->Errno = mysqli_errno($this->Conexion_ID);
				$this->Error = mysqli_error($this->Conexion_ID);
			}
			
			/* Si hemos tenido �xito en la consulta devuelve 
			el identificador de la conexi�n, sino devuelve 0 */
			
			return $this->Consulta_ID;
			  }
              function set_names()
	      {
		  @mysqli_query("SET NAMES 'utf8'");
	          return 1;		
              }	
	      function ultimo_id()
	      {  
	          $this->Consulta_ID = @mysqli_query($this->Conexion_ID,"SELECT LAST_INSERT_ID()");
	          $id = '';
  	          if (!$this->Consulta_ID) 
		  {
		      $this->Errno = mysqli_errno($this->Conexion_ID);  // mysql_errno = codigo de error de mysql o 0 si no hay error
 	              $this->Error = mysqli_error($this->Conexion_ID);  // mysql_error = texto de error de mysql o "" si no hay error	
                  }
                  else
                  {
             	      $resultado = @mysqli_fetch_array($this->Consulta_ID, MYSQL_ASSOC);
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
            function parser($campo)
            {
				return mysqli_real_escape_string($campo);
			}	
    }
?>
