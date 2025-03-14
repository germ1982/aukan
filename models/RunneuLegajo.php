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
    public $archivo_adjunto;  // Para manejar la carga del archivo

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
            [['archivo_adjunto'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, doc, docx, jpg, jpeg, png', 'checkExtensionByMimeType' => false],  // Validación de archivo
        ];
    }

    /**
     * Carga el archivo a la carpeta correspondiente
     * @return string|false El nombre del archivo si se carga correctamente, o false si hubo un error
     */
    public function upload()
    {
        if ($this->validate() && $this->archivo_adjunto) {
            // Genera un nombre único para el archivo
            $fileName = 'legajo_runneu_' .  $this->dni . '.' . $this->archivo_adjunto->extension;

            // Directorio donde se guardarán los archivos subidos
            $uploadPath = Yii::getAlias('@webroot/uploads/legajo_runneu/');

            // Verifica que el directorio exista, si no, lo crea
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0775, true)) {
                    Yii::error("No se pudo crear el directorio: $uploadPath");
                    return false; // Retorna falso si no se pudo crear el directorio
                }
            }

            // Verifica que el archivo ha sido cargado correctamente
            if ($this->archivo_adjunto && $this->archivo_adjunto->tempName) {
                // Guarda el archivo en la ruta correspondiente
                if ($this->archivo_adjunto->saveAs($uploadPath . $fileName)) {
                    Yii::info("Archivo guardado correctamente: " . $uploadPath . $fileName);
                    return $fileName; // Retorna el nombre del archivo guardado
                } else {
                    Yii::error("Error al guardar el archivo '$fileName' en la ruta: " . $uploadPath . $fileName);
                }
            } else {
                Yii::error("El archivo no ha sido cargado correctamente.");
            }
        }
        return false;
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
