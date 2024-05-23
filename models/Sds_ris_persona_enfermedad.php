<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ris_persona_enfermedad".
 *
 * @property int $idpersonaenfermedad
 * @property int $idpersonarisneu
 * @property int $enfermedad
 *
 * @property SdsRisPersona $idpersonarisneu0
 * @property SdsComConfiguracion $enfermedad0
 */
class Sds_ris_persona_enfermedad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_persona_enfermedad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersonarisneu', 'enfermedad'], 'required'],
            [['idpersonarisneu', 'enfermedad', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['idpersonarisneu'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ris_persona::class, 'targetAttribute' => ['idpersonarisneu' => 'idpersonarisneu']],
            [['enfermedad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['enfermedad' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersonaenfermedad' => 'Idpersonaenfermedad',
            'idpersonarisneu' => 'Idpersonarisneu',
            'enfermedad' => 'Enfermedad',
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
     * Gets query for [[Enfermedad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEnfermedad()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'enfermedad']);
    }

    public function getEnfermedadRel(){
        return Sds_com_configuracion::find()->where(['idconfiguracion'=>$this->enfermedad])->one();
    }
}
