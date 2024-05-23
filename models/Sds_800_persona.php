<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_persona".
 *
 * @property int $idpersona
 * @property string $telefono
 * @property string $domicilio
 * @property int $idlocalidad
 * @property int $idgeneroautopercibido
 * @property int $idlocalidadoriundo
 *
 * @property Sds800Llamada[] $sds800Llamadas
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $idgeneroautopercibido0
 * @property SdsComLocalidad $idlocalidadoriundo0
 */
class Sds_800_persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'idlocalidad'], 'required'],
            [['idlocalidadoriundo','idpersona', 'idlocalidad','idgeneroautopercibido'], 'integer'],
            [['telefono', 'domicilio'], 'string', 'max' => 100],
            [['idpersona'], 'unique'],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idgeneroautopercibido'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idgeneroautopercibido' => 'idconfiguracion']],
            [['idlocalidadoriundo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidadoriundo' => 'idlocalidad']],
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
            'idgeneroautopercibido'  => 'Género Autopercibido',
            'idlocalidadoriundo' => 'Localidad Oriundo',
            
        ];
    }

    /**
     * Gets query for [[Sds800Llamadas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSds800Llamadas()
    {
        return $this->hasMany(Sds_800_llamada::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdgeneroautopercibido0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idgeneroautopercibido' => 'idconfiguracion']);
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
     * Gets query for [[Idlocalidadoriundo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlocalidadoriundo0()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidadoriundo' => 'idlocalidad']);
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
}
