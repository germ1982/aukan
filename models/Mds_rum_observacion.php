<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_observacion".
 *
 * @property int $idobservacion
 * @property string|null $observacion
 * @property string|null $fecha
 * @property string|null $hora
 * @property int|null $id_cv
 * @property int|null $id_persona
 */
class Mds_rum_observacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_observacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['observacion'], 'string'],
            [['fecha', 'hora','autor'], 'safe'],
            [['id_cv', 'id_persona'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idobservacion' => 'Idobservacion',
            'observacion' => 'Observación',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'id_cv' => 'Id Cv',
            'id_persona' => 'Id Persona',
        ];
    }
}
