<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificio_oficina".
 *
 * @property int $idoficina
 * @property string $descripcion
 * @property int $idedificio
 * @property string|null $plano_ubicacion
 * @property int|null $activo
 */
class EdificioOficina extends \yii\db\ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return 'edificio_oficina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idedificio'], 'required'],
            [['idedificio', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['plano_ubicacion'], 'string', 'max' => 45],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idoficina' => 'Id',
            'descripcion' => 'Oficina',
            'idedificio' => 'Edificio',
            'plano_ubicacion' => 'Ubicacion',
            'activo' => 'Activo',
        ];
    }
}
