<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_usuario_capa_item".
 *
 * @property int $idusuariocapaitem
 * @property int $idusuario
 * @property int $idcapaitem
 *
 * @property SdsGisCapaItem $idcapaitem0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_seg_usuario_capa_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario_capa_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idcapaitem'], 'required'],
            [['idusuario', 'idcapaitem'], 'integer'],
            [['idcapaitem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::className(), 'targetAttribute' => ['idcapaitem' => 'idcapaitem']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuariocapaitem' => 'Idusuariocapaitem',
            'idusuario' => 'Usuario',
            'idcapaitem' => 'Edificio',
        ];
    }

}
