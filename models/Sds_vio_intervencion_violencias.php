<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_intervencion_violencias".
 *
 * @property int $idviolenciatiporespuesta
 * @property int|null $idintervencion
 * @property int|null $idviolenciatipo
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at

 */
class Sds_vio_intervencion_violencias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_intervencion_violencias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idviolenciatipo'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idviolenciatipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idviolenciatipo' => 'idconfiguracion']],
            [['idintervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_intervencion::class, 'targetAttribute' => ['idintervencion' => 'idintervencion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idviolenciatiporespuesta' => 'Idviolenciatiporespuesta',
            'idintervencion' => 'Idintervencion',
            'idviolenciatipo' => 'idviolenciatipo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[idviolenciatipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getidviolenciatipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idviolenciatipo']);
    }

    /**
     * Gets query for [[Idintervencion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdintervencion()
    {
        return $this->hasOne(Sds_vio_intervencion::class, ['idintervencion' => 'idintervencion']);
    }


    public static function getConfiguracionesViolencias()
    {
        return Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA])
            ->orWhere(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA])
            ->orWhere(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA])
            ->orWhere(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL])
            ->orWhere(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA])
            ->orWhere(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL])
            ->andWhere(['activo' => 1])
            ->orderBy(['idconfiguracion' => SORT_ASC])
            ->all();
    }

    public static function getViolenciaDescripcion($idintervencion)
    {
        return Sds_vio_intervencion_violencias::find()
            ->select('sds_com_configuracion.descripcion,sds_vio_intervencion_violencias.idintervencion,sds_com_configuracion_tipo.idconfiguraciontipo')
            ->innerJoin('sds_com_configuracion', 'sds_vio_intervencion_violencias.idviolenciatipo = sds_com_configuracion.idconfiguracion')
            ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo = sds_com_configuracion.idconfiguraciontipo')
            ->where(['idintervencion' => $idintervencion])
            ->andWhere(['sds_com_configuracion.activo' => 1])
            ->andWhere(['sds_vio_intervencion_violencias.deleted_at' => null])
            ->orderBy(['sds_com_configuracion.idconfiguracion' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getViolenciaByTipoIntervencion($idintervencion, $idtipo)
    {
        return Sds_vio_intervencion_violencias::find()
            ->select('sds_com_configuracion.descripcion,sds_vio_intervencion_violencias.idintervencion,sds_com_configuracion_tipo.idconfiguraciontipo')
            ->innerJoin('sds_com_configuracion', 'sds_vio_intervencion_violencias.idviolenciatipo = sds_com_configuracion.idconfiguracion')
            ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo = sds_com_configuracion.idconfiguraciontipo')
            ->where(['idintervencion' => $idintervencion])
            ->andWhere(['sds_com_configuracion.idconfiguraciontipo' => $idtipo])
            ->andWhere(['sds_com_configuracion.activo' => 1])
            ->andWhere(['sds_vio_intervencion_violencias.deleted_at' => null])
            ->orderBy(['sds_com_configuracion.idconfiguracion' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getTipoViolenciaByIdConfiguracion($idconfiguracion, $idtipoconfiguracion)
    {
        $arrayTipo = Sds_com_configuracion::find()
            ->select('sds_com_configuracion.descripcion,sds_com_configuracion_tipo.idconfiguraciontipo')
            ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo = sds_com_configuracion.idconfiguraciontipo')
            ->where(['sds_com_configuracion.idconfiguraciontipo' => $idtipoconfiguracion])
            ->andWhere(['sds_com_configuracion.idconfiguracion' => $idconfiguracion])
            ->andWhere(['sds_com_configuracion.activo' => 1])
            ->orderBy(['sds_com_configuracion.idconfiguracion' => SORT_ASC])
            ->asArray()
            ->all();
        return $arrayTipo;
    }
}
