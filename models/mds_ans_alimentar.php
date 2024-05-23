<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ans_alimentar".
 *
 * @property int $id
 * @property string|null $municipio
 * @property string|null $nombre
 * @property string|null $estado
 * @property string|null $cuil
 * @property int $dni
 * @property string|null $fecha
 */
class mds_ans_alimentar extends \yii\db\ActiveRecord
{
 const PENDIENTE='Pendiente';
 const ENTREGADA= 'Entregada';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_ans_alimentar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni'], 'required'],
            [['dni'], 'integer'],
            [['fecha'], 'safe'],
            [['municipio', 'nombre', 'estado'], 'string', 'max' => 150],
            [['cuil'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'municipio' => 'Municipio',
            'nombre' => 'Apellido y Nombre',
            'estado' => 'Estado',
            'cuil' => 'Cuil',
            'dni' => 'Dni',
            'fecha' => 'Fecha de Entrega',
        ];
    }
}
