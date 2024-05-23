<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_orden_compra_item".
 *
 * @property int $idordencompraitem
 * @property int $idordencompra
 * @property float $cantidad
 * @property float $importe_unitario
 *
 * @property SdsStkOrdenCompra $idordencompra0
 * @property SdsStkRecepcionItem[] $sdsStkRecepcionItems
 */
class Sds_stk_orden_compra_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_orden_compra_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idordencompra', 'cantidad', 'importe_unitario','idarticulo'], 'required'],
            [['idordencompra','idarticulo'], 'integer'],
            [['cantidad', 'importe_unitario'], 'number'],
            [['idordencompra'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_orden_compra::className(), 'targetAttribute' => ['idordencompra' => 'idordencompra']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idordencompraitem' => 'Idordencompraitem',
            'idordencompra' => 'Idordencompra',
            'cantidad' => 'Cantidad',
            'importe_unitario' => 'Importe Unitario',
        ];
    }

    /**
     * Gets query for [[Idordencompra0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdordencompra0()
    {
        return $this->hasOne(Sds_stk_orden_compra::className(), ['idordencompra' => 'idordencompra']);
    }

    /**
     * Gets query for [[SdsStkRecepcionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkRecepcionItems()
    {
        return $this->hasMany(Sds_stk_recepcion_item::className(), ['idordencompraitem' => 'idordencompraitem']);
    }
}
