<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_orden_compra".
 *
 * @property int $idordencompra
 * @property string $fecha_emision
 * @property string $vencimiento
 * @property string $expediente
 * @property int $numero
 * @property string $norma_legal
 * @property int $tipo_norma_legal
 * @property int $proveedor
 * @property float $importe_total
 * @property int $idorganismo
 *
 * @property SdsComConfiguracion $tipoNormaLegal
 * @property MdsOrgOrganismo $idorganismo0
 * @property SdsComConfiguracion $proveedor0
 * @property SdsStkOrdenCompraItem[] $sdsStkOrdenCompraItems
 * @property SdsStkRecepcion[] $sdsStkRecepcions
 */
class Sds_stk_orden_compra extends \yii\db\ActiveRecord
{
    const RECEPTOR = 3208;
    public $fedesde;
    public $fehasta;
    public $fvdesde;
    public $fvhasta;
    public $detalle_items;
    public static function tableName()
    {
        return 'sds_stk_orden_compra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_emision', 'vencimiento', 'expediente', 'numero', 'proveedor', 'idorganismo'], 'required'],
            [['fecha_emision', 'vencimiento', 'fedesde', 'fehasta', 'fvdesde', 'fvhasta','fecha_orden_compra'], 'safe'],
            [['tipo_norma_legal', 'proveedor', 'idorganismo','nn','generada'], 'integer'],
            [['importe_total'], 'number'],
            [['expediente', 'numero', 'norma_legal', 'detalle_items'], 'string', 'max' => 45],
            [['tipo_norma_legal'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo_norma_legal' => 'idconfiguracion']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['proveedor' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idordencompra' => 'Idordencompra',
            'fecha_emision' => 'Fecha Emision',
            'vencimiento' => 'Vencimiento',
            'expediente' => 'Expediente',
            'numero' => 'Numero',
            'norma_legal' => 'Norma Legal',
            'tipo_norma_legal' => 'Tipo Norma Legal',
            'proveedor' => 'Proveedor',
            'importe_total' => 'Importe Total',
            'idorganismo' => 'Idorganismo',
            'nn' => 'NN-',
        ];
    }

    /**
     * Gets query for [[TipoNormaLegal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoNormaLegal()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipo_norma_legal']);
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

    /**
     * Gets query for [[Proveedor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'proveedor']);
    }

    /**
     * Gets query for [[SdsStkOrdenCompraItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkOrdenCompraItems()
    {
        return $this->hasMany(Sds_stk_orden_compra_item::className(), ['idordencompra' => 'idordencompra']);
    }

    /**
     * Gets query for [[SdsStkRecepcions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkRecepcions()
    {
        return $this->hasMany(Sds_stk_recepcion::className(), ['idordencompra' => 'idordencompra']);
    }
}
