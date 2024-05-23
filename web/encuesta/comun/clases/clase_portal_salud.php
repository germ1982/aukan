<?
      class clase_portal_salud       
      {
	      var $api_key = '';
          var $url = '';
      	  
      
      
         function clase_portal_salud()
         {
      	     $this->api_key = "ZVPnHLELTcsR4seFBEzyQfmyaRE36JdSw9WCU5QW7Ct3BBGSchJSpYjEqtAv";
      	     $this->url = "http://179.43.114.70/portal-salud/ws/usuario.php";
      	 }      	                                		                
          function api_key()
          {
               return $this->api_key;
          }
          function url()
          {
               return $this->url;
          }
          
          function api_key_asigna($campo)
          {
               $this->api_key=$campo;
               
          }
          function url_asigna($campo)
          {
               $this->url=$campo;
               
          }         
}
?>