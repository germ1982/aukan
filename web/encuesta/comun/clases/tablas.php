<?php
class Tabla {
  private $mat=array();
  private $cantFilas;
  private $cantColumnas;
  private $tabla_final;
  public function __construct($fi,$co)
  {
    $this->cantFilas=$fi;
    $this->cantColumnas=$co;
  }

  public function cargar($fila,$columna,$valor)
  {
    $this->mat[$fila][$columna]=$valor;
  }

  public function inicioTabla($border)
  {
    $this->tabla_final = '<table border="$border">';
  }
    
  public function inicioFila($color)
  {
  	if ($color != '')
  	    $color_style = "style='background-color:#".$color."'";
    $this->tabla_final .= "<tr $color_style>";
  }

  public function mostrar($fi,$co,$color_campo)
  {
      if ($color_campo != '' && $this->mat[$fi][$co] != '') $color_style = "style='background-color:#".$color_campo."'"; else $color_style = '';
      
    $this->tabla_final .= "<td $color_style>".$this->mat[$fi][$co]."</td>";
  }

  public function finFila()
  {
    $this->tabla_final .= '</tr>';
  }

  public function finTabla()
  {
    $this->tabla_final .= '</table>';
  }

  public function graficar($border,$color_fila_uno,$color_fila_dos,$color_campo)
  {
    $this->inicioTabla($border);
    $bandera = 0;
    for($f=1;$f<=$this->cantFilas;$f++)
    {
    	if ($bandera == 0)
    	{ 
    	    $color_fila = $color_fila_uno;
    	    $bandera = 1; 
    	} 
    	else
    	{ 
    	    $color_fila = $color_fila_dos;
    	    $bandera = 0;
    	}
      $this->inicioFila($color_fila);
      for($c=1;$c<=$this->cantColumnas;$c++)
      {      	
        $this->mostrar($f,$c,$color_campo);
      }
      $this->finFila();
    }
    $this->finTabla();
    return $this->tabla_final;
  }
}


?>
