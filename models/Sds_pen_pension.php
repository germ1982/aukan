<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_pen_pension".
 *
 * @property int $idpension
 * @property int $idpersona
 * @property int $programa
 * @property int|null $legajo
 * @property int|null $legajo_rh
 * @property int $tramite_nacion
 * @property string $fecha_carga
 * @property string|null $fecha_otorgado
 * @property int|null $tipo_otorgado
 * @property int|null $anio_otorgado
 * @property int|null $numero_otorgado
 * @property string|null $fecha_baja
 * @property int|null $tipo_baja
 * @property int|null $anio_baja
 * @property int|null $causa_baja
 * @property int|null $numero_baja
 * @property int|null $transferida
 * @property int|null $persona_transferida
 * @property string|null $observaciones_baja
 * @property int|null $lugar_pago
 * @property int $idlocalidad
 * @property string|null $notas
 * @property string $calle
 * @property string|null $numero
 * @property string|null $casa
 * @property string|null $manzana
 * @property string|null $lote
 * @property int|null $idbarrio
 * @property string|null $departamento
 * @property int|null $estado
 * @property string|null $expediente
 * @property string|null $resolucion
 *
 * @property SdsComBarrio $idbarrio0
 * @property SdsComConfiguracion $causaBaja
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $estado0
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsComConfiguracion $lugarPago
 * @property SdsComPersona $personaTransferida
 * @property SdsComConfiguracion $programa0
 * @property SdsComConfiguracion $tipoBaja
 * @property SdsComConfiguracion $tipoOtorgado
 */
class Sds_pen_pension extends \yii\db\ActiveRecord
{
    public $documento_tipo;
    public $documento;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $genero;
    public $nacionalidad;
    public $documento_persona_transferida;
    public $descripcion_persona_transferida;
    public $estado_descripcion;
    public $programa_descripcion;
    public $fdesde;
    public $fhasta;
    public $causa_baja_descripcion;

    public static function tableName()
    {
        return 'sds_pen_pension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'programa', 'fecha_carga', 'idlocalidad', 'estado','fecha_nacimiento','documento_tipo','documento','nombre','apellido','genero','nacionalidad'], 'required'],//estos required solo avisan durante el llenado del formulario, igualmente hay que marcarlos en la base de datos sino guarda como si nada
            [['idpersona', 'programa', 'legajo', 'legajo_rh', 'tramite_nacion', 'tipo_otorgado', 'anio_otorgado', 'numero_otorgado', 'tipo_baja', 'anio_baja', 'causa_baja', 'numero_baja', 'transferida', 'persona_transferida', 'lugar_pago', 'idlocalidad', 'idbarrio', 'estado','documento_tipo','documento','genero','nacionalidad','documento_persona_transferida'], 'integer'],
            [['fecha_carga', 'fecha_otorgado', 'fecha_baja','documento_tipo','fecha_nacimiento', 
              'estado_descripcion', 'programa_descripcion', 'fdesde', 'fhasta'], 'safe'],
            [['observaciones_baja', 'notas','nombre','apellido','causa_baja_descripcion','descripcion_persona_transferida', 'estado_descripcion', 'programa_descripcion'], 'string'],
            [['calle'], 'string', 'max' => 200],
            [['numero', 'casa', 'manzana', 'lote', 'departamento', 'expediente', 'resolucion'], 'string', 'max' => 45],
            [['idbarrio'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_barrio::className(), 'targetAttribute' => ['idbarrio' => 'idbarrio']],
            [['causa_baja'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['causa_baja' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['estado' => 'idconfiguracion']],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['lugar_pago'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['lugar_pago' => 'idconfiguracion']],
            [['persona_transferida'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['persona_transferida' => 'idpersona']],
            [['programa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['programa' => 'idconfiguracion']],
            [['tipo_baja'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo_baja' => 'idconfiguracion']],
            [['tipo_otorgado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo_otorgado' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpension' => 'Idpension',
            'idpersona' => 'Idpersona',
            'programa' => 'Programa',
            'legajo' => 'Legajo',
            'legajo_rh' => 'Legajo Rh',
            'tramite_nacion' => 'Tramite Nacion',
            'fecha_carga' => 'Fecha Carga',
            'fecha_otorgado' => 'Fecha Otorgado',
            'tipo_otorgado' => 'Tipo Otorgado',
            'anio_otorgado' => 'Anio Otorgado',
            'numero_otorgado' => 'Numero Otorgado',
            'fecha_baja' => 'Fecha Baja',
            'tipo_baja' => 'Tipo Baja',
            'anio_baja' => 'Anio Baja',
            'causa_baja' => 'Causa Baja',
            'numero_baja' => 'Numero Baja',
            'transferida' => 'Transferida',
            'persona_transferida' => 'Persona Transferida',
            'observaciones_baja' => 'Observaciones Baja',
            'lugar_pago' => 'Lugar Pago',
            'idlocalidad' => 'Idlocalidad',
            'notas' => 'Notas',
            'calle' => 'Calle',
            'numero' => 'Numero',
            'casa' => 'Casa',
            'manzana' => 'Manzana',
            'lote' => 'Lote',
            'idbarrio' => 'Idbarrio',
            'departamento' => 'Departamento',
            'estado' => 'Estado',
            'expediente' => 'Expediente',
            'resolucion' => 'Resolucion',
        ];
    }

    /**
     * Gets query for [[Idbarrio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdbarrio0()
    {
        return $this->hasOne(Sds_com_barrio::className(), ['idbarrio' => 'idbarrio']);
    }

    /**
     * Gets query for [[CausaBaja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCausaBaja()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'causa_baja']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::className(), ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Estado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'estado']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::className(), ['idlocalidad' => 'idlocalidad']);
    }

    /**
     * Gets query for [[LugarPago]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLugarPago()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'lugar_pago']);
    }

    /**
     * Gets query for [[PersonaTransferida]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaTransferida()
    {
        return $this->hasOne(Sds_com_persona::className(), ['idpersona' => 'persona_transferida']);
    }

    /**
     * Gets query for [[Programa0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'programa']);
    }

    /**
     * Gets query for [[TipoBaja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoBaja()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipo_baja']);
    }

    /**
     * Gets query for [[TipoOtorgado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoOtorgado()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipo_otorgado']);
    }
}
