<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_tecnico_incidencia".
 *
 * @property int $idincidencia
 * @property int|null $idregistro
 * @property int|null $idinventario
 * @property int $idingresante
 * @property int $iddispositivo
 * @property int $idestado
 * @property int|null $idretira
 *
 * @property RegistroTecnico $idregistro0
 * @property RegistroTecnicoIncidenciaMovimiento[] $registroTecnicoIncidenciaMovimientos
 */
class RegistroTecnicoIncidencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $fecha_ingreso;
    public $asistencia;
    public static function tableName()
    {
        return 'registro_tecnico_incidencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro', 'idinventario', 'idingresante','iddispositivo', 'idestado', 'idretira'], 'integer'],
            [['idingresante', 'iddispositivo', 'idestado'], 'required'],
            [['fecha_ingreso','asistencia'], 'safe'],
            [['idregistro'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroTecnico::className(), 'targetAttribute' => ['idregistro' => 'idregistro']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idincidencia' => 'Idincidencia',
            'idregistro' => 'Idregistro',
            'idinventario' => 'Idinventario',
            'idingresante' => 'Idingresante',
            'iddispositivo' => 'Iddispositivo',
            'idestado' => 'Idestado',
            'idretira' => 'Idretira',
        ];
    }

    /**
     * Gets query for [[Idregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdregistro0()
    {
        return $this->hasOne(RegistroTecnico::className(), ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[RegistroTecnicoIncidenciaMovimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroTecnicoIncidenciaMovimientos()
    {
        return $this->hasMany(RegistroTecnicoIncidenciaMovimiento::className(), ['idincidencia' => 'idincidencia']);
    }
}
