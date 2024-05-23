<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_expediente".
 *
 * @property int $idexpediente
 * @property string $fecha_ingreso
 * @property string $expediente
 * @property string|null $gde
 * @property string $causante
 * @property string $extracto
 * @property int|null $pedido_numero
 * @property string|null $destino
 * @property string|null $fecha_salida
 * @property int $idorganismo
 *
 * @property MdsOrgOrganismo $idorganismo0
 */
class Mds_org_expediente extends \yii\db\ActiveRecord
{
    public $fidesde;
    public $fihasta;
    public $fsdesde;
    public $fshasta;

    public static function tableName()
    {
        return 'mds_org_expediente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_ingreso', 'expediente', 'causante', 'extracto', 'idorganismo'], 'required'],
            [['fecha_ingreso', 'fecha_salida','fidesde', 'fihasta','fsdesde', 'fshasta'], 'safe'],
            [['extracto'], 'string'],
            [['pedido_numero', 'idorganismo'], 'integer'],
            [['expediente', 'gde'], 'string', 'max' => 45],
            [['causante', 'destino'], 'string', 'max' => 255],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_expediente::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idexpediente' => 'Idexpediente',
            'fecha_ingreso' => 'Fecha Ingreso',
            'expediente' => 'Expediente',
            'gde' => 'Gde',
            'causante' => 'Causante',
            'extracto' => 'Extracto',
            'pedido_numero' => 'Pedido Numero',
            'destino' => 'Destino',
            'fecha_salida' => 'Fecha Salida',
            'idorganismo' => 'Idorganismo',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }
}
