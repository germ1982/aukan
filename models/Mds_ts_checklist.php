<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ts_checklist".
 *
 * @property int $idtschecklist
 * @property int $idtspersona
 * @property int $idconfiguracion
 *
 * @property Mds_ts_persona $idtspersona0
 * @property Sds_com_configuracion $idconfiguracion0
 */
class Mds_ts_checklist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_ts_checklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtspersona', 'idconfiguracion'], 'required'],
            [['idtspersona', 'idconfiguracion'], 'integer'],
            [['idtspersona'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_ts_persona::className(), 'targetAttribute' => ['idtspersona' => 'idtspersona']],
            [['idconfiguracion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idconfiguracion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtschecklist' => 'Idtschecklist',
            'idtspersona' => 'Idtspersona',
            'idconfiguracion' => 'Idconfiguracion',
        ];
    }

    /**
     * Gets query for [[Idtspersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtspersona0()
    {
        return $this->hasOne(Mds_ts_persona::className(), ['idtspersona' => 'idtspersona']);
    }

    /**
     * Gets query for [[Idconfiguracion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdconfiguracion0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idconfiguracion']);
    }
}
