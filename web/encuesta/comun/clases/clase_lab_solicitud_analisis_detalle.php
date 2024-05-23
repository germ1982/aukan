<?

class clase_lab_solicitud_analisis_detalle {

    var $id = '';
    var $cli_nomenclador_item_id = '';
    var $resultado = '';
    var $resultado_fecha = '';
    var $lab_pedido_analisis_id = '';
    var $arreglo_foraneo_cli_nomenclador_item_id = '';
    var $arreglo_foraneo_lab_pedido_analisis_id = '';

    function clase_lab_solicitud_analisis_detalle($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM lab_solicitud_analisis_detalle WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->cli_nomenclador_item_id = $arreglo['cli_nomenclador_item_id'];
        $this->resultado = $arreglo['resultado'];
        $this->resultado_fecha = $arreglo['resultado_fecha'];
        $this->lab_pedido_analisis_id = $arreglo['lab_pedido_analisis_id'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO lab_solicitud_analisis_detalle(cli_nomenclador_item_id,resultado,resultado_fecha,lab_pedido_analisis_id) VALUES('" . $this->cli_nomenclador_item_id . "','" . $this->resultado . "','" . $this->resultado_fecha . "','" . $this->lab_pedido_analisis_id . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE lab_solicitud_analisis_detalle SET cli_nomenclador_item_id='" . $this->cli_nomenclador_item_id . "',resultado='" . $this->resultado . "',resultado_fecha='" . $this->resultado_fecha . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function cli_nomenclador_item_id() {
        return $this->cli_nomenclador_item_id;
    }

    function resultado() {
        return $this->resultado;
    }

    function resultado_fecha() {
        return $this->resultado_fecha;
    }

    function lab_pedido_analisis_id() {
        return $this->lab_pedido_analisis_id;
    }

    function arreglo_foraneo_cli_nomenclador_item_id() {
        return $this->arreglo_foraneo_cli_nomenclador_item_id;
    }

    function arreglo_foraneo_lab_pedido_analisis_id() {
        return $this->arreglo_foraneo_lab_pedido_analisis_id;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function cli_nomenclador_item_id_asigna($campo) {
        $this->cli_nomenclador_item_id = $campo;
    }

    function resultado_asigna($campo) {
        $this->resultado = $campo;
    }

    function resultado_fecha_asigna($campo) {
        $this->resultado_fecha = $campo;
    }

    function lab_pedido_analisis_id_asigna($campo) {
        $this->lab_pedido_analisis_id = $campo;
    }

    function foranea_cli_nomenclador_item_id($cli_nomenclador_item_id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM lab_solicitud_analisis_detalle WHERE cli_nomenclador_item_id=$cli_nomenclador_item_id");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_cli_nomenclador_item_id = $pro;
    }

    function foranea_lab_pedido_analisis_id($lab_pedido_analisis_id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM lab_solicitud_analisis_detalle WHERE lab_pedido_analisis_id=$lab_pedido_analisis_id");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_lab_pedido_analisis_id = $pro;
    }
    function delete_foranea_lab_pedido_analisis_id($lab_pedido_analisis_id){
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("DELETE FROM lab_solicitud_analisis_detalle WHERE lab_pedido_analisis_id = $lab_pedido_analisis_id");        
    }

}

?>