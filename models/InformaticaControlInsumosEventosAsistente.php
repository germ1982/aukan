<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_control_insumos_eventos_asistente".
 *
 * @property int $identrega
 * @property int $idtecnico
 */
class InformaticaControlInsumosEventosAsistente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informatica_control_insumos_eventos_asistente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identrega', 'idtecnico'], 'required'],
            [['identrega', 'idtecnico'], 'integer'],
            [['identrega'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identrega' => 'Identrega',
            'idtecnico' => 'Idtecnico',
        ];
    }
}
