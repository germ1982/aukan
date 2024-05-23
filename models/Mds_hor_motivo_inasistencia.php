<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_motivo_inasistencia".
 *
 * @property int $idmotivoinasistencia
 * @property string $descripcion
 * @property string $idrh
 * @property int $activo
 *
 * @property MdsHorAsistencia[] $mdsHorAsistencias
 * @property MdsHorAsistenciaExcepcion[] $mdsHorAsistenciaExcepcions
 * @property MdsHorLicencia[] $mdsHorLicencias
 */
class Mds_hor_motivo_inasistencia extends \yii\db\ActiveRecord
{
    const SIN_GOCE_HABERES=35;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_motivo_inasistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idrh', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['idrh'], 'string', 'max' => 45],
            [['idrh'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmotivoinasistencia' => 'Idmotivoinasistencia',
            'descripcion' => 'Descripcion',
            'idrh' => 'Idrh',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[MdsHorAsistencias]].
     *
     * @return \yii\db\ActiveQuery
     */
/*     public function getMdsHorAsistencias()
    {
        return $this->hasMany(Mds_hor_asistencia::className(), ['idmotivoinasistencia' => 'idmotivoinasistencia']);
    }*/

    /**
     * Gets query for [[MdsHorAsistenciaExcepcions]].
     *
     * @return \yii\db\ActiveQuery
     */
    /*public function getMdsHorAsistenciaExcepcions()
    {
        return $this->hasMany(Mds_hor_asistencia_excepcion::className(), ['idmotivoinasistencia' => 'idmotivoinasistencia']);
    } */

    /**
     * Gets query for [[MdsHorLicencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsHorLicencias()
    {
        return $this->hasMany(Mds_hor_licencia::className(), ['idmotivoinasistencia' => 'idmotivoinasistencia']);
    }
}
