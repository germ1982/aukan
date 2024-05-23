<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_devolucion".
 *
 * @property int $iddevolucion
 * @property int $idorganismo
 * @property int $idarticulo
 * @property string $fecha_hora_entrega
 * @property int $destinatario
 * @property int $responsable_entrega
 * @property string $observaciones_entrega
 * @property int|null $responsable_devolucion
 * @property string|null $observaciones_devolucion
 * @property string|null $fecha_hora_devolucion
 * @property int|null $estado
 *
 * @property SdsStkArticulo $idarticulo0
 * @property MdsOrgContacto $destinatario0
 * @property MdsSegUsuario $responsableDevolucion
 * @property MdsSegUsuario $responsableEntrega
 * @property SdsComConfiguracion $estado0
 * @property MdsOrgOrganismo $idorganismo0
 */
class Sds_stk_devolucion extends \yii\db\ActiveRecord
{
    public $hora_entrega;
    public $hora_devolucion;
    public $fdesdee;
    public $fhastae;
    public $fdesded;
    public $fhastad;

    public static function tableName()
    {
        return 'sds_stk_devolucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idorganismo', 'idarticulo', 'fecha_hora_entrega', 'destinatario', 'responsable_entrega', 'observaciones_entrega'], 'required'],
            [['idorganismo', 'idarticulo', 'destinatario', 'responsable_entrega', 'responsable_devolucion', 'estado'], 'integer'],
            [['fecha_hora_entrega', 'fecha_hora_devolucion','hora_entrega','hora_devolucion','fdesdee','fhastae','fdesded','fhastad'], 'safe'],
            [['observaciones_entrega', 'observaciones_devolucion'], 'string'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['destinatario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['destinatario' => 'idcontacto']],
            [['responsable_devolucion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['responsable_devolucion' => 'idusuario']],
            [['responsable_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['responsable_entrega' => 'idusuario']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['estado' => 'idconfiguracion']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddevolucion' => 'Iddevolucion',
            'idorganismo' => 'Idorganismo',
            'idarticulo' => 'Herramienta',
            'fecha_hora_entrega' => 'Fecha de Entrega',
            'hora_entrega' => 'Hora de Entrega',
            'destinatario' => 'Destinatario',
            'responsable_entrega' => 'Responsable Entrega',
            'observaciones_entrega' => 'Observaciones Entrega',
            'responsable_devolucion' => 'Responsable Devolucion',
            'observaciones_devolucion' => 'Observaciones Devolucion',
            'fecha_hora_devolucion' => 'Fecha Devolucion',
            'hora_devolucion' => 'Hora Devolucion',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Idarticulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdarticulo0()
    {
        return $this->hasOne(Sds_stk_articulo::className(), ['idarticulo' => 'idarticulo']);
    }

    /**
     * Gets query for [[Destinatario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDestinatario0()
    {
            [['destinatario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['destinatario' => 'idcontacto']];
            return $this->hasOne(Mds_org_contacto::className(), ['idcontacto' => 'destinatario']);
    }

    /**
     * Gets query for [[ResponsableDevolucion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableDevolucion()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'responsable_devolucion']);
    }

    /**
     * Gets query for [[ResponsableEntrega]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableEntrega()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'responsable_entrega']);
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
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }
}
