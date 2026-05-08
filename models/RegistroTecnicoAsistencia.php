<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_tecnico_asistencia".
 *
 * @property int $idregistro
 * @property int $idtecnico
 */
class RegistroTecnicoAsistencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_tecnico_asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro', 'idtecnico'], 'required'],
            [['idregistro', 'idtecnico'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'idtecnico' => 'Idtecnico',
        ];
    }
}
