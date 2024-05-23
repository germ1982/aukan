<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_novedad".
 *
 * @property int $idnovedad
 * @property string $titulo
 * @property string $descripcion
 * @property string $fechahora
 * @property int $estado 1 = no publicado - 2 = publicado
 * @property string|null $imagen
 * @property int|null $tipo idtipo: 68
 *
 * @property Sds_com_configuracion $tipo0
 */
class Mds_org_novedad extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $temp_imagen;
    public $borrar_adjunto;

    const NO_PUBLICADO = 1;
    const PUBLICADO = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_novedad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion', 'fechahora'], 'required'],
            [['descripcion'], 'string'],
            [['fechahora'], 'safe'],
            [['estado', 'tipo'], 'integer'],
            [['titulo', 'imagen'], 'string', 'max' => 255],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idnovedad' => 'Id Novedad',
            'titulo' => 'Título',
            'descripcion' => 'Descripción',
            'fechahora' => 'Fecha y Hora',
            'estado' => 'Estado',
            'imagen' => 'Imagen',
            'tipo' => 'Tipo',
            'temp_imagen' => 'Imagen'
        ];
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipo']);
    }

    public static function getExtension($file) {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'); 
        if (in_array($extension, $extImagenes)) { 
            return 'image';
        } else {
            return $extension;
        }
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
