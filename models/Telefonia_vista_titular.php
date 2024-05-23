<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vista_titular".
 *
 * @property int $lineanro
 * @property string|null $ultimo_movimiento
 * @property string $organismo
 * @property string|null $dependencia
 * @property string|null $responsable
 * @property int $movimientos
 */
class Telefonia_vista_titular extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vista_titular';
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
            [['lineanro', 'organismo'], 'required'],
            [['lineanro', 'movimientos'], 'integer'],
            [['ultimo_movimiento','fdesde', 'fhasta'], 'safe'],
            [['organismo'], 'string', 'max' => 50],
            [['dependencia'], 'string', 'max' => 100],
            [['responsable'], 'string', 'max' => 114],
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
            'organismo' => 'Organismo',
            'dependencia' => 'Dependencia',
            'responsable' => 'Responsable',
            'movimientos' => 'Movimientos',
        ];
    }
    public static function primaryKey()
    {
        return ['lineanro'];
    }
}
