<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_gis_capa_item".
 *
 * @property int $idcapaitem
 * @property int $idcapa
 * @property string $descripcion
 * @property string|null $detalle
 * @property float $latitud
 * @property float $longitud
 * @property int $estado '1: verde, 2: amarillo, 3:rojo'
 * @property int $activo
 * @property int $tipo //1:puntos, 2 :zona
 * @property string $direccion
 * @property string $coleccion_coordenadas
 * @property int|null $idubicacion
 * @property int $privacidad 
 * @property string $contacto_web
 * @property string $contacto_telefono_1
 * @property string $contacto_telefono_2
 * @property string $detalle_interno 
 * @property string $contacto_instagram 
 * @property string $contacto_facebook  
 * @property string $contacto_twitter 
 * @property string $contacto_email 
 * @property int $notificar_email  
 * @property string $imagen 
 * @property string $referencia_externa 
 * @property Sds_gis_capa_item $referencia_externa0
 * 
 * @property MdsOrgDispositivo[] $mdsOrgDispositivos
 * @property SdsGisCapa $idcapa0
 *
 *
 */
class Sds_gis_capa_item extends \yii\db\ActiveRecord
{
    const ESTADO_VERDE = 1;
    const ESTADO_AMARILLO = 2;
    const ESTADO_ROJO = 3;

    public $coordenadas;
    public $tematicas;

    /**
     * {@inheritdoc}
     */
    public $archivo_imagen;
    public static function tableName()
    {
        return 'sds_gis_capa_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcapa', 'descripcion', 'latitud', 'longitud', 'estado', 'tipo'], 'required'],
            [['notificar_email', 'privacidad', 'idcapa', 'estado', 'activo', 'tipo'], 'integer'],
            [['referencia_externa', 'detalle_interno', 'detalle'], 'string'],
            [['latitud', 'longitud'], 'number'],
            [['imagen', 'contacto_email', 'contacto_twitter', 'contacto_facebook', 'contacto_instagram', 'descripcion'], 'string', 'max' => 100],
            [['direccion'], 'string', 'max' => 255],
            [['contacto_web'], 'string', 'max' => 100],
            [['contacto_telefono_1', 'contacto_telefono_2'], 'string', 'max' => 50],
            [['idcapa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa::className(), 'targetAttribute' => ['idcapa' => 'idcapa']],
            [['archivo_imagen'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcapaitem' => 'Idcapaitem',
            'idcapa' => 'Capa',
            'descripcion' => 'Descripción',
            'detalle' => 'Detalle',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'estado' => 'Estado',
            'activo' => 'Activo',
            'direccion' => 'Dirección',
            'idubicacion' => 'Ubicacion',
            'privacidad' => 'Privacidad',
            'contacto_web' => 'Web',
            'contacto_telefono_1' => 'Telefono Principal',
            'contacto_telefono_2' => 'Telefono Alternativo',
            'detalle_interno' => 'Detalle Interno',
            'contacto_instagram' => 'Instagram',
            'contacto_facebook' => 'Facebook',
            'contacto_twitter' => 'Twitter',
            'contacto_email' => 'Email',
            'notificar_email' => 'Notificar Email',
            'imagen' => 'Imagen',
            'referencia_externa' => 'Referencia Externa',
        ];
    }

    /**
     * Gets query for [[MdsOrgDispositivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgDispositivos()
    {
        return $this->hasMany(Mds_org_dispositivo::className(), ['idcapaitem' => 'idcapaitem']);
    }

    /**
     * Gets query for [[Idcapa0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapa()
    {
        return $this->hasOne(Sds_gis_capa::className(), ['idcapa' => 'idcapa']);
    }
    public function getReferencia_externa0()
    {
        return $this->hasOne(Sds_gis_capa::className(), ['idcapaitem' => 'referencia_externa']);
    }
    public function getTematicas()
    {
        return Sds_gis_item_tematica::find()->where(['iditem' => $this->idcapaitem])->all();
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

    public static function getCapaItemByIdCapa($id) {
        return Sds_gis_capa_item::find()
        ->where(['activo' => 1])
        ->andWhere(['idcapa' => $id])
        ->all();
    }
}
