<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificio".
 *
 * @property int $idedificio
 * @property string $descripcion_fija
 * @property string $descripcion_gestion
 * @property int $idlocalidad
 * @property string|null $direccion_calle
 * @property int|null $direccion_altura
 * @property string|null $direccion
 * @property string|null $geolocalizacion
 * @property int|null $activo
 */
class Edificio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edificio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_fija', 'descripcion_gestion', 'idlocalidad'], 'required'],
            [['idlocalidad', 'direccion_altura', 'activo'], 'integer'],
            [['geolocalizacion'], 'string'],
            [['descripcion_fija', 'descripcion_gestion', 'direccion_calle'], 'string', 'max' => 100],
            [['direccion'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idedificio' => 'Id',
            'descripcion_fija' => 'Descripcion Fija',
            'descripcion_gestion' => 'Descripcion Gestion',
            'idlocalidad' => 'Localidad',
            'direccion_calle' => 'Calle',
            'direccion_altura' => 'Altura',
            'direccion' => 'Direccion',
            'geolocalizacion' => 'Geolocalizacion',
            'activo' => 'Activo',
        ];
    }
}
