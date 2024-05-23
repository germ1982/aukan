<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_movimiento".
 *
 * @property int $idmovimiento
 * @property int $linea
 * @property int $numero
 * @property int $organismo
 * @property string|null $observaciones
 * @property int $baja
 * @property string $fecha
 *
 * @property SdsComConfiguracion $organismo0
 */
class Sds_cel_movimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $fdesde;
    public $fhasta;

    public static function tableName()
    {
        return 'sds_cel_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['linea', 'numero', 'organismo', 'fecha'], 'required'],
            [['linea', 'numero', 'organismo', 'baja'], 'integer'],
            [['observaciones'], 'string'],
            [['fecha', 'fdesde', 'fhasta'], 'safe'],
            [['organismo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['organismo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Idmovimiento',
            'linea' => 'Linea',
            'numero' => 'Numero',
            'organismo' => 'Organismo',
            'observaciones' => 'Observaciones',
            'baja' => 'Baja',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * Gets query for [[Organismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismo0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'organismo']);
    }
}
