<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backend".
 *
 * @property int $idbackend
 * @property string $nombre
 * @property string $descripcion
 * @property string $endpoint
 * @property int $estado
 */
class Backend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backend';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'descripcion', 'endpoint'], 'required'],
            [['descripcion'], 'string'],
            [['estado'], 'integer'],
            [['nombre', 'endpoint'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idbackend' => 'Idbackend',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'endpoint' => 'Endpoint',
            'estado' => 'Estado',
        ];
    }
}
