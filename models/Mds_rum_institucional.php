<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_institucional".
 *
 * @property int $id
 * @property int $autor
 * @property string $comment_status
 * @property int $comment_count
 * @property string $contenido
 * @property string $titulo
 * @property int $activo
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fecha_publicacion
 * @property string $hora_publicacion
 * @property int $publicado
 * @property string $imagen
 */
class Mds_rum_institucional extends \yii\db\ActiveRecord
{
    public $archivo_imagen;
    public $auxiliar; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_institucional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['autor', 'comment_count', 'activo', 'publicado'], 'integer'],
            [['contenido', 'titulo'], 'required'],
            [['contenido', 'titulo'], 'string'],
            [['fechamodificacion', 'horamodificacion', 'fechaalta', 'horaalta', 'fecha_publicacion', 'hora_publicacion'], 'safe'],
            [['comment_status'], 'string', 'max' => 20],
            [['imagen'], 'string', 'max' => 255],
            [['archivo_imagen'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'autor' => 'Autor',
            'comment_status' => 'Comment Status',
            'comment_count' => 'Comment Count',
            'contenido' => 'Contenido',
            'titulo' => 'Titulo',
            'activo' => 'Activo',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fecha_publicacion' => 'Fecha Publicacion',
            'hora_publicacion' => 'Hora Publicacion',
            'publicado' => 'Publicado',
            'imagen' => 'Imagen',
        ];
    }
    public function random_filename($length, $directory , $extension )
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
}
