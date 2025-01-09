<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_articulos_cantidades".
 *
 * @property int $idarticulo
 * @property string $rubro
 * @property string|null $descripcion
 * @property float|null $ingresado
 * @property float|null $entregado
 * @property float|null $disponible
 */
class ViewStockArticulosCantidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_articulos_cantidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo'], 'integer'],
            [['rubro'], 'required'],
            [['ingresado', 'entregado', 'disponible'], 'number'],
            [['rubro'], 'string', 'max' => 50],
            [['descripcion'], 'string', 'max' => 229],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Idarticulo',
            'rubro' => 'Rubro',
            'descripcion' => 'Descripcion',
            'ingresado' => 'Ingresado',
            'entregado' => 'Entregado',
            'disponible' => 'Disponible',
        ];
    }

    public static function primaryKey()
{
    return ['idarticulo']; // Define 'idarticulo' como la clave primaria
}
}
