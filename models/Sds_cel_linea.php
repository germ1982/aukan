<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_linea".
 *
 * @property int $idlinea
 * @property int $numero
 * @property string|null $imei
 * @property int $idplan
 * @property int $equipo_tipo 0:desconocido, 1: Obsoleto, 2: Media Gama, 3: Alta Gama
 * @property string|null $equipo_detalle
 * @property int $estado 0: No relevado, 1: Relevado, 2: Entregado
 * @property string|null $fecha_entrega
 * @property int|null $idcontacto responsable de la linea entregada
 * @property int|null $idorganismo organismo destinataria de la linea
 * @property int|null $idusuario usuario que entrega la linea
 * @property string|null $observaciones
 *
 * @property Mds_org_contacto $idcontacto0
 * @property Mds_org_organismo $idorganismo0
 * @property Sds_cel_plan $idplan0
 * @property Mds_seg_usuario $idusuario0
 */
class Sds_cel_linea extends \yii\db\ActiveRecord
{

    public $fdesde;
    public $fhasta;
    public $iddispositivo;
    //public $cuenta;
    public $ultimo_importe;
    //Para estado (ultimo movimiento)
    public $ultimo_movimiento;
    public $id_ultimo_movimiento;

    public $marca;
    
    const TIPO_DESCONOCIDO = 0;
    const TIPO_OBSOLETO = 1;
    const TIPO_GAMA_MEDIA = 2;
    const TIPO_GAMA_ALTA = 3;
    const TIPO_SIN_EQUIPO = 4;
    const TIPO_GAMA_BAJA = 5;

    const ESTADO_NO_RELEVADO = 0;
    const ESTADO_RELEVADO = 1;
    const ESTADO_ENTREGADO = 2;

    const ACTIVO_ACTIVO = 1;
    const ACTIVO_BAJA = 2;
    const ACTIVO_SUSPENSION_POR_ROBO = 3;
    const ACTIVO_SUSPENSION_DESCONOCIDO = 4;
    const ACTIVO_LINEA_DISPONIBLE = 5;

    const ID_MINISTERIO = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_cel_linea';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'idplan','organismo_padre'], 'required'],
            [['numero', 'idplan', 'equipo_tipo', 'estado', 'idcontacto', 'idorganismo', 'idusuario','iddispositivo','activo','ultimo_importe','organismo_padre','equipo_marca', 'idequipo', 'id_ultimo_movimiento'], 'integer'],
            [['equipo_detalle', 'observaciones','cuenta', 'ultimo_movimiento'], 'string'],
            [['fecha_entrega', 'fdesde', 'fhasta','iddispositivo','cuenta','ultimo_importe', 'marca'], 'safe'],
            [['imei'], 'string', 'max' => 45],
            [['equipo_modelo'], 'string', 'max' => 100],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['equipo_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['equipo_marca' => 'idconfiguracion']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['organismo_padre'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['organismo_padre' => 'idorganismo']],
            [['idplan'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_cel_plan::class, 'targetAttribute' => ['idplan' => 'idplan']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlinea' => 'Idlinea',
            'numero' => 'Línea',
            'imei' => 'IMEI',
            'idplan' => 'Plan',
            'equipo_tipo' => 'Tipo de Equipo',
            'equipo_detalle' => 'Detalle',
            'equipo_marca' => 'Marca',
            'equipo_modelo' => 'Modelo',
            'estado' => 'Estado',
            'fecha_entrega' => 'Fecha Entrega',
            'idcontacto' => 'Responsable',
            'idorganismo' => 'Organismo',
            'organismo_padre' => 'Organismo Cuenta',
            'idusuario' => 'Entregó',
            'iddispositivo' => 'Dispositivo',
            'observaciones' => 'Observaciones',
            'idequipo' => 'Equipo'
        ];
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Idplan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdplan0()
    {
        return $this->hasOne(Sds_cel_plan::class, ['idplan' => 'idplan']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
}
