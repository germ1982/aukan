<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_inversion".
 *
 * @property int $organismo
 * @property int $idarticulo
 * @property string $articulo
 * @property int|null $anio
 * @property float|null $cantidad
 * @property float|null $importe
 */
class View_stock_inversion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_inversion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organismo', 'idarticulo', 'anio'], 'integer'],
            [['articulo'], 'required'],
            [['cantidad', 'importe'], 'number'],
            [['articulo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'organismo' => 'Organismo',
            'idarticulo' => 'Idarticulo',
            'articulo' => 'Articulo',
            'anio' => 'Anio',
            'cantidad' => 'Cantidad',
            'importe' => 'Importe',
        ];
    }

    public static function primaryKey()
    {
        return ['organismo','idarticulo','anio'];
    }
}
