<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_factura".
 *
 * @property int $idfactura
 * @property int $periodo_mes
 * @property int $periodo_anio
 * @property string $fecha_carga
 * @property int $cuenta
 * @property string|null $observaciones
 *
 * @property SdsCelFacturaItem[] $sdsCelFacturaItems
 */
class Sds_cel_factura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $fdesde;
    public $fhasta;

    public static function tableName()
    {
        return 'sds_cel_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodo_mes', 'periodo_anio', 'fecha_carga', 'cuenta'], 'required'],
            [['periodo_mes', 'periodo_anio', 'cuenta'], 'integer'],
            [['fecha_carga', 'fdesde', 'fhasta'], 'safe'],
            [['observaciones'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfactura' => 'Idfactura',
            'periodo_mes' => 'Perido Mes',
            'periodo_anio' => 'Periodo Anio',
            'fecha_carga' => 'Fecha Carga',
            'cuenta' => 'Cuenta',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[SdsCelFacturaItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsCelFacturaItems()
    {
        return $this->hasMany(Sds_cel_factura_item::className(), ['idfactura' => 'idfactura']);
    }
}
