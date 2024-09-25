<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_informatica_egreso_detalle".
 *
 * @property int $iddetalle
 * @property int $idegreso
 * @property int $idarticulo
 * @property int $cantidad
 */
class StockInformaticaEgresoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_informatica_egreso_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idegreso', 'idarticulo', 'cantidad'], 'required'],
            [['idegreso', 'idarticulo', 'cantidad'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddetalle' => 'Iddetalle',
            'idegreso' => 'Idegreso',
            'idarticulo' => 'Idarticulo',
            'cantidad' => 'Cantidad',
        ];
    }
}
