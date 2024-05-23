<?

class clase_proveedor {

    var $idproveedor = '';
    var $razonsocial = '';
    var $direccion = '';
    var $localidad = '';
    var $provincia = '';
    var $codigo_postal = '';
    var $telefono = '';
    var $fax = '';
    var $cuit = '';
    var $mail = '';
    var $responsable = '';
    var $arreglo_proveedor = '';

    function clase_proveedor($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM proveedor WHERE idproveedor=$id");
        $arreglo = $bd->registro();
        $this->idproveedor = $arreglo['idproveedor'];
        $this->razonsocial = $arreglo['razonsocial'];
        $this->direccion = $arreglo['direccion'];
        $this->localidad = $arreglo['localidad'];
        $this->provincia = $arreglo['provincia'];
        $this->codigo_postal = $arreglo['codigo_postal'];
        $this->telefono = $arreglo['telefono'];
        $this->fax = $arreglo['fax'];
        $this->cuit = $arreglo['cuit'];
        $this->mail = $arreglo['mail'];
        $this->responsable = $arreglo['responsable'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->idproveedor == 0 || $this->idproveedor == '') {
            if ($bd->select("INSERT INTO proveedor(razonsocial,direccion,localidad,provincia,codigo_postal,telefono,fax,cuit,mail,responsable) VALUES('" . $this->razonsocial . "','" . $this->direccion . "','" . $this->localidad . "','" . $this->provincia . "','" . $this->codigo_postal . "','" . $this->telefono . "','" . $this->fax . "','" . $this->cuit . "','" . $this->mail . "','" . $this->responsable . "')")) {
                $this->idproveedor = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE proveedor SET razonsocial='" . $this->razonsocial . "',direccion='" . $this->direccion . "',localidad='" . $this->localidad . "',provincia='" . $this->provincia . "',codigo_postal='" . $this->codigo_postal . "',telefono='" . $this->telefono . "',fax='" . $this->fax . "',cuit='" . $this->cuit . "',mail='" . $this->mail . "',responsable='" . $this->responsable . "' WHERE idproveedor='" . $this->idproveedor . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function idproveedor() {
        return $this->idproveedor;
    }

    function razonsocial() {
        return $this->razonsocial;
    }

    function direccion() {
        return $this->direccion;
    }

    function localidad() {
        return $this->localidad;
    }

    function provincia() {
        return $this->provincia;
    }

    function codigo_postal() {
        return $this->codigo_postal;
    }

    function telefono() {
        return $this->telefono;
    }

    function fax() {
        return $this->fax;
    }

    function cuit() {
        return $this->cuit;
    }

    function mail() {
        return $this->mail;
    }

    function responsable() {
        return $this->responsable;
    }

    function idproveedor_asigna($campo) {
        $this->idproveedor = $campo;
    }

    function razonsocial_asigna($campo) {
        $this->razonsocial = $campo;
    }

    function direccion_asigna($campo) {
        $this->direccion = $campo;
    }

    function localidad_asigna($campo) {
        $this->localidad = $campo;
    }

    function provincia_asigna($campo) {
        $this->provincia = $campo;
    }

    function codigo_postal_asigna($campo) {
        $this->codigo_postal = $campo;
    }

    function telefono_asigna($campo) {
        $this->telefono = $campo;
    }

    function fax_asigna($campo) {
        $this->fax = $campo;
    }

    function cuit_asigna($campo) {
        $this->cuit = $campo;
    }

    function mail_asigna($campo) {
        $this->mail = $campo;
    }

    function responsable_asigna($campo) {
        $this->responsable = $campo;
    }
    
    function arreglo_proveedor() {
        return $this->arreglo_proveedor;
    }
    
    function todos_proveedores() {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM proveedor ORDER BY razonsocial ASC");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['idproveedor'] != '' && $fila['idproveedor'] != 0)
                $pro->introducirElemento($fila);
        }
        $this->arreglo_proveedor = $pro;
    }

}

?>