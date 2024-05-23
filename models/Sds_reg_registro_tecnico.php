<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_registro_tecnico".
 *
 * @property int $idregistrotecnico
 * @property int $idregistro
 * @property int $idtecnico
 * @property int|null $tipo 1: ingreso, 2: solucion, 3: despacho
 *
 * @property SdsRegRegistro $idregistro0
 * @property MdsSegUsuario $idtecnico0
 */
class Sds_reg_registro_tecnico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_registro_tecnico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistrotecnico', 'idregistro', 'idtecnico'], 'required'],
            [['idregistrotecnico', 'idregistro', 'idtecnico', 'tipo'], 'integer'],
            [['idregistrotecnico'], 'unique'],
            [['idregistro'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_reg_registro::className(), 'targetAttribute' => ['idregistro' => 'idregistro']],
            [['idtecnico'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idtecnico' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistrotecnico' => 'Idregistrotecnico',
            'idregistro' => 'Idregistro',
            'idtecnico' => 'Idtecnico',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Idregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdregistro0()
    {
        return $this->hasOne(Sds_reg_registro::className(), ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[Idtecnico0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtecnico0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idtecnico']);
    }
}
