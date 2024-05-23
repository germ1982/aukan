<?php

class clase_reserva_turnos_portal_empresa {

    var $id = '';
    var $id_tipo_encuesta = '';
    var $idprofesional = '';
    var $fecha_hora_solicitud = '';
    var $cant_empleados = '';
    var $fecha_turno = '';
    var $arreglo_foraneo_id_tipo_encuesta = '';

    function clase_reserva_turnos_portal_empresa($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM reserva_turnos_portal_empresa WHERE id=$id AND baja_fecha IS NULL");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->id_tipo_encuesta = $arreglo['id_tipo_encuesta'];
        $this->idprofesional = $arreglo['idprofesional'];
        $this->fecha_hora_solicitud = $arreglo['fecha_hora_solicitud'];
        $this->cant_empleados = $arreglo['cant_empleados'];
        $this->fecha_turno = $arreglo['fecha_turno'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            error_log("INSERT INTO reserva_turnos_portal_empresa(id_tipo_encuesta,idprofesional,fecha_hora_solicitud,cant_empleados,fecha_turno) VALUES('" . $this->id_tipo_encuesta . "','" . $this->idprofesional . "','" . $this->fecha_hora_solicitud . "','" . $this->cant_empleados . "','" . $this->fecha_turno . "')");
            if ($bd->select("INSERT INTO reserva_turnos_portal_empresa(id_tipo_encuesta,idprofesional,fecha_hora_solicitud,cant_empleados,fecha_turno) VALUES('" . $this->id_tipo_encuesta . "','" . $this->idprofesional . "','" . $this->fecha_hora_solicitud . "','" . $this->cant_empleados . "','" . $this->fecha_turno . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE reserva_turnos_portal_empresa SET id_tipo_encuesta='" . $this->id_tipo_encuesta . "',idprofesional='" . $this->idprofesional . "',fecha_hora_solicitud='" . $this->fecha_hora_solicitud . "',cant_empleados='" . $this->cant_empleados . "',fecha_turno='" . $this->fecha_turno . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function cancelar_turno($id){
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($bd->select("UPDATE reserva_turnos_portal_empresa SET baja_fecha = '".date('Y-m-d H:i:s')."' WHERE id = $id"))
            return 1;
        else
            return 0;
    }
    
    function id() {
        return $this->id;
    }

    function id_tipo_encuesta() {
        return $this->id_tipo_encuesta;
    }

    function idprofesional() {
        return $this->idprofesional;
    }

    function fecha_hora_solicitud() {
        return $this->fecha_hora_solicitud;
    }

    function cant_empleados() {
        return $this->cant_empleados;
    }

    function fecha_turno() {
        return $this->fecha_turno;
    }

    function arreglo_foraneo_id_tipo_encuesta() {
        return $this->arreglo_foraneo_id_tipo_encuesta;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function id_tipo_encuesta_asigna($campo) {
        $this->id_tipo_encuesta = $campo;
    }

    function idprofesional_asigna($campo) {
        $this->idprofesional = $campo;
    }

    function fecha_hora_solicitud_asigna($campo) {
        $this->fecha_hora_solicitud = $campo;
    }

    function cant_empleados_asigna($campo) {
        $this->cant_empleados = $campo;
    }

    function fecha_turno_asigna($campo) {
        $this->fecha_turno = $campo;
    }

    function foranea_id_tipo_encuesta($id_tipo_encuesta) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM reserva_turnos_portal_empresa WHERE id_tipo_encuesta=$id_tipo_encuesta AND baja_fecha IS NULL");
        $pro = array();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0){
                $fila['fecha_turno'] = devolverFechaNormal($fila['fecha_turno']);
                $pro[$i] = $fila;
            }
        }
              $this->arreglo_foraneo_id_tipo_encuesta = $pro;
       
    }
function buscar_turnos_todas_empresas_fechas($fdesde,$fhasta) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT fecha_turno,nombre,id,cant_empleados 
                     FROM reserva_turnos_portal_empresa LEFT JOIN financiacion ON (id_tipo_encuesta=idfinanciacion)
                     WHERE baja_fecha IS NULL AND fecha_turno>='".fechaBase($fdesde)."' AND fecha_turno<='".fechaBase($fhasta)."' ORDER BY fecha_turno ASC,id_tipo_encuesta ASC");
        $pro = array();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            if ($fila['id'] != '' && $fila['id'] != 0){
                $fila['fecha_turno'] = devolverFechaNormal($fila['fecha_turno']);
                $pro[$i] = $fila;
            }
        }
        $this->arreglo_foraneo_id_tipo_encuesta = $pro;
    }

}

?>
