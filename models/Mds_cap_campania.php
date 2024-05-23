<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_campania".
 *
 * @property int $idcampania
 * @property string $descripcion
 * @property int $limite_inscripciones 0: sin limite
 * @property int $estado 0: activa, 1:no activa
 */
class Mds_cap_campania extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVA = 1;
    const ESTADO_NO_ACTIVA = 0;
    public $temp_logo;
    public $borrar_logo;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_campania';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limite_inscripciones', 'estado'], 'integer'],
            [['borrar_logo'], 'safe'],
            [['logo_path','informacion'], 'string'],
            [['descripcion'], 'string', 'max' => 50],
            [['temp_logo'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, pdf, odt, ods, doc, docx, xls, xlsx', 'maxSize' => 1000000000],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcampania' => 'Nro. Campaña',
            'descripcion' => 'Nombre',
            'limite_inscripciones' => 'Limite Inscripciones (Cero si no tiene límite)',
            'estado' => 'Estado',
            'temp_logo' => "Seleccionar una imagen",
            'logo_path' => 'Logo',
            'informacion'=> 'Descripción de la Campaña (Opcional)'
        ];
    }

    public function random_filename($length, $directory, $extension)
    {
        // default to this files directory if empty...
        $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

        do {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
        } while (file_exists($dir . '/' . $key . (!empty($extension) ? '.' . $extension : '')));

        return $key . (!empty($extension) ? '.' . $extension : '');
    }

    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }
}
