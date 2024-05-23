<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_informe_usuario".
 *
 * @property int $idinformeusuario
 * @property int $idinforme
 * @property int $idusuario
 * @property int $visto
 *
 * @property Mds_org_informe $idinforme0
 * @property Mds_seg_usuario $idusuario0
 */
class Mds_org_informe_usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_informe_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idinforme', 'idusuario'], 'required'],
            [['idinforme', 'idusuario', 'visto'], 'integer'],
            [['visto_fecha'], 'safe'],
            [['idinforme'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_informe::class, 'targetAttribute' => ['idinforme' => 'idinforme']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinformeusuario' => 'Idinformeusuario',
            'idinforme' => 'Idinforme',
            'idusuario' => 'Idusuario',
            'visto' => 'Visto',
        ];
    }

    /**
     * Gets query for [[Idinforme0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdinforme0()
    {
        return $this->hasOne(Mds_org_informe::class, ['idinforme' => 'idinforme']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
}
