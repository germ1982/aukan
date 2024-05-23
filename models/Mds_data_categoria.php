<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_data_categoria".
 *
 * @property int $idcategoria
 * @property string $nombre
 * @property string $descripcion
 * @property string|null $icono
 * @property string|null $imagen_fondo
 *
 * @property MdsDataTablero[] $mdsDataTableros
 */
class Mds_data_categoria extends \yii\db\ActiveRecord
{
    public $temp_icono;
    public $temp_imagen_fondo;
    public $borrar_icono;
    public $borrar_imagen_fondo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_data_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'descripcion'], 'required'],
            [['borrar_icono', 'borrar_imagen_fondo'], 'safe'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['temp_icono'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, bmp', 'maxSize' => 10000000],
            [['temp_imagen_fondo'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, bmp', 'maxSize' => 10000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcategoria' => 'Id',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'icono' => 'Icono',
            'imagen_fondo' => 'Imagen Fondo',
            'temp_icono' => 'Icono',
            'temp_imagen_fondo' => 'Imagen de Fondo',
        ];
    }

    /**
     * Gets query for [[MdsDataTableros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsDataTableros()
    {
        return $this->hasMany(Mds_data_tablero::className(), ['idcategoria' => 'idcategoria']);
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
