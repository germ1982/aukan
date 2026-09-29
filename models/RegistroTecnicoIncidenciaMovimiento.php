<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_tecnico_incidencia_movimiento".
 *
 * @property int $idmovimiento
 * @property int $idincidencia
 * @property string $fecha
 * @property string $hora
 * @property int $idmovimiento_nombre
 * @property string|null $descripcion
 *
 * @property RegistroTecnicoIncidencia $idincidencia0
 */
class RegistroTecnicoIncidenciaMovimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_tecnico_incidencia_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idincidencia', 'fecha', 'hora', 'idmovimiento_nombre'], 'required'],
            [['idincidencia', 'idmovimiento_nombre'], 'integer'],
            [['fecha', 'hora'], 'safe'],
            [['descripcion'], 'string'],
            [['idincidencia'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroTecnicoIncidencia::className(), 'targetAttribute' => ['idincidencia' => 'idincidencia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Idmovimiento',
            'idincidencia' => 'Idincidencia',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'idmovimiento_nombre' => 'Idmovimiento Nombre',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[Idincidencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdincidencia0()
    {
        return $this->hasOne(RegistroTecnicoIncidencia::className(), ['idincidencia' => 'idincidencia']);
    }
}
