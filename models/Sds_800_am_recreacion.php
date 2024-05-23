<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_am_recreacion".
 *
 * @property int $idamrecreacion
 * @property int $idatencionam
 * @property int $recreacion
 *
 * @property Sds800AtencionAm $idatencionam0
 * @property SdsComConfiguracion $recreacion0
 */
class Sds_800_am_recreacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_am_recreacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idatencionam', 'recreacion'], 'required'],
            [['idatencionam', 'recreacion'], 'integer'],
            [['idatencionam'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_atencion_am::class, 'targetAttribute' => ['idatencionam' => 'idllamada']],
            [['recreacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['recreacion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idamrecreacion' => 'Idamrecreacion',
            'idatencionam' => 'Idatencionam',
            'recreacion' => 'Recreacion',
        ];
    }

    /**
     * Gets query for [[Idatencionam0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdatencionam0()
    {
        return $this->hasOne(Sds800AtencionAm::class, ['idllamada' => 'idatencionam']);
    }

    /**
     * Gets query for [[Recreacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecreacion0()
    {
        return $this->hasOne(SdsComConfiguracion::class, ['idconfiguracion' => 'recreacion']);
    }
}
