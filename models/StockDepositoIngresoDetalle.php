<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_deposito_ingreso_detalle".
 *
 * @property int $iddetalle
 * @property int $idingreso
 * @property int $idarticulo
 * @property int $cantidad
 */
class StockDepositoIngresoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_deposito_ingreso_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idingreso', 'idarticulo', 'cantidad'], 'required'],
            [['idingreso', 'idarticulo', 'cantidad'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddetalle' => 'Id',
            'idingreso' => 'Nro. Ingreso',
            'idarticulo' => 'Articulo',
            'cantidad' => 'Cantidad',
        ];
    }

    public function getIngreso()
    {
        return $this->hasOne(StockDepositoIngreso::class, ['id' => 'idstock_deposito_ingreso']);
    } 
}
