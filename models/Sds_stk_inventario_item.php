<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_inventario_item".
 *
 * @property int $idinventarioitem
 * @property int $idinventario
 * @property int $idarticulo
 * @property int $cantidad
 *
 * @property SdsStkArticulo $idarticulo0
 * @property SdsStkInventario $idinventario0
 */
class Sds_stk_inventario_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_inventario_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idinventario', 'idarticulo', 'cantidad'], 'required'],
            [['idinventario', 'idarticulo', 'cantidad'], 'integer'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['idinventario'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_inventario::className(), 'targetAttribute' => ['idinventario' => 'idinventario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinventarioitem' => 'Idinventarioitem',
            'idinventario' => 'Idinventario',
            'idarticulo' => 'Idarticulo',
            'cantidad' => 'Cantidad',
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
     * Gets query for [[Idinventario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdinventario0()
    {
        return $this->hasOne(Sds_stk_inventario::className(), ['idinventario' => 'idinventario']);
    }
}
