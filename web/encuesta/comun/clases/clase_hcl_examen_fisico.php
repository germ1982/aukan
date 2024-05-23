<?

class clase_hcl_examen_fisico {

    var $id = '';
    var $idpaciente = '';
    var $fecha = '';
    var $idprofesional = '';
    var $peso = '';
    var $talla = '';
    var $imc = '';
    var $temp = '';
    var $fc = '';
    var $dentadura = '';
    var $pupilas_isocoricas = '';
    var $pulso = '';
    var $pterigion = '';
    var $pterigion_ubicacion = '';
    var $tension_arterial = '';
    var $tension_arterial_maxima = '';
    var $vision_od = '';
    var $vision_oi = '';
    var $vision_cromatica = '';
    var $correccion_od = '';
    var $correccion_oi = '';
    var $cuello_adenomegalias = '';
    var $amigdalas = '';
    var $oidos_timpano_derecho = '';
    var $oidos_timpano_izquierdo = '';
    var $nariz_permeable = '';
    var $nariz_hipertrofias = '';
    var $acv_auscultacion_r1 = '';
    var $acv_auscultacion_r2 = '';
    var $acv_soplos = '';
    var $ar_murmullo_vesicular = '';
    var $ar_vibraciones_vocales = '';
    var $ar_ruidos_agregados = '';
    var $ar_ruidos_agregados_detalle = '';
    var $abdomen = '';
    var $abdomen_blando = '';
    var $abdomen_depresible = '';
    var $abdomen_indoloro = '';
    var $higado = '';
    var $higado_reborde_costal = '';
    var $bazo = '';
    var $hernias = '';
    var $hernia_epigastrica = '';
    var $hernia_umbilical = '';
    var $hernia_inglinal = '';
    var $cicatrices = '';
    var $cicatrices_lugar = '';
    var $piel = '';
    var $columna = '';
    var $col_eje_cervical = '';
    var $col_eje_dorsal = '';
    var $col_eje_lumbar = '';
    var $col_desejes = '';
    var $og_varicocele = '';
    var $og_hidrocele = '';
    var $og_testiculos = '';
    var $reflejos_tendinosos = '';
    var $varices_grado = '';
    var $observaciones = '';
    var $hernia_descripcion = '';
    var $varices_lugar = '';
    var $varices_descripcion = '';
    var $aparato_locomotor_msd = '';
    var $aparato_locomotor_msd_lugar = '';
    var $aparato_locomotor_msi = '';
    var $aparato_locomotor_msi_lugar = '';
    var $aparato_locomotor_mid = '';
    var $aparato_locomotor_mid_lugar = '';
    var $aparato_locomotor_mii = '';
    var $aparato_locomotor_mii_lugar = '';
    var $aparato_locomotor = '';
    var $programada = '';
    var $id_encuesta = '';
    var $arreglo_foraneo_idpaciente = '';
    var $arreglo_foraneo_idprofesional = '';
    var $saturacion = '';

