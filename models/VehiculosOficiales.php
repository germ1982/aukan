<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehiculos_oficiales".
 *
 * @property int $idvehiculo
 * @property string $dominio
 * @property string|null $poliza
 * @property string|null $VTO
 * @property string|null $salida
 * @property string|null $llegada
 * @property string|null $lugar
 * @property string|null $hora
 * @property int|null $kilometraje
 * @property string|null $finalidad_viaje
 */
class VehiculosOficiales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehiculos_oficiales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dominio'], 'required'],
            [['VTO', 'salida', 'llegada', 'hora'], 'safe'],
            [['kilometraje'], 'integer'],
            [['dominio'], 'string', 'max' => 20],
            [['poliza'], 'string', 'max' => 50],
            [['lugar', 'finalidad_viaje'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvehiculo' => 'Idvehiculo',
            'dominio' => 'Dominio',
            'poliza' => 'Poliza',
            'VTO' => 'Vto',
            'salida' => 'Salida',
            'llegada' => 'Llegada',
            'lugar' => 'Lugar',
            'hora' => 'Hora',
            'kilometraje' => 'Kilometraje',
            'finalidad_viaje' => 'Finalidad Viaje',
        ];
    }
}
