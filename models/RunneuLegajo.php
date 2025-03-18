<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "runneu_legajo".
 *
 * @property int $id
 * @property int $num_legajo
 * @property string $dni
 * @property string $archivo_adjunto
 */
class RunneuLegajo extends \yii\db\ActiveRecord
{
    public $archivo_adjunto_file;  // Para manejar la carga del archivo

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'runneu_legajo';  // Asegúrate de que el nombre de la tabla sea correcto
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dni', 'num_legajo'], 'required'],  // Validamos que num_legajo y dni sean obligatorios
            [['num_legajo'], 'integer'],  // Validamos que num_legajo sea un entero
            [['dni'], 'string', 'max' => 20],  // El campo DNI es una cadena de hasta 20 caracteres
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'num_legajo' => 'Num Legajo',
            'dni' => 'DNI',
            'archivo_adjunto' => 'Archivo Adjunto',
        ];
    }
}
