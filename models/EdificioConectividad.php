<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificio_conectividad".
 *
 * @property int $idconectividad
 * @property int $idedificio
 * @property int $infraestructura
 * @property int $servicio
 * @property int|null $velocidad_en_mb
 * @property int|null $estado
 * @property string|null $observacion
 * @property int|null $tipo_conexion
 */
class EdificioConectividad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $edificio_descripcion; // Atributo virtual para mostrar la descripción del edificio

    public static function tableName()
    {
        return 'edificio_conectividad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idedificio', 'infraestructura', 'servicio'], 'required'],
            [['idedificio', 'infraestructura', 'servicio', 'velocidad_en_mb', 'estado', 'tipo_conexion'], 'integer'],
            [['observacion'], 'string'],
            [['edificio_descripcion'], 'safe'], // Asegura que el atributo virtual sea seguro para asignación masiva

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idconectividad' => 'Idconectividad',
            'idedificio' => 'Idedificio',
            'infraestructura' => 'Infraestructura',
            'servicio' => 'Servicio',
            'velocidad_en_mb' => 'Velocidad En Mb',
            'estado' => 'Estado',
            'observacion' => 'Observacion',
            'tipo_conexion' => 'Tipo Conexion',
        ];
    }
}
