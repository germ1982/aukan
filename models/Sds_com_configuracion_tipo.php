<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_configuracion_tipo".
 *
 * @property int $idconfiguraciontipo
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsComConfiguracion[] $sdsComConfiguracions
 */
class Sds_com_configuracion_tipo extends \yii\db\ActiveRecord
{
    const TIPO_SIN_ASIGNAR = 1;
    const TIPO_COBERTURA_SALUD = 2;
    const TIPO_DISCAPACIDAD = 3;
    const TIPO_TRABAJO = 4;
    const TIPO_TIPO_TRABAJO = 5;
    const TIPO_VINCULO_CONTRACTUAL = 6;
    const TIPO_TIPO_EST_EDUCATIVO = 7;
    const TIPO_ULTIMO_ANIO_APROBADO = 8;
    const TIPO_ESCOLARIDAD = 9;
    const TIPO_SIT_CONYUGAL = 10;
    const TIPO_PARENTEZCO = 11;
    const TIPO_NACIONALIDAD = 12;
    const TIPO_GENERO = 13;
    const TIPO_TIPO_DOC = 14;
    const TIPO_ENFERMEDAD = 15;
    const TIPO_ALIMENTACION = 16;
    const TIPO_VIVIENDA_USO = 17;
    const TIPO_VIVIENDA_UBIC = 18;
    const TIPO_AREA = 19;
    const TIPO_REALIZADO_POR = 20;
    const TIPO_ENCUESTADOR = 21;
    const TIPO_VIVIENDA_PROP = 22;
    const TIPO_VIVIENDA_HABIT = 23;
    const TIPO_VIVIENDA_TIPO = 24;
    const TIPO_VIVIENDA_PISO = 25;
    const TIPO_VIVIENDA_AGUA_OBT = 26;
    const TIPO_VIVIENDA_AGUA = 27;
    const TIPO_VIVIENDA_BANO = 28;
    const TIPO_VIVIENDA_BANO_DES = 29;
    const TIPO_VIVIENDA_ILUMINACION = 30;
    const TIPO_VIVIENDA_MEDIDOR = 31;
    const TIPO_VIVIENDA_COMB_CALEF = 32;
    const TIPO_VIVIENDA_COMB_COCINA = 33;
    const TIPO_VIVIENDA_TECHO = 34;
    const TIPO_VIVIENDA_PAREDES = 35;
    const TIPO_INTERVENCION_TIPO = 36;
    const TIPO_INTERVENCION_DERIVACION = 37;
    const TIPO_REFERENTE_TARJETA = 38;
    const TIPO_EMPRESA_TARJETA = 39;
    const TIPO_SITUACION_TIPO = 40;
    const TIPO_COR_INTERVENCION_TIPO = 41;
    const TIPO_CAP_TEMATICA = 42;
    const TIPO_COR_INTERVENCION_LEY = 43;
    const TIPO_RESPONSABLE_ENTREGA = 44;
    const TIPO_ASIGNACION_IP = 45;
    const TIPO_TIPO_INFORME = 46;
    const RUM_NIVEL_CONOC_GRAL = 47;
    const RUM_NIVEL_OCUPACION = 48;
    const RUM_NIVEL_EXPERIENCIA = 49;
    const RUM_CUALIFICACION = 50;
    const RUM_TIPO_TRABAJO = 51;
    const RUM_DURACION_TRABAJO = 52;
    const RUM_CATEGORIA_OFERTA_LAB = 53;
    const RUM_DETALLE_CONOC_GEN = 54;
    const TIPO_CONTACTO_DOCUMENTO_TIPO = 55;
    const TIPO_0800_RECREACION = 56;
    const TIPO_0800_AREA = 57;
    const CAP_DOCENTE_PROFCORTA = 62;
    const TIPO_SISTEMA_OPERATIVO = 63;
    const TIPO_PROCESADOR = 64;
    const TIPO_MEMORIA = 65;
    const TIPO_DISCO = 66;
    const TIPO_CONECTIVIDAD = 67;
    const TIPO_NOVEDAD = 68;
    const TIPO_PROVEEDOR = 69;
    const TIPO_ORGANISMO_LINEA = 70;
    const TIPO_ORG_PERFIL = 71;
    const TIPO_ORG_ACTIVIDAD = 72;
    const TIPO_ENTREGA_PROVEEDOR = 73;
    const TIPO_PENSION_ESTADO = 74;
    const TIPO_PENSION_LUGAR_DE_PAGO = 75;
    const TIPO_PENSION_TIPO_OTORGADO = 76;
    const TIPO_PENSION_TIPO_BAJA = 77;
    const TIPO_PENSION_CAUSA_BAJA = 78;
    const TIPO_PENSION_PROGRAMA = 79;
    const LEGALES_EMISOR_TIPO = 80;
    const LEGALES_OFICIO_TIPO = 81;
    const TIPO_ESTADO_RESPUESTA = 82;
    const NIVEL_INSTITUCION = 60;
    const TIPO_CATEGORIA_PLANTA_POLITICA = 83;
    const TIPO_CATEGORIA_CONVENIO = 84;
    const SDS_GIS_CAPA_ITEM_TEMATICA = 85;
    const TIPO_UNIDAD_MEDIDA = 86;
    const TIPO_CATEGORIA_CONTRATO = 87;
    const TIPO_REG_ENTIDAD = 88;
    const TIPO_UNIDAD_OPERATIVA = 89;
    const TIPO_ENT_MOTIVO_CIERRE = 92;
    const DOC_MEDICINA_LABORAL = 95;
    const TIPO_ESTADO_DOCUMENTO = 96;
    const CERTIFICACION_PROGRAMA = 97;
    const CERTIFICACION_DIRECCION = 98;
    const BDC_TIPO_EQUIPO = 99;
    const BDC_MARCA_EQUIPO = 100;
    const REPROAM_ZONA = 101;
    const ACOMP_RIESGO = 102;
    const BDC_MOVIMIENTO_TIPO = 103;
    const REG_PEDIDO_ESTADO = 105;
    const TIPO_RUBRO = 107;
    const TIPO_NORMA_LEGAL = 108;
    const TIPO_VIOLENCIA = 109;
    const TIPO_GENERO_AUTOPERCIBIDO = 110;
    const MOVIMIENTO_LINEA = 111;
    const TIPO_INTERVENCION_ODONTOLOGIA = 112;
    const TIPO_DISPOSITIVO_ODONTOLOGIA = 113;
    const TIPO_VISITA_ODONTOLOGIA = 114;
    const TIPO_TS_CAMPANIA = 115;
    const TIPO_TS_INSTITUCION = 116;
    //const RELACION_TS_INSTITUCION = 117;
    const GERONTOLOGIA_ABVD_LAVADO = 117;
    const GERONTOLOGIA_ABVD_VESTIDO = 118;
    const GERONTOLOGIA_ABVD_BANIO = 119;
    const GERONTOLOGIA_ABVD_MOVILIZACION = 120;
    const GERONTOLOGIA_ABVD_CONTINENCIA = 121;
    const GERONTOLOGIA_ABVD_ALIMENTACION = 122;
    const GERONTOLOGIA_AIVD_USA_TELEFONO = 123;
    const GERONTOLOGIA_AIVD_COMPRAS = 124;
    const GERONTOLOGIA_AIVD_PREPARACION_COMIDA = 125;
    const GERONTOLOGIA_AIVD_CUIDADO_CASA = 126;
    const GERONTOLOGIA_AIVD_LAVADO_ROPA = 127;
    const GERONTOLOGIA_AIVD_USO_TRANSPORTE = 128;
    const GERONTOLOGIA_AIVD_RESPONSABILIDAD_MEDICACION = 129;
    const GERONTOLOGIA_AIVD_ASUNTOS_ECONOMICOS = 130;
    const GERONTOLOGIA_SOCIAL_SIT_FAMILIAR = 131;
    const GERONTOLOGIA_SOCIAL_SIT_REL_SOCIALES = 132;
    const GERONTOLOGIA_SOCIAL_APOYO_RED_SOCIAL = 133;
    const OBRA_SOCIAL = 134;
    const VACUNA_COVID19 = 135;
    const GERONTOLOGIA_VIVIENDA = 136;
    const CERTIFICACION_ESTADOS = 137;
    const CERTIFICACION_CONDICION = 138;
    const CERTIFICACION_TIPO_JUBILACION = 139;
    const VIO_VIOLENCIA_TIPOS_FISICA = 140;
    const VIO_VIOLENCIA_TIPOS_PSICOLOGICA = 141;
    const VIO_VIOLENCIA_TIPOS_ECONOMICA = 142;
    const VIO_VIOLENCIA_TIPOS_SEXUAL = 143;
    const VIO_VIOLENCIA_TIPOS_SIMBOLICA = 144;
    const VIO_AGRESOR_VINCULO_SEGURIDAD = 145;
    const VIO_AGRESOR_CONSUMO_PROB = 146;
    const DIAGNOSTICO_TIPO_MAPA = 147;
    const DIAGNOSTICO_INDICADOR = 148;
    const VIO_MOVIMIENTO_TIPO_MOVIMIENTO = 149;
    const VIO_VIOLENCIA_OCURRENCIA = 150;
    const VIO_VIOLENCIA_FRECUENCIA = 151;
    const VIO_VIOLENCIA_TIPOS_AMBIENTAL = 152;
    const R_ORGANISMO = 153;
    const R_VARIABLE = 154;
    const R_ORIGEN = 155;
    const R_TIPO_MAPA = 156;
    const R_TIPO_PLANTILLA = 157;
    const VEH_MARCA = 158;
    const VEH_TIPO = 159;
    const VEH_ESTADO = 160;
    const VEH_HABILITACION_TIPO = 161;
    const RRHH_EXC_REMANENTE = 162;
    const NIVEL_ESCOLARIDAD = 163;
    const CERTIFICACION_DIRECCION_RECEPTORA = 172;
    const TIPO_AYUDA = 184;
    const EXPECTATIVA_CORTO_PLAZO = 185;
    const MOTIVO_ABANDONO = 186;
    const SITUACION_SALUD = 187;
    const CONSUMO_PROBLEMATICO = 188;
    const CAPACIDAD_LIMITADA = 189;
    const R_SITUACION_LABORAL = 166;
    const APORTES_ECONOMICOS = 190;
    const VIO_VIOLENCIA_MODALIDAD = 191;
    const CERTIFICACION_TIPO_EXTERNA = 192;
    const LEGALES_AREA_TIPO = 193;
    const CERTIFICACION_TIPO_ADJUNTO = 194;
    const CERTIFICACION_TIPO_SUBSIDIO = 195;
    const FRANCO_TIPO = 196;
    const INGRESO_EXTERNO_MOTIVO = 197;
    const CERTIFICACION_PROGRAMA_REQUISITO = 198;
    const ORGANIZACION_SOCIAL = 199;
    const RUM_ACTIVIDAD = 200;
    const CONDICIION_HACINAMIENTO = 201;
    const PUEBLOS_ORIGINARIOS = 202;
    const SUSTANCIA = 203;
    const RISNEU_TIPO_TIPO_TRABAJO = 204; // Eran las nuevas opciones del RISNeu. Se hace un rollback al anterior (ID: 5)
    const VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO = 205;
    const TIPO_ORG_SERVICIO = 209;
    const REPROAM_SITUACION_HABITACIONAL = 231;
    const BDC_VISITA = 232;
    const CERTIFICACION_TIPO_RESPONSABLE = 240;
    const INV_ENTREGA_TEMPORADA = 242;
    const CONCURSO_SOLICITUD_CATEGORIA = 250;
    const CONCURSO_SOLICITUD_FUNCION = 251;
    const CONCURSO_SOLICITUD_ESTADO = 252;
    const RENDICION_TIPOS_RENDICION = 258;
    const CONCURSO_TIPO = 259;
    const CONCURSO_ETAPA = 260;
    const TIPO_DEVOLUCION = 261;
    const CERTIFICACION_FUNCION_USUARIO = 262;
    const CONCURSO_IMPUGNACION_MOTIVO = 263;
    const COR_INTERVENCION_TIEMPO_RESIDENCIA_NQN = 266;
    const COR_INTERVENCION_DENUNCIA = 267;
    const COR_INTERVENCION_ARTICULACION = 268;
    const COR_INTERVENCION_CONSUMO = 269;
    const COR_INTERVENCION_PROBLEMA = 270;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_configuracion_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idconfiguraciontipo' => 'ID',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[SdsComConfiguracions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsComConfiguracions()
    {
        return $this->hasMany(Sds_com_configuracion::className(), ['idconfiguraciontipo' => 'idconfiguraciontipo']);
    }
}
