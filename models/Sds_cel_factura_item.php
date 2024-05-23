<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_factura_item".
 *
 * @property int $idfacturaitem
 * @property int $idfactura
 * @property int $linea
 * @property string $concepto
 * @property float $cantidad
 * @property float $neto
 * @property float $impuestos
 * @property float $total
 * @property string $idconcepto
 *
 * @property SdsCelFactura $idfactura0
 */
class Sds_cel_factura_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_cel_factura_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idfactura', 'linea', 'concepto', 'cantidad', 'neto', 'impuestos', 'total', 'idconcepto'], 'required'],
            [['idfactura', 'linea'], 'integer'],
            [['cantidad', 'neto', 'impuestos', 'total'], 'number'],
            [['concepto'], 'string', 'max' => 255],
            [['idconcepto'], 'string', 'max' => 45],
            [['idfactura'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_cel_factura::className(), 'targetAttribute' => ['idfactura' => 'idfactura']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfacturaitem' => 'Idfacturaitem',
            'idfactura' => 'Idfactura',
            'linea' => 'Linea',
            'concepto' => 'Concepto',
            'cantidad' => 'Cantidad',
            'neto' => 'Neto',
            'impuestos' => 'Impuestos',
            'total' => 'Total',
            'idconcepto' => 'Idconcepto',
        ];
    }

    /**
     * Gets query for [[Idfactura0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdfactura0()
    {
        return $this->hasOne(Sds_cel_factura::className(), ['idfactura' => 'idfactura']);
    }
}
