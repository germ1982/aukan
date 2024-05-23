<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_usuario_entrega_tipo".
 *
 * @property int $idusuarioentregatipo
 * @property int $idusuario
 * @property int $idtipo
 *
 * @property MdsSegUsuario $idusuario0
 * @property SdsEntTipo $idtipo0
 */
class Mds_seg_usuario_entrega_tipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario_entrega_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idtipo'], 'required'],
            [['idusuario', 'idtipo'], 'integer'],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ent_tipo::className(), 'targetAttribute' => ['idtipo' => 'idtipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuarioentregatipo' => 'Idusuarioentregatipo',
            'idusuario' => 'Idusuario',
            'idtipo' => 'Idtipo',
        ];
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[Idtipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipo0()
    {
        return $this->hasOne(Sds_ent_tipo::className(), ['idtipo' => 'idtipo']);
    }
}
