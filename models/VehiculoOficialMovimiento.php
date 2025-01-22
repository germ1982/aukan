<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehiculo_oficial_movimiento".
 *
 * @property int $idmovimiento
 * @property int $idvehiculo
 * @property int $chofer
 * @property string|null $lugar_salida
 * @property string|null $lugar_destino
 * @property string $finalidad_viaje
 * @property string $fecha
 * @property string $hora
 * @property int $kilometraje
 *
 * @property VehiculoOficial $idvehiculo0
 * @property Empleado $chofer0
 */
class VehiculoOficialMovimiento extends \yii\db\ActiveRecord
{
    //se agregan estas dos variables para que funcione el filtro por fechas
    public $fdesde;
    public $fhasta;

    public static function tableName()
    {
        return 'vehiculo_oficial_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'chofer', 'finalidad_viaje', 'fecha', 'hora', 'kilometraje'], 'required'],
            [['idvehiculo', 'chofer', 'kilometraje'], 'integer'],
            [['fecha', 'hora', 'fdesde', 'fhasta'], 'safe'],//se agregan estas dos variables para que funcione el filtro por fechas
            [['lugar_salida', 'lugar_destino', 'finalidad_viaje'], 'string', 'max' => 255],
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
            'idmovimiento' => 'ID',
            'idvehiculo' => 'Vehiculo',
            'chofer' => 'Chofer',
            'lugar_salida' => 'Salida',
            'lugar_destino' => 'Destino',
            'finalidad_viaje' => 'Finalidad Viaje',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'kilometraje' => 'Kilometraje',
        ];
    }

    /**
     * Gets query for [[Idvehiculo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvehiculo0()
    {
        return $this->hasOne(VehiculoOficial::className(), ['idvehiculo' => 'idvehiculo']);
    }

    /**
     * Gets query for [[Chofer0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChofer0()
    {
        return $this->hasOne(Empleado::className(), ['idempleado' => 'chofer']);
    }
}
