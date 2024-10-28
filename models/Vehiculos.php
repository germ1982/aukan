<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehiculos".
 *
 * @property int $idvehiculo
 * @property int|null $idempleado
 * @property int|null $idpersona
 * @property string $dominio
 * @property int|null $idmarca
 * @property string|null $modelo
 * @property string|null $color
 * @property int|null $vehiculo_oficial
 */
class Vehiculos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehiculos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idempleado', 'idpersona', 'idmarca', 'vehiculo_oficial', ], 'integer'],
            [['dominio'], 'required'],
            [['dominio'], 'string', 'max' => 20],
            [['modelo'], 'string', 'max' => 100],
            [['color'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvehiculo' => 'idvehiculo',
            'idempleado' => 'Empleado',
            'idpersona' => 'Persona',
            'dominio' => 'Dominio',
            'idmarca' => 'Marca',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'vehiculo_oficial' => 'Vehiculo Oficial',
        ];
    }
}
