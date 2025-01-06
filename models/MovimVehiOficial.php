<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movim_vehi_oficial".
 *
 * @property int $idmovimiento
 * @property int $vehiculo
 * @property string $dominio
 * @property int $chofer
 * @property string $salida
 * @property string $regreso
 * @property string $finalidad_viaje
 * @property string $fecha
 * @property string $lugar
 * @property string $hora
 * @property int $kilometraje
 *
 * @property VehiculoOficial $vehiculo0
 * @property Empleado $chofer0
 */
class MovimVehiOficial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movim_vehi_oficial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'chofer', 'salida', 'regreso', 'finalidad_viaje', 'fecha', 'lugar', 'hora', 'kilometraje'], 'required'],
            [['chofer', 'kilometraje'], 'integer'],
            [['salida', 'regreso', 'fecha', 'hora'], 'safe'],            
            [['finalidad_viaje', 'lugar'], 'string', 'max' => 255],
            [['idvehiculo'], 'exist', 'skipOnError' => true, 'targetClass' => VehiculoOficial::className(), 'targetAttribute' => ['idvehiculo' => 'idvehiculo']],
            [['chofer'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::className(), 'targetAttribute' => ['chofer' => 'idempleado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Idmovimiento',
            'idvehiculo' => 'Vehiculo',             
            'chofer' => 'Chofer',
            'salida' => 'Salida',
            'regreso' => 'Regreso',
            'finalidad_viaje' => 'Finalidad Viaje',
            'fecha' => 'Fecha',
            'lugar' => 'Lugar',
            'hora' => 'Hora',
            'kilometraje' => 'Kilometraje',
        ];
    }

    /**
     * Gets query for [[Vehiculo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehiculo0()
    {
        return $this->hasOne(VehiculoOficial::class, ['idvehiculo' => 'idvehiculo']);
    }

    /**
     * Gets query for [[Chofer0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function  getChofer0()
    {
        return $this->hasOne(Empleado::className(), ['idempleado' => 'chofer']);
    }
}
