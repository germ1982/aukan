<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_director".
 *
 * @property int $idcertificaciondirector
 * @property int $idusuario
 * @property int $idcertificaciondireccion
 * @property string $fecha_desde
 * @property string|null $fecha_hasta
 * @property string|null $observaciones
 * @property int|null $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsCertificacionDireccion $idcertificaciondireccion0
 * @property MdsSegUsuario $idusuario0
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 * @property Sds_com_configuracion $idfuncion
 */
class Mds_certificacion_director extends \yii\db\ActiveRecord
{
    const ID_FUNCION_DIRECTOR = 6519;
    const ID_FUNCION_ADMINISTRATIVO = 6520;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_director';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idcertificaciondireccion', 'fecha_desde', 'idfuncion'], 'required'],
            [['idusuario', 'idcertificaciondireccion', 'idusuario_carga', 'idusuario_borra', 'idfuncion'], 'integer'],
            [['fecha_desde', 'fecha_hasta', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['observaciones'], 'string'],
            [['idcertificaciondireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['idcertificaciondireccion' => 'idcertificaciondireccion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idfuncion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idfuncion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificaciondirector' => 'Idcertificaciondirector',
            'idusuario' => 'Idusuario',
            'idcertificaciondireccion' => 'Idcertificaciondireccion',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'observaciones' => 'Observaciones',
            'idfuncion' => 'función que desempeña',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Idcertificaciondireccion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcertificaciondireccion0()
    {
        return $this->hasOne(Mds_certificacion_direccion::class, ['idcertificaciondireccion' => 'idcertificaciondireccion']);
    }


    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
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

    /**
     * Gets query for [[idfuncion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFuncion_usuario()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idfuncion']);
    }
}
