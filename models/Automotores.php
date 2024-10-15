<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "automotores".
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
class Automotores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'automotores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idempleado', 'idpersona', 'idmarca', 'vehiculo_oficial'], 'integer'],
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
            'idvehiculo' => 'Idvehiculo',
            'idempleado' => 'Idempleado',
            'idpersona' => 'Idpersona',
            'dominio' => 'Dominio',
            'idmarca' => 'Idmarca',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'vehiculo_oficial' => 'Vehiculo Oficial',
        ];
    }
}
