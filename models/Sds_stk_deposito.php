<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_deposito".
 *
 * @property int $iddeposito
 * @property string $descripcion
 * @property int $activo
 * @property int $idorganismo
 *
 * @property MdsOrgOrganismo $idorganismo0
 */
class Sds_stk_deposito extends \yii\db\ActiveRecord
{
    public $disponible;//Variable auxiliar para stock general.

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_deposito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo', 'idorganismo'], 'required'],
            [['activo', 'idorganismo', 'disponible'], 'integer'],
            [['disponible'], 'safe'],
            [['descripcion'], 'string', 'max' => 100],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddeposito' => 'Iddeposito',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'idorganismo' => 'Idorganismo',
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
}
