<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_oficina".
 *
 * @property int $idoficina
 * @property string $descripcion
 * @property int $cupo
 * @property int $activo
 *
 * @property MdsOrgContacto[] $mdsOrgContactos
 */
class Mds_org_oficina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_oficina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'cupo', 'activo'], 'required'],
            [['cupo', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idoficina' => 'Idoficina',
            'descripcion' => 'Descripcion',
            'cupo' => 'Cupo',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[MdsOrgContactos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgContactos()
    {
        return $this->hasMany(Mds_org_contacto::class, ['idoficina' => 'idoficina']);
    }
}
