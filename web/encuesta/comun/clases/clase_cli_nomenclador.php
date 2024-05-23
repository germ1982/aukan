<?

class clase_cli_nomenclador {

    var $id = '';
    var $codigo = '';
    var $descripcion = '';
    var $activado = '';
    var $activado_lab = '';
    var $codigo_contenedor = '';
    var $codigo_loinc = '';
    var $activado_pacs = '';
    var $baja_fecha = '';
    var $arreglo_foraneo_codigo_contenedor = '';
    var $arreglo_todos_activados = '';

    function clase_cli_nomenclador($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM cli_nomenclador WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->codigo = $arreglo['codigo'];
        $this->descripcion = $arreglo['descripcion'];
        $this->activado = $arreglo['activado'];
        $this->activado_lab = $arreglo['activado_lab'];
        $this->codigo_contenedor = $arreglo['codigo_contenedor'];
        $this->codigo_loinc = $arreglo['codigo_loinc'];
        $this->activado_pacs = $arreglo['activado_pacs'];
        $this->baja_fecha = $arreglo['baja_fecha'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO cli_nomenclador(codigo,descripcion,activado,activado_lab,codigo_contenedor,codigo_loinc,activado_pacs,baja_fecha) VALUES('" . $this->codigo . "','" . $this->descripcion . "','" . $this->activado . "','" . $this->activado_lab . "','" . $this->codigo_contenedor . "','" . $this->codigo_loinc . "','" . $this->activado_pacs . "','" . $this->baja_fecha . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE cli_nomenclador SET codigo='" . $this->codigo . "',descripcion='" . $this->descripcion . "',activado='" . $this->activado . "',activado_lab='" . $this->activado_lab . "',codigo_contenedor='" . $this->codigo_contenedor . "',codigo_loinc='" . $this->codigo_loinc . "',activado_pacs='" . $this->activado_pacs . "',baja_fecha='" . $this->baja_fecha . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function codigo() {
        return $this->codigo;
    }

    function descripcion() {
        return $this->descripcion;
    }

    function activado() {
        return $this->activado;
    }

    function activado_lab() {
        return $this->activado_lab;
    }

    function codigo_contenedor() {
        return $this->codigo_contenedor;
    }

    function codigo_loinc() {
        return $this->codigo_loinc;
    }

    function activado_pacs() {
        return $this->activado_pacs;
    }

    function baja_fecha() {
        return $this->baja_fecha;
    }

    function arreglo_foraneo_codigo_contenedor() {
        return $this->arreglo_foraneo_codigo_contenedor;
    }
    
    function arreglo_todos_activados() {
        return $this->arreglo_todos_activados;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function codigo_asigna($campo) {
        $this->codigo = $campo;
    }

    function descripcion_asigna($campo) {
        $this->descripcion = $campo;
    }

    function activado_asigna($campo) {
        $this->activado = $campo;
    }

    function activado_lab_asigna($campo) {
        $this->activado_lab = $campo;
    }

    function codigo_contenedor_asigna($campo) {
        $this->codigo_contenedor = $campo;
    }

    function codigo_loinc_asigna($campo) {
        $this->codigo_loinc = $campo;
    }

    function activado_pacs_asigna($campo) {
        $this->activado_pacs = $campo;
    }

    function baja_fecha_asigna($campo) {
        $this->baja_fecha = $campo;
    }

    function foranea_codigo_contenedor($codigo_contenedor) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM cli_nomenclador WHERE codigo_contenedor=$codigo_contenedor");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_codigo_contenedor = $pro;
    }
    function todos_activados() {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM cli_nomenclador WHERE activado_lab = 1 AND baja_fecha IS NULL ORDER  BY descripcion ASC");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_todos_activados = $pro;
    }

}

?>