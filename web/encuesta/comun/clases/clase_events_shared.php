<?
      class clase_events_shared       
      {
	  var $event_id = '';
          var $start_date = '';
          var $end_date = '';
          var $text = '';
          var $event_type = '';
          var $userId = '';
          var $estado = '';
          var $event_id_inicial = '';
          var $sesiones = '';
          var $sesion_actual = '';
          var $idfinanciacion = '';
          var $motivo_consulta = '';
          
      
      
      
         function clase_events_shared($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM events_shared WHERE event_id=$id");
      	     $arreglo=$bd->registro();
      	     $this->event_id=$arreglo['event_id'];
      	     $this->start_date=$arreglo['start_date'];
      	     $this->end_date=$arreglo['end_date'];
      	     $this->text=$arreglo['text'];
      	     $this->event_type=$arreglo['event_type'];
      	     $this->userId=$arreglo['userId'];
      	     $this->estado=$arreglo['estado'];
      	     $this->event_id_inicial=$arreglo['event_id_inicial'];
      	     $this->sesiones=$arreglo['sesiones'];
      	     $this->sesion_actual=$arreglo['sesion_actual'];
      	     $this->idfinanciacion=$arreglo['idfinanciacion'];
      	     $this->motivo_consulta=$arreglo['motivo_consulta'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->event_id==0 || $this->event_id=='' ) {
      	      if ($bd->select("INSERT INTO events_shared(start_date,end_date,text,event_type,userId,estado,event_id_inicial,sesiones,sesion_actual,idfinanciacion,motivo_consulta) VALUES('".$this->start_date."','".$this->end_date."','".$this->text."','".$this->event_type."','".$this->userId."','".$this->estado."','".$this->event_id_inicial."','".$this->sesiones."','".$this->sesion_actual."','".$this->idfinanciacion."','".$this->motivo_consulta."')"))
      	      {
      	          $this->event_id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE events_shared SET start_date='".$this->start_date."',end_date='".$this->end_date."',text='".$this->text."',event_type='".$this->event_type."',userId='".$this->userId."',estado='".$this->estado."',event_id_inicial='".$this->event_id_inicial."',sesiones='".$this->sesiones."',sesion_actual='".$this->sesion_actual."',idfinanciacion='".$this->idfinanciacion."',motivo_consulta='".$this->motivo_consulta."' WHERE event_id='".$this->event_id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function event_id()
          {
               return $this->event_id;
          }
          function start_date()
          {
               return $this->start_date;
          }
          function end_date()
          {
               return $this->end_date;
          }
          function text()
          {
               return $this->text;
          }
          function event_type()
          {
               return $this->event_type;
          }
          function userId()
          {
               return $this->userId;
          }
          function estado()
          {
               return $this->estado;
          }
          function event_id_inicial()
          {
               return $this->event_id_inicial;
          }
          function sesiones()
          {
               return $this->sesiones;
          }
          function sesion_actual()
          {
               return $this->sesion_actual;
          }
          function idfinanciacion()
          {
               return $this->idfinanciacion;
          }
          function motivo_consulta()
          {
               return $this->motivo_consulta;
          }
          
          
          
      
          function event_id_asigna($campo)
          {
               $this->event_id=$campo;
               
          }
          function start_date_asigna($campo)
          {
               $this->start_date=$campo;
               
          }
          function end_date_asigna($campo)
          {
               $this->end_date=$campo;
               
          }
          function text_asigna($campo)
          {
               $this->text=$campo;
               
          }
          function event_type_asigna($campo)
          {
               $this->event_type=$campo;
               
          }
          function userId_asigna($campo)
          {
               $this->userId=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function event_id_inicial_asigna($campo)
          {
               $this->event_id_inicial=$campo;
               
          }
          function sesiones_asigna($campo)
          {
               $this->sesiones=$campo;
               
          }
          function sesion_actual_asigna($campo)
          {
               $this->sesion_actual=$campo;
               
          }
          function idfinanciacion_asigna($campo)
          {
               $this->idfinanciacion=$campo;
               
          }
          function motivo_consulta_asigna($campo)
          {
               $this->motivo_consulta=$campo;
               
          }
          
          
          
      
}
?>