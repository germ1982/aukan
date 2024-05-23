<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_provincia".
 *
 * @property int $idprovincia
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsComLocalidad[] $localidades
 */
class Sds_com_provincia extends \yii\db\ActiveRecord
{
    const ID_PROVINCIA_NEUQUEN = 58;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_provincia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idprovincia' => 'Idprovincia',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[getLocalidades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidades()
    {
        return $this->hasMany(Sds_com_localidad::className(), ['idprovincia' => 'idprovincia']);
    }

    public static function getProvinciasMostrar()
    {
        return Sds_com_provincia::findBySql("select idprovincia, descripcion from sds_com_provincia order by descripcion")->all();
    }
}
