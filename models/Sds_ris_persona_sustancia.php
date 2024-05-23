<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ris_persona_sustancia".
 *
 * @property int $idpersonasustancia
 * @property int $idpersonarisneu
 * @property int $sustancia
 *
 * @property SdsRisPersona $idpersonarisneu0
 * @property SdsComConfiguracion $sustancia0
 */
class Sds_ris_persona_sustancia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_persona_sustancia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersonarisneu', 'sustancia'], 'required'],
            [['idpersonarisneu', 'sustancia', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['idpersonarisneu'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ris_persona::class, 'targetAttribute' => ['idpersonarisneu' => 'idpersonarisneu']],
            [['sustancia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['sustancia' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersonasustancia' => 'Idpersonasustancia',
            'idpersonarisneu' => 'Idpersonarisneu',
            'sustancia' => 'Sustancia',
        ];
    }

    /**
     * Gets query for [[Idpersonarisneu0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSds_ris_persona()
    {
        return $this->hasOne(Sds_ris_persona::class, ['idpersonarisneu' => 'idpersonarisneu']);
    }

    /**
     * Gets query for [[Sustancia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSustancia()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'sustancia']);
    }

    public function getSustanciaRel(){
        return Sds_com_configuracion::find()->where(['idconfiguracion'=>$this->sustancia])->one();
    }
}
