<?

class clase_encuesta {

    var $id = '';
    var $idpaciente = '';
    var $idpaciente_temp = '';
    var $fecha_creacion = '';
    var $completa = '';
    var $baja_fecha = '';
    var $arreglo_foraneo_documento = '';

    function clase_encuesta($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM encuesta WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->idpaciente = $arreglo['idpaciente'];
        $this->idpaciente_temp = $arreglo['idpaciente_temp'];
        $this->fecha_creacion = $arreglo['fecha_creacion'];
        $this->completa = $arreglo['completa'];
        $this->baja_fecha = $arreglo['baja_fecha'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO encuesta(idpaciente,idpaciente_temp,fecha_creacion,completa,baja_fecha) VALUES('" . $this->idpaciente . "','" . $this->idpaciente_temp . "','" . $this->fecha_creacion . "','" . $this->completa . "','" . $this->baja_fecha . "')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE encuesta SET idpaciente='" . $this->idpaciente . "',idpaciente_temp='" . $this->idpaciente_temp . "',fecha_creacion='" . $this->fecha_creacion . "',completa='" . $this->completa . "',baja_fecha='" . $this->baja_fecha . "' WHERE id='" . $this->id . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function id() {
        return $this->id;
    }

    function idpaciente() {
        return $this->idpaciente;
    }

    function idpaciente_temp() {
        return $this->idpaciente_temp;
    }

    function fecha_creacion() {
        return $this->fecha_creacion;
    }

    function completa() {
        return $this->completa;
    }

    function baja_fecha() {
        return $this->baja_fecha;
    }
    
    function arreglo_foraneo_documento() {
        return $this->arreglo_foraneo_documento;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function idpaciente_asigna($campo) {
        $this->idpaciente = $campo;
    }

    function idpaciente_temp_asigna($campo) {
        $this->idpaciente_temp = $campo;
    }

    function fecha_creacion_asigna($campo) {
        $this->fecha_creacion = $campo;
    }

    function completa_asigna($campo) {
        $this->completa = $campo;
    }

    function baja_fecha_asigna($campo) {
        $this->baja_fecha = $campo;
    }

    function arreglo_foraneo_documento_asigna($campo) {
        $this->arreglo_foraneo_documento = $campo;
    }

    function encuesta_documento($documento,$idprofesional) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd1 = new baseDatos();
        $bd1->Conectarse();
        $bd->select("SELECT e.*,pac.idpaciente,pac.nombre as paciente,pac.documento,pac.localidad,pac.telefono_celular,pac.mail
        FROM encuesta AS e
        join pacientes_temp as pac ON (e.idpaciente_temp = pac.idpaciente)
        WHERE pac.documento = '$documento' AND e.baja_fecha IS NULL AND completa = 1 AND e.idpaciente IS NULL");
        $pro = '';
        if ($bd->numero_filas() > 0) {
            while ($paquete = $bd->registro()) {
                //busco el detalle 
                $id = $paquete['id'];
                $fecha_creacion = date('d/m/Y', strtotime($paquete['fecha_creacion']));
                $paciente = $paquete['paciente'];
                $idpaciente = $paquete['idpaciente'];
                $documento = $paquete['documento'];
                $localidad = $paquete['localidad'];
                $telefono_celular = $paquete['telefono_celular'];
                $mail = $paquete['mail'];
                $pac = json_encode($paquete);
                $pro .= "<tr><td>$paciente</td><td>$fecha_creacion</td><td><button id='verificar_datos' class='cursor-point btn btn-success block-button' onclick='verificarDatos($idpaciente,$documento,$id,$idprofesional,$pac);'>Verificar Datos</button></td></tr>";
            }
        }
        syslog(LOG_NOTICE, "pro " . $pro);
        $this->arreglo_foraneo_documento = $pro;
    }

}

?>