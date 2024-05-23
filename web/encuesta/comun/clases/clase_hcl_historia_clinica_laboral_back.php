<?

class clase_hcl_historia_clinica_laboral {

    var $idprofesional = '';
    var $idpaciente = '';
    var $fecha = '';
    var $hora = '';
    var $descripcion = '';
    var $proximavisita = '';
    var $es_ultima_visita = '';
    var $numero = '';
    var $interconsulta = '';
    var $fq = '';
    var $tac_rnm = '';
    var $tratamiento = '';
    var $cirugia = '';
    var $nada = '';
    var $rx = '';
    var $numero_transaccion = '';
    var $arreglo_foraneo_idprofesional = '';
    var $arreglo_foraneo_idpaciente = '';
    var $arreglo_foraneo_idpaciente_numero = '';
    var $arreglo_foraneo_fecha = '';

    function clase_hcl_historia_clinica_laboral($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE numero_transaccion=$id");
        $arreglo = $bd->registro();
        self::asignar($arreglo);
    }
    function asignar($arreglo)
    {
        $this->idprofesional = $arreglo['idprofesional'];
        $this->idpaciente = $arreglo['idpaciente'];
        $this->fecha = $arreglo['fecha'];
        $this->hora = $arreglo['hora'];
        $this->descripcion = $arreglo['descripcion'];
        $this->proximavisita = $arreglo['proximavisita'];
        $this->es_ultima_visita = $arreglo['es_ultima_visita'];
        $this->numero = $arreglo['numero'];
        $this->interconsulta = $arreglo['interconsulta'];
        $this->fq = $arreglo['fq'];
        $this->tac_rnm = $arreglo['tac_rnm'];
        $this->tratamiento = $arreglo['tratamiento'];
        $this->cirugia = $arreglo['cirugia'];
        $this->nada = $arreglo['nada'];
        $this->rx = $arreglo['rx'];
        $this->numero_transaccion = $arreglo['numero_transaccion'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->numero_transaccion == 0 || $this->numero_transaccion == '') {
            if ($bd->select("INSERT INTO hcl_historia_clinica_laboral(idprofesional,idpaciente,fecha,hora,descripcion,proximavisita,es_ultima_visita,numero,interconsulta,fq,tac_rnm,tratamiento,cirugia,nada,rx) VALUES('" . $this->idprofesional . "','" . $this->idpaciente . "','" . $this->fecha . "','" . $this->hora . "','" . $bd->parser($this->descripcion) . "','" . $this->proximavisita . "','" . $this->es_ultima_visita . "','" . $this->numero . "','" . $this->interconsulta . "','" . $this->fq . "','" . $this->tac_rnm . "','" . $this->tratamiento . "','" . $this->cirugia . "','" . $this->nada . "','" . $this->rx . "')")) {
                $this->numero_transaccion = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE hcl_historia_clinica_laboral SET idprofesional='" . $this->idprofesional . "',idpaciente='" . $this->idpaciente . "',fecha='" . $this->fecha . "',hora='" . $this->hora . "',descripcion='" . $bd->parser($this->descripcion) . "',proximavisita='" . $this->proximavisita . "',es_ultima_visita='" . $this->es_ultima_visita . "',numero='" . $this->numero . "',interconsulta='" . $this->interconsulta . "',fq='" . $this->fq . "',tac_rnm='" . $this->tac_rnm . "',tratamiento='" . $this->tratamiento . "',cirugia='" . $this->cirugia . "',nada='" . $this->nada . "',rx='" . $this->rx . "' WHERE numero_transaccion='" . $this->numero_transaccion . "'")) {

                return 1;
            } else
                return 0;
        }
    }

    function idprofesional() {
        return $this->idprofesional;
    }

    function idpaciente() {
        return $this->idpaciente;
    }

    function fecha() {
        return $this->fecha;
    }

    function hora() {
        return $this->hora;
    }

    function descripcion() {
        return $this->descripcion;
    }

    function proximavisita() {
        return $this->proximavisita;
    }

    function es_ultima_visita() {
        return $this->es_ultima_visita;
    }

    function numero() {
        return $this->numero;
    }

    function interconsulta() {
        return $this->interconsulta;
    }

    function fq() {
        return $this->fq;
    }

    function tac_rnm() {
        return $this->tac_rnm;
    }

    function tratamiento() {
        return $this->tratamiento;
    }

    function cirugia() {
        return $this->cirugia;
    }

    function nada() {
        return $this->nada;
    }

    function rx() {
        return $this->rx;
    }

    function numero_transaccion() {
        return $this->numero_transaccion;
    }

    function arreglo_foraneo_idprofesional() {
        return $this->arreglo_foraneo_idprofesional;
    }

    function arreglo_foraneo_idpaciente() {
        return $this->arreglo_foraneo_idpaciente;
    }

    function arreglo_foraneo_idpaciente_numero() {
        return $this->arreglo_foraneo_idpaciente_numero;
    }

    function arreglo_foraneo_fecha() {
        return $this->arreglo_foraneo_fecha;
    }

    function idprofesional_asigna($campo) {
        $this->idprofesional = $campo;
    }

    function idpaciente_asigna($campo) {
        $this->idpaciente = $campo;
    }

    function fecha_asigna($campo) {
        $this->fecha = $campo;
    }

    function hora_asigna($campo) {
        $this->hora = $campo;
    }

    function descripcion_asigna($campo) {
        $this->descripcion = $campo;
    }

    function proximavisita_asigna($campo) {
        $this->proximavisita = $campo;
    }

    function es_ultima_visita_asigna($campo) {
        $this->es_ultima_visita = $campo;
    }

    function numero_asigna($campo) {
        $this->numero = $campo;
    }

    function interconsulta_asigna($campo) {
        $this->interconsulta = $campo;
    }

    function fq_asigna($campo) {
        $this->fq = $campo;
    }

    function tac_rnm_asigna($campo) {
        $this->tac_rnm = $campo;
    }

    function tratamiento_asigna($campo) {
        $this->tratamiento = $campo;
    }

    function cirugia_asigna($campo) {
        $this->cirugia = $campo;
    }

    function nada_asigna($campo) {
        $this->nada = $campo;
    }

    function rx_asigna($campo) {
        $this->rx = $campo;
    }

    function numero_transaccion_asigna($campo) {
        $this->numero_transaccion = $campo;
    }

    function foranea_idprofesional($idprofesional) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE idprofesional=$idprofesional");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idprofesional = $pro;
    }

    function foranea_idpaciente($idpaciente) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE idpaciente=$idpaciente");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idpaciente = $pro;
    }

    function foranea_idpaciente_numero($idpaciente, $numero,$order) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE idpaciente=$idpaciente AND numero = $numero ORDER BY fecha $order, hora $order");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idpaciente_numero = $pro;
    }

    function foranea_fecha($fecha) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE fecha=$fecha");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_fecha = $pro;
    }
    function foranea_idpaciente_dado_alta($idpaciente) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_historia_clinica_laboral WHERE idpaciente=$idpaciente AND es_ultima_visita = 1 ORDER BY numero_transaccion DESC");
        self::asignar($bd->registro());
    }

}

?>
