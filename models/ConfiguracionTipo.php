<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_tipo".
 *
 * @property int $id_configuracion_tipo
 * @property string $descripcion
 * @property int $activo
 */
class ConfiguracionTipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion_tipo' => 'Id Configuracion Tipo',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }
}
