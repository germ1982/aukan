<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_diccionario".
 *
 * @property int $idcorreccion
 * @property string $palabra_mal
 * @property string $palabra_correcta
 */
class ConfiguracionDiccionario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_diccionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['palabra_mal', 'palabra_correcta'], 'required'],
            [['palabra_mal', 'palabra_correcta'], 'string', 'max' => 100],
            [['palabra_mal'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcorreccion' => 'Idcorreccion',
            'palabra_mal' => 'Palabra Mal',
            'palabra_correcta' => 'Palabra Correcta',
        ];
    }
}
