<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_articulo_safipro".
 *
 * @property int $idarticulosafipro
 * @property int $idarticulo
 * @property int $clase
 * @property int $item
 *
 * @property SdsStkArticulo $idarticulo0
 */
class Sds_stk_articulo_safipro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_articulo_safipro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo', 'clase', 'item'], 'required'],
            [['idarticulo', 'clase', 'item'], 'integer'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulosafipro' => 'Idarticulosafipro',
            'idarticulo' => 'Idarticulo',
            'clase' => 'Clase',
            'item' => 'Item',
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
}
