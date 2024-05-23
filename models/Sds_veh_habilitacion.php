<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_veh_habilitacion".
 *
 * @property int $idhabilitacion
 * @property string $detalle
 * @property string $vencimiento
 * @property string|null $adjunto
 * @property int $tipo
 * @property int $idvehiculo
 *
 * @property SdsComConfiguracion $tipo0
 * @property SdsVehVehiculo $idvehiculo0
 */
class Sds_veh_habilitacion extends \yii\db\ActiveRecord
{
    public $tipo_descripcion;
    public $delete_file;
    public $temp_file;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_veh_habilitacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detalle', 'vencimiento', 'tipo', 'idvehiculo'], 'required'],
            [['detalle', 'adjunto', 'tipo_descripcion'], 'string'],
            [['delete_file'], 'safe'],
            [['tipo', 'idvehiculo'], 'integer'],
            [['temp_file'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idvehiculo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_veh_vehiculo::class, 'targetAttribute' => ['idvehiculo' => 'idvehiculo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idhabilitacion' => 'Idhabilitacion',
            'detalle' => 'Detalle',
            'vencimiento' => 'Vencimiento',
            'adjunto' => 'Adjunto',
            'tipo' => 'Tipo',
            'idvehiculo' => 'Idvehiculo',
            'tipo_descripcion' => 'Tipo',
            'temp_file' => 'Seleccionar un Archivo (imagen o PDF)'
        ];
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }

    /**
     * Gets query for [[Idvehiculo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvehiculo0()
    {
        return $this->hasOne(Sds_veh_vehiculo::class, ['idvehiculo' => 'idvehiculo']);
    }
}
