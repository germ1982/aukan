<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_programa_requisito".
 *
 * @property int $idprogramarequisito
 * @property int $idrequisito
 * @property int $idcertificacionprograma
 * @property int $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Sds_com_configuracion $idcertificacionprograma0
 * @property Sds_com_configuracion $idrequisito0
 * @property Mds_seg_usuario $idusuarioBorra
 * @property Mds_seg_usuario $idusuarioCarga
 */
class Mds_certificacion_programa_requisito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_programa_requisito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrequisito', 'idcertificacionprograma', 'idusuario_carga', 'created_at'], 'required'],
            [['idrequisito', 'idcertificacionprograma', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idcertificacionprograma'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idcertificacionprograma' => 'idconfiguracion']],
            [['idrequisito'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idrequisito' => 'idconfiguracion']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idprogramarequisito' => 'Idprogramarequisito',
            'idrequisito' => 'Idrequisito',
            'idcertificacionprograma' => 'Idcertificacionprograma',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Idcertificacionprograma0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcertificacionprograma0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idcertificacionprograma']);
    }

    /**
     * Gets query for [[Idrequisito0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrequisito0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idrequisito']);
    }

    /**
     * Gets query for [[IdusuarioBorra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    /**
     * Gets query for [[IdusuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }
}
