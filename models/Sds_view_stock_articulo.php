<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_articulo".
 *
 * @property int $idarticulo
 * @property int $organismo
 * @property float|null $stock
 */
class Sds_view_stock_articulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo', 'organismo'], 'integer'],
            [['stock'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Idarticulo',
            'organismo' => 'Organismo',
            'stock' => 'Stock',
        ];
    }
}
