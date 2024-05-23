<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vista_lineas".
 *
 * @property int|null $lineanro
 * @property string|null $ultimo_movimiento
 * @property int|null $cuenta
 * @property string|null $simcard
 * @property string $empresa
 * @property int $movimientos
 */
class Telefonia_vista_linea extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vista_lineas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lineanro', 'cuenta', 'movimientos'], 'integer'],
            [['fdesde', 'fhasta','ultimo_movimiento'], 'safe'],
            [['empresa'], 'required'],
            [['simcard', 'empresa'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lineanro' => 'Lineanro',
            'ultimo_movimiento' => 'Ultimo Movimiento',
            'cuenta' => 'Cuenta',
            'simcard' => 'Simcard',
            'empresa' => 'Empresa',
            'movimientos' => 'Movimientos',
        ];
    }
    public static function primaryKey()
    {
        return ['lineanro'];
    }
}
