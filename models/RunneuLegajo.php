<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

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
     * @var UploadedFile|null $archivo_adjunto
     */
    public $archivo_adjunto;

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
            [['num_legajo', 'dni'], 'required'],
            [['num_legajo'], 'integer'],
            [['archivo_adjunto'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf', 'maxSize' => 10485760], // Validación del archivo (extensiones y tamaño)
            [['dni'], 'string', 'max' => 20],
            [['num_legajo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'num_legajo' => 'Nº Legajo',
            'dni' => 'Dni',
            'archivo_adjunto' => 'Archivo Adjunto',
        ];
    }

    /**
     * Método para subir el archivo al servidor.
     * 
     * @return string|false El nombre del archivo si se sube correctamente, o false si hay un error.
     */
    public function upload()
    {
        if ($this->validate()) {
            // Definir el directorio de destino
            $path = Yii::getAlias('@webroot/uploads/') . $this->archivo_adjunto->baseName . '.' . $this->archivo_adjunto->extension;

            // Guardar el archivo físicamente
            if ($this->archivo_adjunto->saveAs($path)) {
                // Guardar solo la ruta en la base de datos
                $this->archivo_adjunto = $path;

                if ($this->save()) {
                    return true;
                }
            }
        }

        return false;
    }
}
