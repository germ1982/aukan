<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_veh_mantenimiento".
 *
 * @property int $idmantenimiento
 * @property int $idvehiculo
 * @property string $fecha
 * @property string $detalle
 * @property int $km
 *
 * @property SdsVehVehiculo $idvehiculo0
 */
class Sds_veh_mantenimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_veh_mantenimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'fecha', 'detalle', 'km'], 'required'],
            [['idvehiculo', 'km'], 'integer'],
            [['fecha'], 'safe'],
            [['detalle'], 'string'],
            [['idvehiculo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_veh_vehiculo::className(), 'targetAttribute' => ['idvehiculo' => 'idvehiculo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmantenimiento' => 'Idmantenimiento',
            'idvehiculo' => 'Idvehiculo',
            'fecha' => 'Fecha',
            'detalle' => 'Detalle',
            'km' => 'Km',
        ];
    }

    /**
     * Gets query for [[Idvehiculo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvehiculo0()
    {
        return $this->hasOne(Sds_veh_vehiculo::className(), ['idvehiculo' => 'idvehiculo']);
    }
}
