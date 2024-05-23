<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_deposito".
 *
 * @property int $idarticulo
 * @property int|null $deposito
 * @property int $organismo
 * @property string $deposito_descripcion
 * @property float|null $stock
 */
class View_stock_deposito extends \yii\db\ActiveRecord
{
    public $detalle_depositos;
    public static function tableName()
    {
        return 'view_stock_deposito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo', 'deposito', 'organismo'], 'integer'],
            [['deposito_descripcion'], 'required'],
            [['stock'], 'number'],
            [['deposito_descripcion', 'detalle_depositos'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Idarticulo',
            'deposito' => 'Deposito',
            'organismo' => 'Organismo',
            'deposito_descripcion' => 'Deposito Descripcion',
            'stock' => 'Stock',
        ];
    }
    public static function primaryKey()
    {
        return ['idarticulo'];
    }
}
