<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_direccion_usuario".
 *
 * @property int $iddireccionusuario
 * @property int $idcertificaciondireccion
 * @property int $idusuario
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsCertificacionDireccion $idcertificaciondireccion0
 * @property MdsSegUsuario $idusuario0
 *
 */
class Mds_certificacion_direccion_usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_direccion_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idcertificaciondireccion'], 'required'],
            [['idusuario', 'idcertificaciondireccion'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],

            [['idcertificaciondireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['idcertificaciondireccion' => 'idcertificaciondireccion']],            
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddireccionusuario' => '#',
            'idcertificaciondireccion' => 'Dirección',
            'idusuario' => 'Usuario',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo'
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

}
