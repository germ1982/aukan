<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_barrio".
 *
 * @property int $idbarrio
 * @property string $nombre
 * @property int $idlocalidad
 * @property int $activo
 *
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsRisRisneu[] $sdsRisRisneus
 */
class Sds_com_barrio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_barrio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'idlocalidad'], 'required'],
            [['idlocalidad', 'activo'], 'integer'],
            [['nombre'], 'string', 'max' => 40],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idbarrio' => 'Idbarrio',
            'nombre' => 'Nombre',
            'idlocalidad' => 'Idlocalidad',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(Sds_com_localidad::className(), ['idlocalidad' => 'idlocalidad']);
    }

    public static function getBarriosByIdLocalidad($idLocalidad) {
        return Sds_com_barrio::findBySql("select idbarrio, nombre from sds_com_barrio where idlocalidad=$idLocalidad order by nombre")->all();
    }
}
