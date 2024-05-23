<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_persona".
 *
 * @property int $idpersona
 * @property string $telefono
 * @property string $domicilio
 * @property int $idlocalidad
 *
 * @property SdsVioIntervencion[] $sdsVioIntervencions
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $idconfiguracion0
 * @property SdsComConfiguracion $nacionalidad0
 * @property SdsComConfiguracion $generoautopercibido0
 * @property SdsComLocalidad $localidadoriunda0
 */
class Sds_vio_persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono', 'domicilio', 'idlocalidad'], 'required'],
            [['idlocalidad'], 'integer'],
            [['telefono'], 'string', 'max' => 45],
            [['domicilio'], 'string', 'max' => 100],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Idpersona',
            'telefono' => 'Telefono',
            'domicilio' => 'Domicilio',
            'idlocalidad' => 'Idlocalidad',
        ];
    }

    /**
     * Gets query for [[SdsVioIntervencions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsVioIntervencions()
    {
        return $this->hasMany(Sds_vio_intervencion::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Generoautopercibido0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGeneroautopercibido0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion'=> 'genero_autopercibido']);
    }

    /**
     * Gets query for [[Localidadoriunda0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidadoriunda0()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'localidad_oriunda']);
    }

    /**
     * Gets query for [[Nacionalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNacionalidad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'nacionalidad']);
    }
}
