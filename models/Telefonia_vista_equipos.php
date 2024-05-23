<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vista_equipos".
 *
 * @property int|null $lineanro
 * @property string|null $ultimo_movimiento
 * @property string|null $modelo
 * @property string $dispositivo
 * @property string|null $imei
 * @property int $movimientos
 */
class Telefonia_vista_equipos extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vista_equipos';
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
            [['lineanro', 'movimientos'], 'integer'],
            [['fdesde', 'fhasta','ultimo_movimiento'], 'safe'],
            [['dispositivo'], 'required'],
            [['modelo'], 'string', 'max' => 103],
            [['dispositivo', 'imei'], 'string', 'max' => 50],
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
            'modelo' => 'Modelo',
            'dispositivo' => 'Dispositivo',
            'imei' => 'Imei',
            'movimientos' => 'Movimientos',
        ];
    }
    public static function primaryKey()
    {
        return ['lineanro'];
    }
}
