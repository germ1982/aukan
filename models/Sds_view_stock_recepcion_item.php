<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_recepcion_item".
 *
 * @property int|null $item_recepcion
 * @property int $idarticulo
 * @property float|null $cantidad
 */
class Sds_view_stock_recepcion_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_recepcion_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_recepcion', 'idarticulo'], 'integer'],
            [['cantidad'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_recepcion' => 'Item Recepcion',
            'idarticulo' => 'Idarticulo',
            'cantidad' => 'Cantidad',
        ];
    }
}
