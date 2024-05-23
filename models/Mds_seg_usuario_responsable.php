<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_seg_usuario_responsable".
 *
 * @property int $idusuarioresponsable
 * @property int $idusuario
 * @property int $idresponsable
 *
 * @property MdsSegUsuario $idusuario0
 * @property SdsComConfiguracion $idresponsable0
 */
class Mds_seg_usuario_responsable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_seg_usuario_responsable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idresponsable'], 'required'],
            [['idusuario', 'idresponsable'], 'integer'],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idresponsable'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idresponsable' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuarioresponsable' => 'Idusuarioresponsable',
            'idusuario' => 'Idusuario',
            'idresponsable' => 'Idresponsable',
        ];
    }

}
