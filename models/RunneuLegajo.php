<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "runneu_legajo".
 *
 * @property int $num_legajo
 * @property string $dni
 * @property string $archivo_adjunto
 */
class RunneuLegajo extends \yii\db\ActiveRecord
{
    public $archivo_adjunto;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'runneu_legajo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dni'], 'required'],
            [['archivo_adjunto'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf', 'maxSize' => 10485760], // 10MB max
        ];
    }

    /**
     * Carga el archivo a la carpeta correspondiente
     */
    public function upload()
    {
        if ($this->validate() && $this->archivo_adjunto) {
            $baseName = $this->archivo_adjunto->getBaseName();
            $fileName = 'legajo_runneu_' . $this->dni . '_' . $baseName . '.' . $this->archivo_adjunto->extension;

            // Directorio donde se guardarán los archivos subidos
            $uploadPath = Yii::getAlias('@webroot/uploads/legajo_runneu/');

            // Verifica que el directorio exista, si no, lo crea
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            // Guarda el archivo
            if ($this->archivo_adjunto->saveAs($uploadPath . $fileName)) {
                return $fileName; // Retorna el nombre del archivo guardado
            }
        }
        return false; // Retorna false si la validación o la carga falla
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'num_legajo' => 'Num Legajo',
            'dni' => 'DNI',
            'archivo_adjunto' => 'Archivo Adjunto',
        ];
    }
}
