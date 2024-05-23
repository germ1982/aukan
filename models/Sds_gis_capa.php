<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_gis_capa".
 *
 * @property int $idcapa
 * @property string $descripcion
 * @property int $activo
 * @property string $capa_icono
 *
 * @property SdsGisCapaItem[] $sdsGisCapaItems
 */
class Sds_gis_capa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $archivo_imagen;
    public static function tableName()
    {
        return 'sds_gis_capa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['activo'], 'integer'],
            [['capa_icono','descripcion'], 'string', 'max' => 100],
            [['archivo_imagen'], 'image', 'extensions' => 'png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcapa' => 'Idcapa',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'capa_icono'=>'Capa Icono',
        ];
    }

    /**
     * Gets query for [[SdsGisCapaItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsGisCapaItems()
    {
        return $this->hasMany(Sds_gis_capa_item::className(), ['idcapa' => 'idcapa']);
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

    public static function getAllCapa() {
        return Sds_gis_capa::find()
        ->where(['activo' => 1])
        ->orderBy(['descripcion' => SORT_ASC])
        ->all();
    }
}
