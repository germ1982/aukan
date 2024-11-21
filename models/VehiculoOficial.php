<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehiculo_oficial".
 *
 * @property int $idvehiculo
 * @property string $dominio
 * @property string|null $poliza
 * @property string|null $VTO
 * @property string|null $idmarca
 * @property string|null $modelo
 * @property string|null $color
 * @property string|null $anio
 */
class VehiculoOficial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehiculo_oficial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dominio', 'modelo', 'color', 'idmarca'], 'required'],
            [['VTO','poliza'], 'safe'],
            [['idmarca','anio'], 'integer'],
            [['dominio'], 'string', 'max' => 20],
            [['modelo'], 'string', 'max' => 50],
            [['color'], 'string', 'max' => 50],
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
            'idmarca' => 'Marca',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'anio' => 'Año'
        ];
    }
}
