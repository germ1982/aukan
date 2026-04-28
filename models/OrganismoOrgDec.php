<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo_org_dec".
 *
 * @property int $idorganismo
 * @property int $iddecreto
 *
 * @property OrganismoDecreto $iddecreto0
 * @property Organismo $idorganismo0
 */
class OrganismoOrgDec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organismo_org_dec';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idorganismo', 'iddecreto'], 'required'],
            [['idorganismo', 'iddecreto'], 'integer'],
            [['idorganismo', 'iddecreto'], 'unique', 'targetAttribute' => ['idorganismo', 'iddecreto']],
            //[['iddecreto'], 'exist', 'skipOnError' => true, 'targetClass' => OrganismoOrgDec::className(), 'targetAttribute' => ['iddecreto' => 'iddecreto']],
            // CORRECCIÓN AQUÍ: El targetClass debe ser el modelo de Decretos, no este mismo modelo
            [['iddecreto'], 'exist', 'skipOnError' => true, 'targetClass' => OrganismoDecreto::className(), 'targetAttribute' => ['iddecreto' => 'iddecreto']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idorganismo' => 'Idorganismo',
            'iddecreto' => 'Iddecreto',
        ];
    }

    /**
     * Gets query for [[Iddecreto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddecreto0()
    {
        // Cambié OrganismoOrgDec por OrganismoDecreto
        return $this->hasOne(OrganismoDecreto::className(), ['iddecreto' => 'iddecreto']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Organismo::className(), ['idorganismo' => 'idorganismo']);
    }
}
