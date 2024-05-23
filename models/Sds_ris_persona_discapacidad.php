<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ris_persona_discapacidad".
 *
 * @property int $idpersonadiscapacidad
 * @property int $idpersonarisneu
 * @property int $discapacidad
 *
 * @property SdsRisPersona $idpersonarisneu0
 * @property SdsComConfiguracion $discapacidad0
 */
class Sds_ris_persona_discapacidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_persona_discapacidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersonarisneu', 'discapacidad'], 'required'],
            [['idpersonarisneu', 'discapacidad', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['idpersonarisneu'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ris_persona::class, 'targetAttribute' => ['idpersonarisneu' => 'idpersonarisneu']],
            [['discapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['discapacidad' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersonadiscapacidad' => 'Idpersonadiscapacidad',
            'idpersonarisneu' => 'Idpersonarisneu',
            'discapacidad' => 'Discapacidad',
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
     * Gets query for [[Discapacidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscapacidad()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'discapacidad']);
    }

    public function getDiscapacidadRel(){
        return Sds_com_configuracion::find()->where(['idconfiguracion'=>$this->discapacidad])->one();
    }
}
