<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_programa_monto".
 *
 * @property int $idcertificacionprogramamonto
 * @property int $idcertificacionprograma
 * @property string $monto
 * @property string $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsCertificacionPrograma $idcertificacionprograma
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 */
class Mds_certificacion_programa_monto extends \yii\db\ActiveRecord
{

    public $iddireccion;
    public $idprograma;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_programa_monto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacionprograma', 'fecha_inicio', 'created_at', 'idusuario_carga'], 'required'],
            [['idcertificacionprogramamonto', 'idcertificacionprograma', 'monto', 'idusuario_carga', 'idusuario_borra'], 'integer'],

            [['fecha_inicio', 'fecha_fin', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            
            [['idcertificacionprograma'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_programa::class, 'targetAttribute' => ['idcertificacionprograma' => 'idcertificacionprograma']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionprogramamonto' => '#',
            'monto' => 'Monto',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'iddireccion' => 'Dirección',
            'idprograma' => 'Programa',
        ];
    }


    /**
     * Gets query for [[idcertificacionprograma]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificacionPrograma()
    {
        return $this->hasOne(Mds_certificacion_programa::class, ['idcertificacionprograma' => 'idcertificacionprograma']);
    }

    /**
     * Gets query for [[iddireccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddireccion']);
    }

    /**
     * Gets query for [[idprograma]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idprograma']);
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