    function clase_hcl_examen_fisico($id) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_examen_fisico WHERE id=$id");
        $arreglo = $bd->registro();
        $this->id = $arreglo['id'];
        $this->idpaciente = $arreglo['idpaciente'];
        $this->fecha = $arreglo['fecha'];
        $this->idprofesional = $arreglo['idprofesional'];
        $this->peso = $arreglo['peso'];
        $this->talla = $arreglo['talla'];
        $this->imc = $arreglo['imc'];
        $this->temp = $arreglo['temp'];
        $this->fc = $arreglo['fc'];
        $this->dentadura = $arreglo['dentadura'];
        $this->pupilas_isocoricas = $arreglo['pupilas_isocoricas'];
        $this->pulso = $arreglo['pulso'];
        $this->pterigion = $arreglo['pterigion'];
        $this->pterigion_ubicacion = $arreglo['pterigion_ubicacion'];
        $this->tension_arterial = $arreglo['tension_arterial'];
        $this->tension_arterial_maxima = $arreglo['tension_arterial_maxima'];
        $this->vision_od = $arreglo['vision_od'];
        $this->vision_oi = $arreglo['vision_oi'];
        $this->vision_cromatica = $arreglo['vision_cromatica'];
        $this->correccion_od = $arreglo['correccion_od'];
        $this->correccion_oi = $arreglo['correccion_oi'];
        $this->cuello_adenomegalias = $arreglo['cuello_adenomegalias'];
        $this->amigdalas = $arreglo['amigdalas'];
        $this->oidos_timpano_derecho = $arreglo['oidos_timpano_derecho'];
        $this->oidos_timpano_izquierdo = $arreglo['oidos_timpano_izquierdo'];
        $this->nariz_permeable = $arreglo['nariz_permeable'];
        $this->nariz_hipertrofias = $arreglo['nariz_hipertrofias'];
        $this->acv_auscultacion_r1 = $arreglo['acv_auscultacion_r1'];
        $this->acv_auscultacion_r2 = $arreglo['acv_auscultacion_r2'];
        $this->acv_soplos = $arreglo['acv_soplos'];
        $this->ar_murmullo_vesicular = $arreglo['ar_murmullo_vesicular'];
        $this->ar_vibraciones_vocales = $arreglo['ar_vibraciones_vocales'];
        $this->ar_ruidos_agregados = $arreglo['ar_ruidos_agregados'];
        $this->ar_ruidos_agregados_detalle = $arreglo['ar_ruidos_agregados_detalle'];
        $this->abdomen = $arreglo['abdomen'];
        $this->abdomen_blando = $arreglo['abdomen_blando'];
        $this->abdomen_depresible = $arreglo['abdomen_depresible'];
        $this->abdomen_indoloro = $arreglo['abdomen_indoloro'];
        $this->higado = $arreglo['higado'];
        $this->higado_reborde_costal = $arreglo['higado_reborde_costal'];
        $this->bazo = $arreglo['bazo'];
        $this->hernias = $arreglo['hernias'];
        $this->hernia_epigastrica = $arreglo['hernia_epigastrica'];
        $this->hernia_umbilical = $arreglo['hernia_umbilical'];
        $this->hernia_inglinal = $arreglo['hernia_inglinal'];
        $this->cicatrices = $arreglo['cicatrices'];
        $this->cicatrices_lugar = $arreglo['cicatrices_lugar'];
        $this->piel = $arreglo['piel'];
        $this->columna = $arreglo['columna'];
        $this->col_eje_cervical = $arreglo['col_eje_cervical'];
        $this->col_eje_dorsal = $arreglo['col_eje_dorsal'];
        $this->col_eje_lumbar = $arreglo['col_eje_lumbar'];
        $this->col_desejes = $arreglo['col_desejes'];
        $this->og_varicocele = $arreglo['og_varicocele'];
        $this->og_hidrocele = $arreglo['og_hidrocele'];
        $this->og_testiculos = $arreglo['og_testiculos'];
        $this->reflejos_tendinosos = $arreglo['reflejos_tendinosos'];
        $this->varices_grado = $arreglo['varices_grado'];
        $this->observaciones = $arreglo['observaciones'];
        $this->hernia_descripcion = $arreglo['hernia_descripcion'];
        $this->varices_lugar = $arreglo['varices_lugar'];
        $this->varices_descripcion = $arreglo['varices_descripcion'];
        $this->aparato_locomotor_msd = $arreglo['aparato_locomotor_msd'];
        $this->aparato_locomotor_msd_lugar = $arreglo['aparato_locomotor_msd_lugar'];
        $this->aparato_locomotor_msi = $arreglo['aparato_locomotor_msi'];
        $this->aparato_locomotor_msi_lugar = $arreglo['aparato_locomotor_msi_lugar'];
        $this->aparato_locomotor_mid = $arreglo['aparato_locomotor_mid'];
        $this->aparato_locomotor_mid_lugar = $arreglo['aparato_locomotor_mid_lugar'];
        $this->aparato_locomotor_mii = $arreglo['aparato_locomotor_mii'];
        $this->aparato_locomotor_mii_lugar = $arreglo['aparato_locomotor_mii_lugar'];
        $this->aparato_locomotor = $arreglo['aparato_locomotor'];
        $this->programada = $arreglo['programada'];
        $this->id_encuesta = $arreglo['id_encuesta'];
        $this->saturacion = $arreglo['saturacion'];
    }

    function guardar() {
        $bd = new baseDatos();
        $bd->Conectarse();
        if ($this->id == 0 || $this->id == '') {
            if ($bd->select("INSERT INTO hcl_examen_fisico(idpaciente,fecha,idprofesional,peso,talla,imc,temp,fc,dentadura,"
                            . "pupilas_isocoricas,pulso,pterigion,pterigion_ubicacion,tension_arterial,vision_od,vision_oi,vision_cromatica,"
                            . "correccion_od,correccion_oi,cuello_adenomegalias,amigdalas,oidos_timpano_derecho,oidos_timpano_izquierdo,"
                            . "nariz_permeable,nariz_hipertrofias,acv_auscultacion_r1,acv_auscultacion_r2,acv_soplos,ar_murmullo_vesicular,"
                            . "ar_vibraciones_vocales,ar_ruidos_agregados,ar_ruidos_agregados_detalle,abdomen,abdomen_blando,abdomen_depresible,"
                            . "abdomen_indoloro,higado,higado_reborde_costal,bazo,hernias,hernia_epigastrica,hernia_umbilical,"
                            . "hernia_inglinal,cicatrices,cicatrices_lugar,piel,columna,col_eje_cervical,col_eje_dorsal,col_eje_lumbar,col_desejes,"
                            . "og_varicocele,og_hidrocele,og_testiculos,reflejos_tendinosos,varices_grado,observaciones,hernia_descripcion,"
                            . "varices_lugar,varices_descripcion,"
                            . "aparato_locomotor_msd,aparato_locomotor_msd_lugar,aparato_locomotor_msi,aparato_locomotor_msi_lugar,"
                            . "aparato_locomotor_mid,aparato_locomotor_mid_lugar,aparato_locomotor_mii,aparato_locomotor_mii_lugar,aparato_locomotor,"
                    . "programada,id_encuesta,tension_arterial_maxima,saturacion) VALUES('" . $this->idpaciente . "','" . $this->fecha . "','" . $this->idprofesional . "',"
                            . "'" . $this->peso . "','" . $this->talla . "','" . $this->imc . "','" . $this->temp . "','" . $this->fc . "','" . $this->dentadura . "',"
                            . "'" . $this->pupilas_isocoricas . "','" . $this->pulso . "','" . $this->pterigion . "','" . $this->pterigion_ubicacion . "',"
                            . "'" . $this->tension_arterial . "','" . $this->vision_od . "','" . $this->vision_oi . "','" . $this->vision_cromatica . "',"
                            . "'" . $this->correccion_od . "','" . $this->correccion_oi . "','" . $this->cuello_adenomegalias . "','" . $this->amigdalas . "',"
                            . "'" . $this->oidos_timpano_derecho . "','" . $this->oidos_timpano_izquierdo . "','" . $this->nariz_permeable . "',"
                            . "'" . $this->nariz_hipertrofias . "','" . $this->acv_auscultacion_r1 . "','" . $this->acv_auscultacion_r2 . "',"
                            . "'" . $this->acv_soplos . "','" . $this->ar_murmullo_vesicular . "','" . $this->ar_vibraciones_vocales . "',"
                            . "'" . $this->ar_ruidos_agregados . "','" . $this->ar_ruidos_agregados_detalle . "','" . $this->abdomen . "',"
                            . "'" . $this->abdomen_blando . "','" . $this->abdomen_depresible . "','" . $this->abdomen_indoloro . "','" . $this->higado . "',"
                            . "'" . $this->higado_reborde_costal . "','" . $this->bazo . "','" . $this->hernias . "','" . $this->hernia_epigastrica . "',"
                            . "'" . $this->hernia_umbilical . "','" . $this->hernia_inglinal . "','" . $this->cicatrices . "','" . $this->cicatrices_lugar . "',"
                            . "'" . $this->piel . "','" . $this->columna . "','" . $this->col_eje_cervical . "','" . $this->col_eje_dorsal . "',"
                            . "'" . $this->col_eje_lumbar . "','" . $this->col_desejes . "','" . $this->og_varicocele . "','" . $this->og_hidrocele . "',"
                            . "'" . $this->og_testiculos . "','" . $this->reflejos_tendinosos . "','" . $this->varices_grado . "',"
                            . "'" . $this->observaciones . "','" . $this->hernia_descripcion . "','" . $this->varices_lugar . "',"
                            . "'" . $this->varices_descripcion . "','" . $this->aparato_locomotor_msd . "','" . $this->aparato_locomotor_msd_lugar . "'"
                            . ",'" . $this->aparato_locomotor_msi . "','" . $this->aparato_locomotor_msi_lugar . "','" . $this->aparato_locomotor_mid . "','" . $this->aparato_locomotor_mid_lugar . "'"
                            . ",'" . $this->aparato_locomotor_mii . "','" . $this->aparato_locomotor_mii_lugar . "','" . $this->aparato_locomotor . "',"
                    . "'".$this->programada."','".$this->id_encuesta."','".$this->tension_arterial_maxima."','".$this->saturacion."')")) {
                $this->id = $bd->ultimo_id();
                return 1;
            } else
                return 0;
        }else {
            if ($bd->select("UPDATE hcl_examen_fisico SET saturacion='".$this->saturacion."',tension_arterial_maxima='".$this->tension_arterial_maxima."',aparato_locomotor='" . $this->aparato_locomotor . "',idpaciente='" . $this->idpaciente . "',fecha='" . $this->fecha . "',idprofesional='" . $this->idprofesional . "',peso='" . $this->peso . "',talla='" . $this->talla . "',imc='" . $this->imc . "',temp='" . $this->temp . "',fc='" . $this->fc . "',dentadura='" . $this->dentadura . "',pupilas_isocoricas='" . $this->pupilas_isocoricas . "',pulso='" . $this->pulso . "',pterigion='" . $this->pterigion . "',pterigion_ubicacion='" . $this->pterigion_ubicacion . "',tension_arterial='" . $this->tension_arterial . "',vision_od='" . $this->vision_od . "',vision_oi='" . $this->vision_oi . "',vision_cromatica='" . $this->vision_cromatica . "',correccion_od='" . $this->correccion_od . "',correccion_oi='" . $this->correccion_oi . "',cuello_adenomegalias='" . $this->cuello_adenomegalias . "',amigdalas='" . $this->amigdalas . "',oidos_timpano_derecho='" . $this->oidos_timpano_derecho . "',oidos_timpano_izquierdo='" . $this->oidos_timpano_izquierdo . "',nariz_permeable='" . $this->nariz_permeable . "',nariz_hipertrofias='" . $this->nariz_hipertrofias . "',acv_auscultacion_r1='" . $this->acv_auscultacion_r1 . "',acv_auscultacion_r2='" . $this->acv_auscultacion_r2 . "',acv_soplos='" . $this->acv_soplos . "',ar_murmullo_vesicular='" . $this->ar_murmullo_vesicular . "',ar_vibraciones_vocales='" . $this->ar_vibraciones_vocales . "',ar_ruidos_agregados='" . $this->ar_ruidos_agregados . "',ar_ruidos_agregados_detalle='" . $this->ar_ruidos_agregados_detalle . "',abdomen='" . $this->abdomen . "',abdomen_blando='" . $this->abdomen_blando . "',abdomen_depresible='" . $this->abdomen_depresible . "',abdomen_indoloro='" . $this->abdomen_indoloro . "',higado='" . $this->higado . "',higado_reborde_costal='" . $this->higado_reborde_costal . "',bazo='" . $this->bazo . "',hernias='" . $this->hernias . "',hernia_epigastrica='" . $this->hernia_epigastrica . "',hernia_umbilical='" . $this->hernia_umbilical . "',hernia_inglinal='" . $this->hernia_inglinal . "',cicatrices='" . $this->cicatrices . "',cicatrices_lugar='" . $this->cicatrices_lugar . "',piel='" . $this->piel . "',columna='" . $this->columna . "',col_eje_cervical='" . $this->col_eje_cervical . "',col_eje_dorsal='" . $this->col_eje_dorsal . "',col_eje_lumbar='" . $this->col_eje_lumbar . "',col_desejes='" . $this->col_desejes . "',og_varicocele='" . $this->og_varicocele . "',og_hidrocele='" . $this->og_hidrocele . "',og_testiculos='" . $this->og_testiculos . "',reflejos_tendinosos='" . $this->reflejos_tendinosos . "',varices_grado='" . $this->varices_grado . "',observaciones='" . $this->observaciones . "',hernia_descripcion='" . $this->hernia_descripcion . "',varices_descripcion='" . $this->varices_descripcion . "',varices_lugar='" . $this->varices_lugar . "',aparato_locomotor_msd='" . $this->aparato_locomotor_msd . "',aparato_locomotor_msd_lugar='" . $this->aparato_locomotor_msd_lugar . "',aparato_locomotor_msi='" . $this->aparato_locomotor_msi . "',aparato_locomotor_msi_lugar='" . $this->aparato_locomotor_msi_lugar . "',aparato_locomotor_mii='" . $this->aparato_locomotor_mii . "',aparato_locomotor_mii_lugar='" . $this->aparato_locomotor_mii_lugar . "',aparato_locomotor_mid='" . $this->aparato_locomotor_mid . "',aparato_locomotor_mid_lugar='" . $this->aparato_locomotor_mid_lugar . "',programada='".$this->programada."',id_encuesta='".$this->id_encuesta."' WHERE id='" . $this->id . "'")) {

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

    function fecha() {
        return $this->fecha;
    }

    function idprofesional() {
        return $this->idprofesional;
    }

    function peso() {
        return $this->peso;
    }

    function talla() {
        return $this->talla;
    }

    function imc() {
        return $this->imc;
    }

    function temp() {
        return $this->temp;
    }

    function fc() {
        return $this->fc;
    }

    function dentadura() {
        return $this->dentadura;
    }

    function pupilas_isocoricas() {
        return $this->pupilas_isocoricas;
    }

    function pulso() {
        return $this->pulso;
    }

    function pterigion() {
        return $this->pterigion;
    }

    function pterigion_ubicacion() {
        return $this->pterigion_ubicacion;
    }

    function tension_arterial() {
        return $this->tension_arterial;
    }
    function tension_arterial_maxima() {
        return $this->tension_arterial_maxima;
    }

    function vision_od() {
        return $this->vision_od;
    }

    function vision_oi() {
        return $this->vision_oi;
    }

    function vision_cromatica() {
        return $this->vision_cromatica;
    }

    function correccion_od() {
        return $this->correccion_od;
    }

    function correccion_oi() {
        return $this->correccion_oi;
    }

    function cuello_adenomegalias() {
        return $this->cuello_adenomegalias;
    }

    function amigdalas() {
        return $this->amigdalas;
    }

    function oidos_timpano_derecho() {
        return $this->oidos_timpano_derecho;
    }

    function oidos_timpano_izquierdo() {
        return $this->oidos_timpano_izquierdo;
    }

    function nariz_permeable() {
        return $this->nariz_permeable;
    }

    function nariz_hipertrofias() {
        return $this->nariz_hipertrofias;
    }

    function acv_auscultacion_r1() {
        return $this->acv_auscultacion_r1;
    }

    function acv_auscultacion_r2() {
        return $this->acv_auscultacion_r2;
    }

    function acv_soplos() {
        return $this->acv_soplos;
    }

    function ar_murmullo_vesicular() {
        return $this->ar_murmullo_vesicular;
    }

    function ar_vibraciones_vocales() {
        return $this->ar_vibraciones_vocales;
    }

    function ar_ruidos_agregados() {
        return $this->ar_ruidos_agregados;
    }

    function ar_ruidos_agregados_detalle() {
        return $this->ar_ruidos_agregados_detalle;
    }

    function abdomen() {
        return $this->abdomen;
    }

    function abdomen_blando() {
        return $this->abdomen_blando;
    }

    function abdomen_depresible() {
        return $this->abdomen_depresible;
    }

    function abdomen_indoloro() {
        return $this->abdomen_indoloro;
    }

    function higado() {
        return $this->higado;
    }

    function higado_reborde_costal() {
        return $this->higado_reborde_costal;
    }

    function bazo() {
        return $this->bazo;
    }

    function hernias() {
        return $this->hernias;
    }

    function hernia_epigastrica() {
        return $this->hernia_epigastrica;
    }

    function hernia_umbilical() {
        return $this->hernia_umbilical;
    }

    function hernia_inglinal() {
        return $this->hernia_inglinal;
    }

    function cicatrices() {
        return $this->cicatrices;
    }

    function cicatrices_lugar() {
        return $this->cicatrices_lugar;
    }

    function piel() {
        return $this->piel;
    }

    function columna() {
        return $this->columna;
    }

    function col_eje_cervical() {
        return $this->col_eje_cervical;
    }

    function col_eje_dorsal() {
        return $this->col_eje_dorsal;
    }

    function col_eje_lumbar() {
        return $this->col_eje_lumbar;
    }

    function col_desejes() {
        return $this->col_desejes;
    }

    function og_varicocele() {
        return $this->og_varicocele;
    }

    function og_hidrocele() {
        return $this->og_hidrocele;
    }

    function og_testiculos() {
        return $this->og_testiculos;
    }

    function reflejos_tendinosos() {
        return $this->reflejos_tendinosos;
    }

    function varices_grado() {
        return $this->varices_grado;
    }

    function observaciones() {
        return $this->observaciones;
    }

    function hernia_descripcion() {
        return $this->hernia_descripcion;
    }

    function varices_descripcion() {
        return $this->varices_descripcion;
    }

    function varices_lugar() {
        return $this->varices_lugar;
    }

    function aparato_locomotor_msd() {
        return $this->aparato_locomotor_msd;
    }

    function aparato_locomotor_msd_lugar() {
        return $this->aparato_locomotor_msd_lugar;
    }

    function aparato_locomotor_msi() {
        return $this->aparato_locomotor_msi;
    }

    function aparato_locomotor_msi_lugar() {
        return $this->aparato_locomotor_msi_lugar;
    }

    function aparato_locomotor_mid() {
        return $this->aparato_locomotor_mid;
    }

    function aparato_locomotor_mid_lugar() {
        return $this->aparato_locomotor_mid_lugar;
    }

    function aparato_locomotor_mii() {
        return $this->aparato_locomotor_mii;
    }

    function aparato_locomotor_mii_lugar() {
        return $this->aparato_locomotor_mii_lugar;
    }

    function aparato_locomotor() {
        return $this->aparato_locomotor;
    }
           function programada() {
        return $this->programada;
    }
    function id_encuesta() {
        return $this->id_encuesta;
    }
    function saturacion() {
        return $this->saturacion;
    }
    function arreglo_foraneo_idpaciente() {
        return $this->arreglo_foraneo_idpaciente;
    }

    function arreglo_foraneo_idprofesional() {
        return $this->arreglo_foraneo_idprofesional;
    }

    function id_asigna($campo) {
        $this->id = $campo;
    }

    function idpaciente_asigna($campo) {
        $this->idpaciente = $campo;
    }

    function fecha_asigna($campo) {
        $this->fecha = $campo;
    }

    function idprofesional_asigna($campo) {
        $this->idprofesional = $campo;
    }

    function peso_asigna($campo) {
        $this->peso = $campo;
    }

    function talla_asigna($campo) {
        $this->talla = $campo;
    }

    function imc_asigna($campo) {
        $this->imc = $campo;
    }

    function temp_asigna($campo) {
        $this->temp = $campo;
    }

    function fc_asigna($campo) {
        $this->fc = $campo;
    }

    function dentadura_asigna($campo) {
        $this->dentadura = $campo;
    }

    function pupilas_isocoricas_asigna($campo) {
        $this->pupilas_isocoricas = $campo;
    }

    function pulso_asigna($campo) {
        $this->pulso = $campo;
    }

    function pterigion_asigna($campo) {
        $this->pterigion = $campo;
    }

    function pterigion_ubicacion_asigna($campo) {
        $this->pterigion_ubicacion = $campo;
    }

    function tension_arterial_asigna($campo) {
        $this->tension_arterial = $campo;
    }
    function tension_arterial_maxima_asigna($campo) {
        $this->tension_arterial_maxima=$campo;
    }
    function vision_od_asigna($campo) {
        $this->vision_od = $campo;
    }

    function vision_oi_asigna($campo) {
        $this->vision_oi = $campo;
    }

    function vision_cromatica_asigna($campo) {
        $this->vision_cromatica = $campo;
    }

    function correccion_od_asigna($campo) {
        $this->correccion_od = $campo;
    }

    function correccion_oi_asigna($campo) {
        $this->correccion_oi = $campo;
    }

    function cuello_adenomegalias_asigna($campo) {
        $this->cuello_adenomegalias = $campo;
    }

    function amigdalas_asigna($campo) {
        $this->amigdalas = $campo;
    }

    function oidos_timpano_derecho_asigna($campo) {
        $this->oidos_timpano_derecho = $campo;
    }

    function oidos_timpano_izquierdo_asigna($campo) {
        $this->oidos_timpano_izquierdo = $campo;
    }

    function nariz_permeable_asigna($campo) {
        $this->nariz_permeable = $campo;
    }

    function nariz_hipertrofias_asigna($campo) {
        $this->nariz_hipertrofias = $campo;
    }

    function acv_auscultacion_r1_asigna($campo) {
        $this->acv_auscultacion_r1 = $campo;
    }

    function acv_auscultacion_r2_asigna($campo) {
        $this->acv_auscultacion_r2 = $campo;
    }

    function acv_soplos_asigna($campo) {
        $this->acv_soplos = $campo;
    }

    function ar_murmullo_vesicular_asigna($campo) {
        $this->ar_murmullo_vesicular = $campo;
    }

    function ar_vibraciones_vocales_asigna($campo) {
        $this->ar_vibraciones_vocales = $campo;
    }

    function ar_ruidos_agregados_asigna($campo) {
        $this->ar_ruidos_agregados = $campo;
    }

    function ar_ruidos_agregados_detalle_asigna($campo) {
        $this->ar_ruidos_agregados_detalle = $campo;
    }

    function abdomen_asigna($campo) {
        $this->abdomen = $campo;
    }

    function abdomen_blando_asigna($campo) {
        $this->abdomen_blando = $campo;
    }

    function abdomen_depresible_asigna($campo) {
        $this->abdomen_depresible = $campo;
    }

    function abdomen_indoloro_asigna($campo) {
        $this->abdomen_indoloro = $campo;
    }

    function higado_asigna($campo) {
        $this->higado = $campo;
    }

    function higado_reborde_costal_asigna($campo) {
        $this->higado_reborde_costal = $campo;
    }

    function bazo_asigna($campo) {
        $this->bazo = $campo;
    }

    function hernias_asigna($campo) {
        $this->hernias = $campo;
    }

    function hernia_epigastrica_asigna($campo) {
        $this->hernia_epigastrica = $campo;
    }

    function hernia_umbilical_asigna($campo) {
        $this->hernia_umbilical = $campo;
    }

    function hernia_inglinal_asigna($campo) {
        $this->hernia_inglinal = $campo;
    }

    function cicatrices_asigna($campo) {
        $this->cicatrices = $campo;
    }

    function cicatrices_lugar_asigna($campo) {
        $this->cicatrices_lugar = $campo;
    }

    function piel_asigna($campo) {
        $this->piel = $campo;
    }

    function columna_asigna($campo) {
        $this->columna = $campo;
    }

    function col_eje_cervical_asigna($campo) {
        $this->col_eje_cervical = $campo;
    }

    function col_eje_dorsal_asigna($campo) {
        $this->col_eje_dorsal = $campo;
    }

    function col_eje_lumbar_asigna($campo) {
        $this->col_eje_lumbar = $campo;
    }

    function col_desejes_asigna($campo) {
        $this->col_desejes = $campo;
    }

    function og_varicocele_asigna($campo) {
        $this->og_varicocele = $campo;
    }

    function og_hidrocele_asigna($campo) {
        $this->og_hidrocele = $campo;
    }

    function og_testiculos_asigna($campo) {
        $this->og_testiculos = $campo;
    }

    function reflejos_tendinosos_asigna($campo) {
        $this->reflejos_tendinosos = $campo;
    }

    function varices_grado_asigna($campo) {
        $this->varices_grado = $campo;
    }

    function observaciones_asigna($campo) {
        $this->observaciones = $campo;
    }

    function hernia_descripcion_asigna($campo) {
        $this->hernia_descripcion = $campo;
    }

    function varices_descripcion_asigna($campo) {
        $this->varices_descripcion = $campo;
    }

    function varices_lugar_asigna($campo) {
        $this->varices_lugar = $campo;
    }

    function aparato_locomotor_msd_asigna($campo) {
        $this->aparato_locomotor_msd = $campo;
    }

    function aparato_locomotor_msd_lugar_asigna($campo) {
        $this->aparato_locomotor_msd_lugar = $campo;
    }

    function aparato_locomotor_msi_asigna($campo) {
        $this->aparato_locomotor_msi = $campo;
    }

    function aparato_locomotor_msi_lugar_asigna($campo) {
        $this->aparato_locomotor_msi_lugar = $campo;
    }

    function aparato_locomotor_mid_asigna($campo) {
        $this->aparato_locomotor_mid = $campo;
    }

    function aparato_locomotor_mid_lugar_asigna($campo) {
        $this->aparato_locomotor_mid_lugar = $campo;
    }

    function aparato_locomotor_mii_asigna($campo) {
        $this->aparato_locomotor_mii = $campo;
    }

    function aparato_locomotor_mii_lugar_asigna($campo) {
        $this->aparato_locomotor_mii_lugar = $campo;
    }

    function aparato_locomotor_asigna($campo) {
        $this->aparato_locomotor = $campo;
    }
           function programada_asigna($campo)
            {
                 $this->programada=$campo;
            }
            function id_encuesta_asigna($campo)
            {
                 $this->id_encuesta=$campo;
            }
            function saturacion_asigna($campo) {
         $this->saturacion=$campo;
    }
          
    function foranea_idpaciente($idpaciente) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_examen_fisico WHERE idpaciente=$idpaciente ORDER BY fecha DESC");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idpaciente = $pro;
    }

    function foranea_idprofesional($idprofesional) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM hcl_examen_fisico WHERE idprofesional=$idprofesional");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idprofesional = $pro;
    }

    function foranea_idpaciente_idosocial($idpaciente, $idosocial) {
        $bd = new baseDatos();
        $bd->Conectarse();
        $base = new baseDatos();
        $base->Conectarse();
        //primero buscamos los pedidos de examen fisico para luego buscar si en la misma fecha se hizo el estudio
        $bd->select("SELECT fecha FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                WHERE idpaciente=$idpaciente AND idosocial=$idosocial AND (idnomenclador=1923 OR idnomenclador=2139 OR idnomenclador=2194 )");
        $pro = new clase_listar();
        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $arreglo = $bd->registro();
            $base->select("SELECT * FROM hcl_examen_fisico WHERE idpaciente=$idpaciente AND fecha='" . $arreglo['fecha'] . "'");
            $fila = $base->registro();

            if ($fila['id'] != 0 && $fila['id'] != '')
                $pro->introducirElemento($fila);
        }
        $this->arreglo_foraneo_idpaciente = $pro;
    }

}

?>
