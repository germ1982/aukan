<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_organismo_vinculacion".
 *
 * @property int $idorganismovinculacion
 * @property int $idorganismo
 * @property int $vinculacion
 *
 * @property MdsOrgOrganismo $idorganismo0
 * @property MdsOrgOrganismo $vinculacion0
 */
class Mds_org_organismo_vinculacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_organismo_vinculacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idorganismo', 'vinculacion'], 'required'],
            [['idorganismo', 'vinculacion'], 'integer'],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['vinculacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['vinculacion' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idorganismovinculacion' => 'Idorganismovinculacion',
            'idorganismo' => 'Idorganismo',
            'vinculacion' => 'Vinculacion',
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
     * Gets query for [[Vinculacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVinculacion0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'vinculacion']);
    }
}
