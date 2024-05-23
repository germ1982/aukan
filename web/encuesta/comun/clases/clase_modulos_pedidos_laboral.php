<?

class clase_modulos_pedidos_laboral {

    var $id = '';
    var $nombre = '';
    var $arreglo_modulos_pedidos_laboral = '';
    var $arreglo_modulos_nombre = '';

    function clase_modulos_pedidos_laboral($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM modulos_pedidos_laboral WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->nombre = $arreglo['nombre'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO modulos_pedidos_laboral(nombre) VALUES('" . $this->nombre . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE modulos_pedidos_laboral SET nombre='" . $this->nombre . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function nombre() {
        return $this->nombre;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function nombre_asigna($campo) {
        $this->nombre = $campo;
    }

    function todos_modulos() {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM modulos_pedidos_laboral ORDER BY nombre ASC");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0)
                $pro->introducirElemento($fila);
        }
        $this->arreglo_modulos_pedidos_laboral = $pro;
    }

    function modulos_por_nombre($texto) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM modulos_pedidos_laboral WHERE nombre like '%$texto%'");
        $pro = '';

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0) {
                $fila['fecha_turno'] = devolverFechaNormal($fila['fecha_turno']);
                $pro .= "<tr><td class='cursor-point' onClick=\"$('#paquete_idmodulo1').val('".$fila['nombre']."');
    $('#paquete_idmodulo').val(".$fila['id'].");
    $('#modulos_buscados tbody').html('');//asignarModulo(".$fila['id'].",'".$fila['nombre']."')\">".$fila['nombre']."</td></tr>";
            }
        }
        $this->arreglo_modulos_nombre = $pro;
    }
function arreglo_modulos_nombre()
    {
        return $this->arreglo_modulos_nombre;
                
    }
    function arreglo_modulos_pedidos_laboral() {
        return $this->arreglo_modulos_pedidos_laboral;
    }

    function devolver_estudios($modulo) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM modulos_pedidos_laboral_detalle WHERE idmodulo_pedido_laboral=$modulo");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0)
                $pro->introducirElemento($fila);
        }
        $this->arreglo_modulos_pedidos_laboral = $pro;
    }

    function devolver_estudios_tipo($modulo, $tipo) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM modulos_pedidos_laboral_detalle WHERE idmodulo_pedido_laboral=$modulo AND tipo=$tipo");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0)
                $pro->introducirElemento($fila);
        }
        $this->arreglo_modulos_pedidos_laboral = $pro;
    }

}

?>