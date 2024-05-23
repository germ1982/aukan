<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_item".
 *
 * @property int $iditem
 * @property string $descripcion
 *
 * @property MdsSegPermiso[] $mdsSegPermisos
 */
class Mds_seg_item extends \yii\db\ActiveRecord
{
    const MODULO_REG_REGISTROS = 1;
    const MODULO_REG_TIPOS = 2;
    const MODULO_NOT_NOTAS = 3;
    const MODULO_ORG_ORGANISMOS = 4;
    const MODULO_ORG_DISPOSITIVOS = 5;
    const MODULO_SEG_SEGURIDAD = 6;
    const MODULO_GIS_MAPA = 7;
    const MODULO_GIS_CAPAS = 8;
    const MODULO_CEL_CORPORATIVOS = 9;
    const MODULO_RIS_RISNEU = 10;
    const ACCESO_PORTAL_UNIF = 11;
    const MODULO_RIS_ENCUESTADOR = 12;
    const WS_ORGANISMOS = 13;
    const WS_RISNEU = 14;
    const WS_ENTREGAS = 15;
    const WS_FAMILIA = 16;
    const WS_CAPACITACIONES = 17;
    const WS_SST = 18;
    const WS_DESEMPLEO = 19;
    const MODULO_VIO_VIOLENCIA = 20;
    const MODULO_POR_FAMILIA = 21;
    const MODULO_POR_DESEMPLEO = 22;
    const MODULO_POR_SST = 23;
    const MODULO_ENT_INDICADORES = 24;
    const MODULO_TAR_TARJETA = 25;
    const MODULO_ANS_ALIMENTAR = 26;
    const MODULO_ENT_SOLICITUD_APROBAR = 27;
    const MODULO_GUARDIAS_INTEGRADAS_LLAMADA = 28;
    const WS_ALIMENTAR = 29;
    const WS_NEGATIVA = 30;
    const MODULO_ANS_NEGATIVA = 31;
    const MODULO_VIO_EXTERNO = 32;
    const MODULO_REG_TECNICO = 33;
    const MODULO_ORG_CONTACTOS = 34;
    const MODULO_ATP_SOLICITUD = 35;
    const MODULO_COR_INTERVENCION = 36;
    const WS_JUBILACIONES_Y_PENSIONES = 37;
    const MODULO_CAP_CAPACITACION = 38;
    const MODULO_REG_AUTOSOLICITUD = 39;
    const WS_0800 = 40;
    const WS_GIS_ENTREGAS = 41;
    const MODULO_TELEFONIA_VISTAS = 42;
    const MODULO_RRHH = 43;
    const MODULO_ENT_PRIMER_INGRESO = 44;
    const MODULO_ENT_VER_TODAS = 45;
    const MODULO_ENT_CAMBIO_RESPONSABLE = 46;
    //CODIGO PARA RUMBO
    const MODULO_RUM_INSTITUCIONAL = 47;
    const MODULO_RUM_CV = 48;
    const MODULO_RUM_OFERTA_LABORAL = 49;
    const MODULO_RUM_POSTULACION = 50;
    const MODULO_RUM_EMPLEADOR = 51;
    const MODULO_RUM_NOVEDAD = 52;
    const MODULO_RUM_REG = 117;
    const MODULO_RUM_REG_TIPO = 118;
    const MODULO_RUM_REG_TECNICO = 119;
    //FIN CODIGO PARA RUMBO
    const MODULO_ORG_INFORMES = 53;
    const MODULO_ENT_ENTREGAS = 54;
    const MODULO_CAP_GLOBAL = 55;
    const MODULO_ENT_ARBOL = 56;
    const MODULO_GUARDIAS_INTEGRADAS_LLAMADA_FAMILIA = 57;
    const MODULO_GUARDIAS_INTEGRADAS_LLAMADA_ADULTOS = 58;
    const MODULO_TES_ADJUNTOS = 59;
    const MODULO_SST_INDICADORES = 60;
    const MODULO_GUARDIAS_INTEGRADAS_INTERIOR = 61;
    const MODULO_ENCUESTA_ADMIN = 62;
    const MODULO_ENCUESTA_MIS = 63;
    const MODULO_HOR_INGRESO = 64;
    const MODULO_HOR_REPORTE = 65;
    const MODULO_ORG_DOCUMENTO = 66;
    const MODULO_HOR_FRANCO = 67;
    const MODULO_HOR_LICENCIA = 68;
    const MODULO_STK_RECEPCION = 69;
    const MODULO_ORG_SITUACION = 70;
    const WS_COMERENCASA = 71;
    const WS_RH = 72;
    const WS_RENAPER = 73;
    const WS_TARIFA_SOCIAL = 74;
    const WS_RPI = 75;
    const MODULO_ATPCEN_ENCUESTA = 76;
    const WS_CUMBRE = 77;
    const MODULO_CAP_DOCENTE = 78;
    const WS_PADRON = 79;
    const MODULO_CAP_GENERAR_CERTIF = 80;
    const MODULO_PEN_PENSION = 81;
    const MODULO_SYS_LOG = 82;
    const WS_HAY_PRODUCTO = 83;
    const MODULO_CAP_INSCRIPCION = 84;
    const MODULO_CAP_INSTANCIA = 85;
    const MODULO_CAP_PERSONA = 86;
    const MODULO_ENT_ENTREGA_INTERMEDIA = 87;
    const MODULO_ENT_SALDO = 88;
    const MODULO_ENT_SOLICITUD = 89;
    const MODULO_ENT_TIPO = 90;
    const MODULO_LEGALES_CREAR_REQUERIMIENTO = 91;
    const MODULO_LEGALES_RESPONDER_REQUERIMIENTO = 92;
    const MODULO_LEGALES_VER_RESPUESTAS = 93;
    const MODULO_LEGALES_ACCIONAR_RESPUESTA = 94;
    const MODULO_LEGALES_ENVIAR_RESPUESTA = 95;
    const MODULO_LEGALES_VER_REQUERIMIENTO = 96;
    const MODULO_LEGALES_EDITAR_REQUERIMIENTO = 97;
    const MODULO_LEGALES_ELIMINAR_REQUERIMIENTO = 98;
    const MODULO_ENT_RESPONSABLE = 99;
    const MODULO_LEGALES_NOTIFICACIONES = 100;
    const MODULO_LEGALES_SEGUIMIENTO = 101;
    const MODULO_ORG_ORGANISMO_EXTERNO = 102;
    const MODULO_ENT_RESPONSABLE_UNIFICAR = 103;
    const MODULO_ENT_DEUDOR = 104;
    const MODULO_HOR_CERTIFICACION = 105;
    const MODULO_LEGALES_DERIVAR_A_USUARIOS = 106;
    const MODULO_LEGALES_RECHAZAR_REQUERIMIENTO = 107;
    const MODULO_ORG_OFICINA = 108;
    const MODULO_REG_MANTENIMIENTO = 109;
    const MODULO_REG_MANTENIMIENTO_TIPO = 110;
    const MODULO_ORG_PADRON = 111;
    const MODULO_INV_PERSONA = 112;
    const MODULO_REG_INTERNO = 113;
    const MODULO_REG_MANTENIMIENTO_TECNICO = 114;
    const MODULO_HOR_REMANENTE = 115;
    const MDS_TS_PERSONA = 116;
    const MODULO_HOR_FICHADA_LEGAJO = 120;
    const MODULO_ORG_DOC_MEDICINA = 121;
    const MODULO_ACOMP = 122;
    const MODULO_REPROAM = 123;
    const MODULO_CERTIFICACION = 124;
    const BDC_EQUIPO = 125;
    const WS_MDS_ACOMP_ASISTENCIA = 126;
    const HOR_REGISTRO_HORARIO_CARGAR = 127;
    const HOR_LIC_SGH = 128;
    const STK_GENERAL = 129;
    const MODULO_DESCARGAR_CERTI = 130;
    const STK_CARGA_DEPOSITO = 131;
    const SDS_VIO_AGRESOR = 132;
    const MDS_FS_PERSONA = 133;
    const MODULO_EDITAR_CERTI = 134;
    const MODULO_MENU = 135;
    const STK_CONSULTAR_MINIMO = 136;
    const MODULO_ODONTOLOGIA = 137;
    const MODULO_GERONTOLOGIA = 138;
    const STK_OC = 139;
    const STK_ENTREGA = 140;
    const STK_MOVIMIENTO = 141;
    const STK_ARTICULO = 142;
    const MDS_R_DIAGNOSTICO = 143;
    const STK_INVENTARIO = 144;
    const COM_PERSONA = 145;
    const MODULO_CERTIFICACIONES_FUNCIONARIO = 146;
    const MODULO_CERTIFICACIONES_SOLICITUD = 147;
    const MODULO_CERTIFICACIONES_DIRECCION_SIMPLE = 148;
    const MODULO_CERTIFICACIONES_DIRECCION_GENERAL = 149;
    const MODULO_CERTIFICACIONES_DIRECCION_PROVINCIAL = 150;
    const MODULO_CERTIFICACIONES_SUBSECRETARIA = 151;
    const MODULO_CERTIFICACIONES_ADMINISTRACION = 152;
    const MDS_R_PLANILLA = 153;
    const MDS_R_PLANTILLA = 154;
    const MDS_CERTIFICACION_ADMINISTRADOR = 155;
    const MODULO_LEGALES_ADMIN_GENERAL = 156;
    const MODULO_ODONTOLOGIA_SEGUIMIENTO = 157;
    const MODULO_GERONTOLOGIA_SEGUIMIENTO = 158;
    const MODULO_REPROAM_SEGUIMIENTO = 159;
    const INGRESO_EXTERNOS = 160;
    const ING_EXT_PENDIENTES = 161;
    const MODULO_ACOMP_ASISTENCIA_SEGUIMIENTO = 162;
    const MODULO_RUM_ACTIVIDAD = 163;
    const WS_VULNERABILIDAD = 164;
    const WS_RUMBO = 165;
    const WS_FALLECIDO = 166;
    const WS_INTERVENCIONES_LLAMADA = 167;
    const WS_INTERVENCIONES = 168;
    const WS_MDS_ACOMP_ASISTENCIA_TOTAL = 169;
    const WS_ODONTOLOGIA_SIMPLE = 170;
    const WS_ODONTOLOGIA_TOTAL = 171;
    const WS_GERONTOLOGIA_SIMPLE = 172;
    const WS_GERONTOLOGIA_TOTAL = 173;
    const WS_SUIST_EMPRESA = 174;
    const RIS_RISNEU_SEGUIMIENTO = 175;
    const _800_seguimiento = 176;
    const MODULO_CERTIFICACIONES_DASHBOARD = 177;
    const MODULO_RIS_RISNEU_IMPRIMIR = 178;
    const MODULO_ORG_NOVEDAD = 179;
    const MODULO_PAD_PADRON = 180;	
    const MODULO_DATA_SUR_WEB = 181;	
    const MODULO_LEGALES_RESPUESTAS_CON_OBSERVACION = 182;
    const MODULO_VEH_VEHICULO = 183;
    const MODULO_RELEVAMIENTO_EDILICIO = 184;
    const MODULO_LEGALES_SUPERVISOR_AREA = 185;
    const MODULO_LEGALES_CARATULA = 186;
    const MODULO_LEGALES_IMPRIMIR_REPORTE = 187;
    const MODULO_LEGALES_VINCULAR_PERSONAS = 188;
    const MODULO_ATP_ALTA = 191;
    const MODULO_CONCURSO = 195;
    const MODULO_RENDICION = 196;
    const MODULO_LEGALES_SEARCH_CARATULA = 197;
    const MODULO_LEGALES_TEMP_FILE = 198;
    const MODULO_SEG_USUARIO_STATUS = 199;
    const MODULO_MDS_RELEVAMIENTO_PLANTA = 205;
    const MODULO_HOR_FICHADA_DNI = 206;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iditem' => 'Iditem',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[MdsSegPermisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsSegPermisos()
    {
        return $this->hasMany(Mds_seg_permiso::className(), ['iditem' => 'iditem']);
    }
}
