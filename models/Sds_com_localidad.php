<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_localidad".
 *
 * @property int $idlocalidad
 * @property string $descripcion
 * @property string $codigo_postal
 * @property int $activo
 * @property int $idprovincia
 *
 * @property Sds800Persona[] $sds800Personas
 * @property SdsComBarrio[] $sdsComBarrios
 * @property SdsComProvincia $idprovincia0
 * @property SdsVioPersona[] $sdsVioPersonas
 */
class Sds_com_localidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ID_NEUQUEN = 58035070;
    
    public static function tableName()
    {
        return 'sds_com_localidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'codigo_postal', 'activo'], 'required'],
            [['activo', 'idprovincia'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['codigo_postal'], 'string', 'max' => 8],
            [['idprovincia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_provincia::className(), 'targetAttribute' => ['idprovincia' => 'idprovincia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlocalidad' => 'Idlocalidad',
            'descripcion' => 'Descripción',
            'codigo_postal' => 'Código Postal',
            'activo' => 'Activo',
            'idprovincia' => 'Idprovincia',
        ];
    }


    public function getProvincia()
    {
        return $this->hasOne(Sds_com_provincia::className(), ['idprovincia' => 'idprovincia'])->one();
    }

    public static function getLocalidadesMostrar()
    {
        return Sds_com_localidad::findBySql("select idlocalidad,concat(loc.descripcion,' (',prov.descripcion,')') descripcion from sds_com_localidad loc,sds_com_provincia prov where prov.idprovincia=loc.idprovincia order by loc.descripcion")->all();
    }

    public static function getLocalidadesByIdProvincia($idProvincia)
    {
        return Sds_com_localidad::findBySql("select idlocalidad, descripcion from sds_com_localidad where idprovincia=$idProvincia order by descripcion")->all();
    }
}
