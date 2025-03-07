<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "runneu_legajo".
 *
 * @property int $nº_legajo
 * @property string $dni
 * @property resource|null $archivo_adjunto
 */
class RunneuLegajo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runneu_legajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nº_legajo', 'dni'], 'required'],
            [['nº_legajo'], 'integer'],
            [['archivo_adjunto'], 'string'],
            [['dni'], 'string', 'max' => 20],
            [['nº_legajo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nº_legajo' => 'Nº Legajo',
            'dni' => 'Dni',
            'archivo_adjunto' => 'Archivo Adjunto',
        ];
    }
}
