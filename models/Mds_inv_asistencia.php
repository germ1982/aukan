<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_inv_asistencia".
 *
 * @property int $idasistencia
 * @property string $descripcion
 * @property int $idconfiguracion Clave foranea a la tabla sds_com_configuracion
 * @property int $idpersona Clave foranea a la tabla mds_inv_persona
 *
 * @property SdsComConfiguracion $idconfiguracion0
 * @property MdsInvPersona $idpersona0
 */
class Mds_inv_asistencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_inv_asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'idconfiguracion', 'idpersona'], 'required'],
            [['idconfiguracion', 'idpersona'], 'integer'],
            [['descripcion'], 'string', 'max' => 150],
            [['idconfiguracion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idconfiguracion' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_inv_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idasistencia' => 'Idasistencia',
            'descripcion' => 'Descripcion',
            'idconfiguracion' => 'Idconfiguracion',
            'idpersona' => 'Idpersona',
        ];
    }

    /**
     * Gets query for [[Idconfiguracion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdconfiguracion0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idconfiguracion']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Mds_inv_persona::className(), ['idpersona' => 'idpersona']);
    }
}
