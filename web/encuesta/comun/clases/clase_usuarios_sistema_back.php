<?
      class clase_usuarios_sistema       
      {
	  var $id = '';
          var $username = '';
          var $password = '';
          var $rol = '';
          var $email = '';
          var $ultimo_ingreso = '';
          var $ultima_accion = '';
          var $ip = '';
          var $sesion = '';
          var $idprof = '';
          var $suspendido = '';
          
      
      var $arreglo_foraneo_idprof='';
      	     
      
         function clase_usuarios_sistema($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM usuarios_sistema WHERE id=$id");
      	     $arreglo=$bd->registro();     
      	     self::asigna($arreglo); 	           	     
      	 }
      	 function asigna($arreglo)
      	 {
      	     $this->id=$arreglo['id'];
      	     $this->username=$arreglo['username'];
      	     $this->password=$arreglo['password'];
      	     $this->rol=$arreglo['rol'];
      	     $this->email=$arreglo['email'];
      	     $this->ultimo_ingreso=$arreglo['ultimo_ingreso'];
      	     $this->ultima_accion=$arreglo['ultima_accion'];
      	     $this->ip=$arreglo['ip'];
      	     $this->sesion=$arreglo['sesion'];
      	     $this->idprof=$arreglo['idprof'];
      	     $this->suspendido=$arreglo['suspendido'];
      	 }       
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO usuarios_sistema(username,password,rol,email,ultimo_ingreso,ultima_accion,ip,sesion,idprof,suspendido) VALUES('".$this->username."','".$this->password."','".$this->rol."','".$this->email."','".$this->ultimo_ingreso."','".$this->ultima_accion."','".$this->ip."','".$this->sesion."','".$this->idprof."','".$this->suspendido."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE usuarios_sistema SET username='".$this->username."',password='".$this->password."',rol='".$this->rol."',email='".$this->email."',ultimo_ingreso='".$this->ultimo_ingreso."',ultima_accion='".$this->ultima_accion."',ip='".$this->ip."',sesion='".$this->sesion."',idprof='".$this->idprof."',suspendido='".$this->suspendido."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function id()
          {
               return $this->id;
          }
          function username()
          {
               return $this->username;
          }
          function password()
          {
               return $this->password;
          }
          function rol()
          {
               return $this->rol;
          }
          function email()
          {
               return $this->email;
          }
          function ultimo_ingreso()
          {
               return $this->ultimo_ingreso;
          }
          function ultima_accion()
          {
               return $this->ultima_accion;
          }
          function ip()
          {
               return $this->ip;
          }
          function sesion()
          {
               return $this->sesion;
          }
          function idprof()
          {
               return $this->idprof;
          }
          function suspendido()
          {
               return $this->suspendido;
          }
          
          
          
      	     function arreglo_foraneo_idprof()
             {
                 return $this->arreglo_foraneo_idprof;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function username_asigna($campo)
          {
               $this->username=$campo;
               
          }
          function password_asigna($campo)
          {
               $this->password=$campo;
               
          }
          function rol_asigna($campo)
          {
               $this->rol=$campo;
               
          }
          function email_asigna($campo)
          {
               $this->email=$campo;
               
          }
          function ultimo_ingreso_asigna($campo)
          {
               $this->ultimo_ingreso=$campo;
               
          }
          function ultima_accion_asigna($campo)
          {
               $this->ultima_accion=$campo;
               
          }
          function ip_asigna($campo)
          {
               $this->ip=$campo;
               
          }
          function sesion_asigna($campo)
          {
               $this->sesion=$campo;
               
          }
          function idprof_asigna($campo)
          {
               $this->idprof=$campo;
               
          }
          function suspendido_asigna($campo)
          {
               $this->suspendido=$campo;
               
          }
          function password_user($username)
          {
          	  $bd = new baseDatos();
			  $bd->Conectarse();		    
			  $bd->select("SELECT * FROM usuarios_sistema WHERE username='$username'");				
			  $pro = $bd->registro();
	    	  self::asigna($pro);		
          }
          function login($idprofesional,$username)
          {
              $bd = new baseDatos();
			  $bd->Conectarse();		    
			  if ($bd->select("UPDATE usuarios_sistema SET sesion=1 WHERE username='$username' AND idprof=$idprofesional"))
			      return 1;
			  else 
			      return 0;    	
          }
      	  function logout($username)
          {
              $bd = new baseDatos();
			  $bd->Conectarse();		    
			  if ($bd->select("UPDATE usuarios_sistema SET sesion=0 WHERE username='$username'"))
			      return 1;
			  else 
			      return 0;    	
          }
      	  function sesion_activa($username)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM usuarios_sistema WHERE username='$username' AND sesion=1");
				$arreglo = $bd->registro();
				self::asigna($arreglo);
				return $arreglo['sesion'];												                         	
			}
	      function foranea_idprof($idprof)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM usuarios_sistema WHERE idprof=$idprof");				
				$pro = $bd->registro();				
	    		self::asigna($pro);		                              		
			}
	function cambiarPassword($anterior_pass,$password,$username)
	{	    
	    $bd = new baseDatos();
	    $bd->Conectarse();
	    $bd->select("SELECT * FROM usuarios_sistema WHERE username='$username'"); 	    
	    $arreglo = $bd->registro();
	    if ($arreglo['password'] == sha1($anterior_pass))
	    {	
		if (passwordcheck($username, $password) == false)
		{
		    $bd->select("UPDATE usuarios_sistema SET password='".sha1($password)."' WHERE username='$username'");
		    $bd->cerrar();     
		    return 0;
		}
		else
		{
		    return 1;	
		    //echo "La password elegida no cumple con los requisitos obligatorios";
		} 
	    }
	    else	
	    {
		return 2;
		//echo "Alguno de los datos son incorrectos, vuelva a intertarlo";
	    }	
	}      
}
?>
