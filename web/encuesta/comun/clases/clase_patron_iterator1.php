<?
class clase_patron_iterator {
    protected $lista;
    protected $fila_actual = 0;

    public function __construct(clase_listar $lista_in) {
      $this->lista = $lista_in;
    }
    public function elementoActual() {
      if (($this->fila_actual > 0) && 
          ($this->lista->filas() >= $this->fila_actual)) {
        return $this->lista->obtenerLista($this->fila_actual);
      }
    }
    public function elementoSiguiente() {
	  if ($this->existeElementoSiguiente()) {
        return $this->lista->obtenerLista(++$this->fila_actual);
      } else {
        return NULL;
      }
    }
    public function existeElementoSiguiente() {
      if ($this->lista->filas() > $this->fila_actual) {
	    return TRUE;
	  } else {
        return FALSE;
	  }
    }
} 
?>