<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_deposito_ingreso".
 *
 * @property int $idingreso
 * @property string $fecha
 * @property int $idorigen
 * @property string|null $origen_referencia
 * @property int $idempleado_recepcion
 * @property int $idusuario_carga
 * @property string|null $observacion
 */
class StockDepositoIngreso extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public static function tableName()
    {
        return 'stock_deposito_ingreso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'idorigen', 'idempleado_recepcion'], 'required'],
            [['fecha', 'fdesde', 'fhasta'], 'safe'],
            [['idorigen', 'idempleado_recepcion', 'idusuario_carga','idusuario_edicion'], 'integer'],
            [['origen_referencia'], 'string', 'max' => 100],
            [['observacion'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idingreso' => 'Nro.',
            'fecha' => 'Fecha',
            'idorigen' => 'Origen',
            'origen_referencia' => 'Referencia Origen',
            'idempleado_recepcion' => 'Receptor',
            'idusuario_carga' => 'Carga',
            'idusuario_edicion' => 'Edicion',
            'observacion' => 'Observacion',
        ];
    }
    
    /* Esto indica que cada registro en StockDepositoIngreso tiene muchos StockDepositoIngresoDetalle. */
    public function getDetalles()
    {
        return $this->hasMany(StockDepositoIngresoDetalle::class, ['idstock_deposito_ingreso' => 'id']);
    }
}
