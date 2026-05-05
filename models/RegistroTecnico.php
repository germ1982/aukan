<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_tecnico".
 *
 * @property int $idregistro
 * @property string $fecha_solicitud
 * @property int $idsolicitante
 * @property int|null $iddispositivo
 * @property int|null $idtipo_registro
 * @property string|null $problema
 * @property string|null $solucion
 * @property string|null $fecha_solucion
 */
class RegistroTecnico extends \yii\db\ActiveRecord
{
    public $asistentes_informaticos;
    public static function tableName()
    {
        return 'registro_tecnico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_solicitud', 'idsolicitante'], 'required'],
            [['fecha_solicitud', 'fecha_solucion', 'asistentes_informaticos'], 'safe'],
            [['idsolicitante', 'iddispositivo', 'idtipo_registro'], 'integer'],
            [['problema', 'solucion'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'fecha_solicitud' => 'Fecha Solicitud',
            'idsolicitante' => 'Idsolicitante',
            'iddispositivo' => 'Iddispositivo',
            'idtipo_registro' => 'Idtipo Registro',
            'problema' => 'Problema',
            'solucion' => 'Solucion',
            'fecha_solucion' => 'Fecha Solucion',
            'asistentes_informaticos' => 'Asistencia',
        ];
    }
}
