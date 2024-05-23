<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_capacitacion".
 *
 * @property int $idcapacitacion
 * @property string $descripcion
 * @property int $tematica
 * @property int $idusuario
 * @property int $idorganismo
 * @property int $idorganismoexterno 
 * @property string $detalle
 *
 * @property MdsOrgOrganismo $idorganismo0
 * @property MdsOrgOrganismoExterno $idorganismoexterno0
 * @property SdsComConfiguracion $tematica0
 * @property MdsSegUsuario $idusuario0
 * @property MdsCapInstancia[] $mdsCapInstancias
 */
class Mds_cap_capacitacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_capacitacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'tematica', 'idusuario', 'detalle', 'objetivos', 'perfil'], 'required'],
            [['tematica', 'idusuario', 'idorganismo'], 'integer'],
            [['detalle', 'objetivos', 'perfil', 'nombre_corto'], 'string'],
            [['descripcion'], 'string', 'max' => 255],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['idorganismoexterno'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo_externo::className(), 'targetAttribute' => ['idorganismoexterno' => 'idorganismoexterno']],
            [['tematica'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tematica' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcapacitacion' => 'Idcapacitacion',
            'descripcion' => 'Descripcion',
            'tematica' => 'Tematica',
            'idusuario' => 'Idusuario',
            'idorganismo' => 'Idorganismo',
            'idorganismoexterno' => 'Idorganismo Externo',
            'detalle' => 'Detalle',
            'nombre_corto'=>'Nombre Corto',
            'objetivos' => 'Objetivos',
            'perfil'=> 'Perfil',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Tematica0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTematica0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tematica']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[MdsCapInstancias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsCapInstancias()
    {
        return $this->hasMany(Mds_cap_instancia::className(), ['idcapacitacion' => 'idcapacitacion']);
    }
}
