<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_organismo_externo".
 *
 * @property int $idorganismoexterno
 * @property string $descripcion
 * @property int $activo
 *
 * @property MdsSegUsuario[] $mdsSegUsuarios
 */
class Mds_org_organismo_externo extends \yii\db\ActiveRecord
{

    public $temp_logo;
    public $borrar_logo;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_organismo_externo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idorganismoexterno', 'descripcion', 'activo'], 'required'],
            [['idorganismoexterno', 'activo'], 'integer'],
            [['borrar_logo'], 'safe'],
            [['logo','informacion','link_externo'], 'string'],
            [['descripcion'], 'string', 'max' => 100],
            [['idorganismoexterno'], 'unique'],
            [['temp_logo'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png', 'maxSize' => 1000000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idorganismoexterno' => 'Idorganismoexterno',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'temp_logo' => "Seleccionar una imagen",
            'logo' => 'Logo',
            'informacion'=> 'Información del Organismo (Opcional)'
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
