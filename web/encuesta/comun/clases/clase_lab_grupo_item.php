<?

class clase_lab_grupo_item {

    var $id = '';
    var $descripcion = '';
    var $arreglo_todos_lab_grupo_item = '';
    
    function clase_lab_grupo_item($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM lab_grupo_item WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->descripcion = $arreglo['descripcion'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO lab_grupo_item(descripcion) VALUES('" . $this->descripcion . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE lab_grupo_item SET descripcion='" . $this->descripcion . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function descripcion() {
        return $this->descripcion;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function descripcion_asigna($campo) {
        $this->descripcion = $campo;
    }

    function todos_lab_grupo_item() {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM lab_grupo_item ORDER BY descripcion");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_todos_lab_grupo_item = $pro;
    }
    function arreglo_todos_lab_grupo_item(){
        return $this->arreglo_todos_lab_grupo_item();
     }
}

?>