<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_programa_adjunto".
 *
 * @property int $idprogramaadjunto
 * @property int $idcertificacionprograma
 * @property int $idadjunto
 * @property int $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $deleted_at

 */
class Mds_certificacion_programa_adjunto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_programa_adjunto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacionprograma', 'idadjunto', 'created_at', 'idusuario_carga'], 'required'],
            [['idprogramaadjunto', 'idcertificacionprograma', 'idadjunto', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['idcertificacionprograma', 'idadjunto','created_at', 'deleted_at', 'idusuario_carga', 'idusuario_borra'], 'safe'],
            [['idcertificacionprograma'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_programa::class, 'targetAttribute' => ['idcertificacionprograma' => 'idcertificacionprograma']],
            [['idadjunto'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idadjunto' => 'idconfiguracion']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idprogramaadjunto' => 'Idprogramaadjunto',
            'idcertificacionprograma' => 'Idcertificacionprograma',
            'idadjunto' => 'Idadjunto',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[idcertificacionprograma]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcertificacionprograma()
    {
        return $this->hasOne(Mds_certificacion_programa::class, ['idcertificacionprograma' => 'idcertificacionprograma']);
    }


    /**
     * Gets query for [[idadjunto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdadjunto()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idadjunto']);
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

    /**
     * Gets query for [[IdusuarioBorra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }
}
