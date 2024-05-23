<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vista_integradora".
 *
 * @property int|null $lineanro
 * @property int|null $cuenta
 * @property string $empresa
 * @property string|null $ultimo_movimiento
 * @property string|null $organismo
 * @property string|null $dependecia
 * @property string|null $responsable
 * @property string|null $equipo
 * @property string|null $imei
 * @property string|null $plan
 */
class Telefonia_vista_integradora_optic extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $baja;
    public $linea;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vista_integradora';
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
            [['lineanro', 'cuenta','baja','linea'], 'integer'],
            [['empresa'], 'required'],
            [['ultimo_movimiento','fdesde', 'fhasta','baja','linea'], 'safe'],
            [['empresa', 'organismo', 'imei', 'plan'], 'string', 'max' => 50],
            [['dependecia'], 'string', 'max' => 100],
            [['responsable'], 'string', 'max' => 114],
            [['equipo'], 'string', 'max' => 103],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lineanro' => 'Lineanro',
            'cuenta' => 'Cuenta',
            'empresa' => 'Empresa',
            'ultimo_movimiento' => 'Ultimo Movimiento',
            'organismo' => 'Organismo',
            'dependecia' => 'Dependecia',
            'responsable' => 'Responsable',
            'equipo' => 'Equipo',
            'imei' => 'Imei',
            'plan' => 'Plan',
        ];
    }
    public static function primaryKey()
    {
        return ['lineanro'];
    }
}
