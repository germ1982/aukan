<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_informatica_ingreso".
 *
 * @property int $idingreso
 * @property string $fecha
 * @property int $idorigen
 * @property string|null $origen_referencia
 * @property int $idempleado_recepcion
 * @property int $idusuario_carga
 * @property string|null $observacion
 */
class StockInformaticaIngreso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_informatica_ingreso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'idorigen', 'idempleado_recepcion', 'idusuario_carga'], 'required'],
            [['fecha'], 'safe'],
            [['idorigen', 'idempleado_recepcion', 'idusuario_carga'], 'integer'],
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
            'idingreso' => 'Idingreso',
            'fecha' => 'Fecha',
            'idorigen' => 'Idorigen',
            'origen_referencia' => 'Origen Referencia',
            'idempleado_recepcion' => 'Idempleado Recepcion',
            'idusuario_carga' => 'Idusuario Carga',
            'observacion' => 'Observacion',
        ];
    }
}
