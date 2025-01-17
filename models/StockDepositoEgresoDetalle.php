<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_deposito_egreso_detalle".
 *
 * @property int $iddetalle
 * @property int $idegreso
 * @property int $idarticulo
 * @property int $cantidad
 */
class StockDepositoEgresoDetalle extends \yii\db\ActiveRecord
{
    public $descripcion;
    public $unidad_medida;
    public static function tableName()
    {
        return 'stock_deposito_egreso_detalle';
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
            'iddetalle' => 'Id',
            'idegreso' => 'Nro. Egreso',
            'idarticulo' => 'Articulo',
            'cantidad' => 'Cantidad',
        ];
    }

    public function getEgreso()
    {
        return $this->hasOne(StockDepositoEgreso::class, ['id' => 'idstock_deposito_egreso']);
    } 
}
