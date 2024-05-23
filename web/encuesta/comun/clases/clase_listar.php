<?
class clase_listar {
    private $arreglo = array();
	private $arreglo_cantidad = 0;
    public function __construct() { }
    public function filas() { return $this->arreglo_cantidad;  }
    private function cantidad_filas($newCount) {
      $this->arreglo_cantidad = $newCount; }
    public function obtenerLista($fila) {
	  if ( (is_numeric($fila)) && 
           ($fila <= $this->filas())) {
           return $this->arreglo[$fila];
         } else {
           return NULL;
         }
	}
    public function introducirElemento($elemento) {
      $this->cantidad_filas($this->filas() + 1);
      $this->arreglo[$this->filas()] = $elemento;
      return $this->filas();
    }

}
?>