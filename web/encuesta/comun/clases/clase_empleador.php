<?

class clase_empleador {

    var $id = '';
    var $nombre_empresa = '';
    var $cuit = '';
    var $domicilio = '';
    var $telefono = '';
    var $contacto = '';
    var $telefono_contacto = '';
    var $email = '';
    var $arreglo_todos_empleadores = '';

    function clase_empleador($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM empleador WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->nombre_empresa = $arreglo['nombre_empresa'];
        $this->cuit = $arreglo['cuit'];
        $this->domicilio = $arreglo['domicilio'];
        $this->telefono = $arreglo['telefono'];
        $this->contacto = $arreglo['contacto'];
        $this->telefono_contacto = $arreglo['telefono_contacto'];
        $this->email = $arreglo['email'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO empleador(nombre_empresa,cuit,domicilio,telefono,contacto,telefono_contacto,email) VALUES('" . $this->nombre_empresa . "','" . $this->cuit . "','" . $this->domicilio . "','" . $this->telefono . "','" . $this->contacto . "','" . $this->telefono_contacto . "','" . $this->email . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE empleador SET nombre_empresa='" . $this->nombre_empresa . "',cuit='" . $this->cuit . "',domicilio='" . $this->domicilio . "',telefono='" . $this->telefono . "',contacto='" . $this->contacto . "',telefono_contacto='" . $this->telefono_contacto . "',email='" . $this->email . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function nombre_empresa() {
        return $this->nombre_empresa;
    }

    function cuit() {
        return $this->cuit;
    }

    function domicilio() {
        return $this->domicilio;
    }

    function telefono() {
        return $this->telefono;
    }

    function contacto() {
        return $this->contacto;
    }

    function telefono_contacto() {
        return $this->telefono_contacto;
    }

    function email() {
        return $this->email;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function nombre_empresa_asigna($campo) {
        $this->nombre_empresa = $campo;
    }

    function cuit_asigna($campo) {
        $this->cuit = $campo;
    }

    function domicilio_asigna($campo) {
        $this->domicilio = $campo;
    }

    function telefono_asigna($campo) {
        $this->telefono = $campo;
    }

    function contacto_asigna($campo) {
        $this->contacto = $campo;
    }

    function telefono_contacto_asigna($campo) {
        $this->telefono_contacto = $campo;
    }

    function email_asigna($campo) {
        $this->email = $campo;
    }

    function todos_empleador_like($valor) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM empleador WHERE nombre_empresa LIKE '%$valor%' ORDER BY nombre_empresa");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_todos_empleadores = $pro;
    }

    function arreglo_todos_empleadores() {
        return $this->arreglo_todos_empleadores();
    }

}

?>