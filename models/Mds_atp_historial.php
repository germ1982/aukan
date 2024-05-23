<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_atp_historial".
 *
 * @property int $id_atp_historial
 * @property int $id_atp_solicitud
 * @property string|null $descripcion
 * @property string|null $fecha_hora
 * @property int|null $estado
 */
class Mds_atp_historial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $estado_aux_anterior;
    public $estado_aux;
    public $cad_estado;
    public $fecha_reg;
    public $hora_reg;
    public static function tableName()
    {
        return 'mds_atp_historial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_atp_solicitud', 'estado_nuevo', 'estado_anterior'], 'integer'],
            [['descripcion'], 'string'],
            [['fecha_hora'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_atp_historial' => 'Id Atp Historial',
            'id_atp_solicitud' => 'Id Atp Solicitud',
            'descripcion' => 'Descripcion',
            'fecha_hora' => 'Fecha Hora',
            'estado' => 'Estado',
            'estado_aux' => 'Estado Actual del Registro ATPCen',
        ];
    }
}
