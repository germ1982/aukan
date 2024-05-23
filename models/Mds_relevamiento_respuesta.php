<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_relevamiento_respuesta".
 *
 * @property int $idrelevamientorespuesta
 * @property int|null $idrelevamientoregistro
 * @property int|null $iditem
 * @property int|null $posee
 * @property string|null $detalle
 * @property int|null $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComConfiguracion $iditem0
 * @property MdsRelevamientoRegistro $idrelevamientoregistro0
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 */
class Mds_relevamiento_respuesta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_relevamiento_respuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrelevamientoregistro', 'iditem', 'posee', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['detalle'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['iditem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['iditem' => 'idconfiguracion']],
            [['idrelevamientoregistro'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_relevamiento_registro::class, 'targetAttribute' => ['idrelevamientoregistro' => 'idrelevamientoregistro']],
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
            'idrelevamientorespuesta' => 'Idrelevamientorespuesta',
            'idrelevamientoregistro' => 'Idrelevamientoregistro',
            'iditem' => 'Iditem',
            'posee' => 'Posee',
            'detalle' => 'Detalle',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Iditem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIditem0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iditem']);
    }

    /**
     * Gets query for [[Idrelevamientoregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrelevamientoregistro0()
    {
        return $this->hasOne(Mds_relevamiento_registro::class, ['idrelevamientoregistro' => 'idrelevamientoregistro']);
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
