<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_veh_modelo".
 *
 * @property int $idmodelo
 * @property string $descripcion
 * @property int $idmarca idconfiguraciontipo: 158
 * @property int $activo
 *
 * @property Sds_com_configuracion $idmarca0
 * @property SdsVehVehiculo[] $sdsVehVehiculos
 */
class Sds_veh_modelo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_veh_modelo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idmarca', 'activo'], 'required'],
            [['idmarca', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['idmarca'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idmarca' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmodelo' => 'Idmodelo',
            'descripcion' => 'Descripcion',
            'idmarca' => 'Idmarca',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Idmarca0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmarca0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idmarca']);
    }

    /**
     * Gets query for [[SdsVehVehiculos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsVehVehiculos()
    {
        return $this->hasMany(Sds_veh_vehiculo::className(), ['modelo' => 'idmodelo']);
    }
}
