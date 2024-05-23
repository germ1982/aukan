<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vista_abonos".
 *
 * @property int|null $lineanro
 * @property string|null $ultimo_movimiento
 * @property string|null $externos
 * @property string $abonodato
 * @property int $movimientos
 */
class Telefonia_vista_abonos extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vista_abonos';
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
            [['abonodato'], 'required'],
            [['externos'], 'string', 'max' => 20],
            [['abonodato'], 'string', 'max' => 50],
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
            'externos' => 'Externos',
            'abonodato' => 'Abonodato',
            'movimientos' => 'Movimientos',
        ];
    }
    public static function primaryKey()
    {
        return ['lineanro'];
    }
}
