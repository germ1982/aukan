<?php

      class clase_respuesta_movil       
      {
           var $ip       ='';
           var $username = '';
           var $codigo   = 0;
           var $descripcion = '';
           var $url      = '';
           var $request   = '';
           var $idprofesional = 0;

           function clase_respuesta_movil($codigo,$idprofesional)
           {
               $this->descripcion = MensajeError($codigo);
               $usuario = new clase_usuarios_sistema();
               $usuario->foranea_idprof($idprofesional);
               $this->ip = $usuario->ip();
               $this->username = $usuario->username();
               $this->url = 'algo';
               $this->request = 'algo mas'; 	
           }
           function ip()
           {
           	   return $this->ip;
           }
           function username()
           {
           	   return $this->username;
           }
           function codigo()
           {
           	   return $this->codigo;
           }
           function descripcion()
           {
           	   return $this->descripcion;
           }
           function url()
           {
           	   return $this->url;
           }
           function request()
           {
           	   return $this->request;
           }
           function idprofesional()
           {
           	   return $this->idprofesional;
           }
      	   function ip_asigna($campo)
           {
           	   $this->ip = $campo;
           }
           function username_asigna($campo)
           {
           	   $this->username = $campo;
           }
           function codigo_asigna($campo)
           {
           	   $this->codigo = $campo;
           }
           function descripcion_asigna($campo)
           {
           	   $this->descripcion = $campo;
           }
           function url_asigna($campo)
           {
           	   $this->url = $campo;
           }
           function request_asigna($campo)
           {
           	   $this->request = $campo;
           }
           function idprofesional_asigna($campo)
           {
           	   $this->idprofesional = $campo;
           }
      }
?>