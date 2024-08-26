<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_web_eventos".
 *
 * @property int $idevento
 * @property string|null $descripcion
 * @property string|null $fotos
 * @property string|null $titulo
 * @property int|null $activo
 */
class InformaticaWebEventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informatica_web_eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'string'],
            [['activo'], 'integer'],
            [['fotos', 'titulo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idevento' => 'Idevento',
            'descripcion' => 'Descripcion',
            'fotos' => 'Fotos',
            'titulo' => 'Titulo',
            'activo' => 'Activo',
        ];
    }
}
