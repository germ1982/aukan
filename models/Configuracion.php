<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion".
 *
 * @property int $id_configuracion
 * @property int $id_configuracion_tipo
 * @property string $descripcion
 * @property int $activo
 */
class Configuracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_configuracion_tipo', 'descripcion'], 'required'],
            [['id_configuracion_tipo', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion' => 'Id',
            'id_configuracion_tipo' => 'Tipo De Dato',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }
}
