<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_r_ejidos".
 *
 * @property int $idejido
 * @property string|null $ejido
 * @property int|null $id_departamento
 * @property string|null $departamento
 * @property int $idlocalidad
 *
 * @property Mds_r_diagnostico[] $Mds_r_diagnosticos
 * @property Sds_com_localidad $idlocalidad0
 */
class Mds_r_ejidos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_r_ejidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idejido', 'idlocalidad'], 'required'],
            [['idejido', 'id_departamento', 'idlocalidad'], 'integer'],
            [['ejido', 'departamento'], 'string', 'max' => 150],
            [['idejido'], 'unique'],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idejido' => 'Idejido',
            'ejido' => 'Ejido',
            'id_departamento' => 'Id Departamento',
            'departamento' => 'Departamento',
            'idlocalidad' => 'Idlocalidad',
        ];
    }

    /**
     * Gets query for [[Mds_r_diagnosticos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMds_r_diagnosticos()
    {
        return $this->hasMany(Mds_r_diagnostico::className(), ['idejido' => 'idejido']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::className(), ['idlocalidad' => 'idlocalidad']);
    }
}
