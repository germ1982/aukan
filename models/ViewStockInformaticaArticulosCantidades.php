<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_informatica_articulos_cantidades".
 *
 * @property int $idarticulo
 * @property string $rubro
 * @property string|null $descripcion
 * @property float|null $ingresado
 * @property float|null $entregado
 * @property float|null $disponible
 */
class ViewStockInformaticaArticulosCantidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_informatica_articulos_cantidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo'], 'integer'],
            [['rubro'], 'required'],
            [['descripcion'], 'string'],
            [['ingresado', 'entregado', 'disponible'], 'number'],
            [['rubro'], 'string', 'max' => 50],
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
