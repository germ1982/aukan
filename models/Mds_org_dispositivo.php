<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_dispositivo".
 *
 * @property int $iddispositivo
 * @property string $descripcion
 * @property int $idorganismo
 * @property int $activo
 * @property int $idcapaitem
 *
 * @property MdsOrgContacto[] $mdsOrgContactos
 * @property SdsGisCapaItem $idcapaitem0
 * @property MdsOrgOrganismo $idorganismo0
 * @property SdsRegRegistro[] $sdsRegRegistros
 */
class Mds_org_dispositivo extends \yii\db\ActiveRecord
{
    /* {@inheritdoc} */

    const INACTIVOS=330;

    public static function tableName()
    {
        return 'mds_org_dispositivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idorganismo'], 'required'],
            [['idorganismo', 'activo', 'idcapaitem'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['idcapaitem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::className(), 'targetAttribute' => ['idcapaitem' => 'idcapaitem']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddispositivo' => 'Iddispositivo',
            'descripcion' => 'Descripción',
            'idorganismo' => 'Organismo',
            'activo' => 'Activo',
            'idcapaitem' => 'Edificio',
        ];
    }

    /**
     * Gets query for [[MdsOrgContactos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgContactos()
    {
        return $this->hasMany(Mds_org_contacto::className(), ['iddispositivo' => 'iddispositivo']);
    }

    /**
     * Gets query for [[Idcapaitem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapaitem()
    {
        return $this->hasOne(Sds_gis_capa_item::className(), ['idcapaitem' => 'idcapaitem']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismo()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[SdsRegRegistros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistros()
    {
        return $this->hasMany(Sds_reg_registro::className(), ['iddispositivo' => 'iddispositivo']);
    }

    public static function getTodoslosDisp($idorganismo)
    {
        return Mds_org_dispositivo::findBySql("SELECT * FROM mds_org_dispositivo where idorganismo = " . $idorganismo )->all();
    }

}

