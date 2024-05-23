<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_organismo".
 *
 * @property int $idorganismo
 * @property string $descripcion
 * @property int $padre
 * @property int $nivel
 * @property int $activo
 *
 * @property MdsOrgContacto[] $mdsOrgContactos
 * @property MdsOrgDispositivo[] $mdsOrgDispositivos
 * @property SdsRegRegistro[] $sdsRegRegistros
 */
class Mds_org_organismo extends \yii\db\ActiveRecord
{
    public $vinculaciones; //Arreglo con ids de organismos vinculados. A persistir en tabla intermedia de mds_org_organismo_vinculacion

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_organismo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'nivel', 'abreviatura'], 'required'],
            [['padre', 'nivel', 'activo', 'idrubro','recepcion'], 'integer'],
            ['vinculaciones', 'safe'],
            [['nivel'], 'integer', 'max' => 4, 'min' => 1],
            [['descripcion'], 'string', 'max' => 200],
            [['abreviatura'], 'string', 'max' => 100],
            [['padre'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['padre' => 'idorganismo']],
            [['idrubro'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idrubro' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idorganismo' => 'Idorganismo',
            'descripcion' => 'Descripcion',
            'padre' => 'Padre',
            'nivel' => 'Nivel',
            'activo' => 'Activo',
            'idrubro' => 'Rubro',
            'recepcion' => 'Recepcion'
        ];
    }

    public static function getDescripcion($idorganismo)
    {
        $organismo=Mds_org_organismo::findOne($idorganismo);
        return $organismo->descripcion;
    }

    /**
     * Gets query for [[MdsOrgContactos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgContactos()
    {
        return $this->hasMany(Mds_org_contacto::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[MdsOrgDispositivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgDispositivos()
    {
        return $this->hasMany(Mds_org_dispositivo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[SdsRegRegistros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistros()
    {
        return $this->hasMany(Sds_reg_registro::className(), ['idorganismo' => 'idorganismo']);
    }

    public static function getOrganismoRaiz()
    {
        return Mds_org_organismo::findBySql("SELECT * FROM mds_org_organismo where padre is null")->one();
    }

    public static function getOrganismosHijos($padre, $usuario)
    {
        return Mds_org_organismo::findBySql(
            "SELECT * FROM mds_org_organismo 
            where padre = " . $padre->idorganismo . " and (" . $usuario . "=0 or 
            (idorganismo in (select idorganismo from mds_org_dispositivo disp where disp.idcapaitem in 
            (SELECT ici.idcapaitem FROM mds_seg_usuario_capa_item ici WHERE ici.idusuario=" . $usuario . "))
            OR IFNULL((SElECT COUNT(ici.idusuario) 
            FROM mds_seg_usuario_capa_item ici WHERE ici.idusuario=" . $usuario . "), 0) = 0))
            order by descripcion"
        )->all();
    }
}
