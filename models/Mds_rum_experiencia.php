<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_experiencia".
 *
 * @property int $id
 * @property string $puesto
 * @property string $entidad
 * @property string $periodo
 * @property string $descripcion
 * @property int $idpersona
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property int $idlocalidad
 * @property int $orden
 * @property int $lugarpaisexp
 * @property int $descripcionpaisexp
 */
class Mds_rum_experiencia extends \yii\db\ActiveRecord
{

    public $una_localidad;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_experiencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'puesto', 'entidad', 'periodo', 'descripcion', 'idpersona', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'idlocalidad', 'orden'], 'required'],
            [['lugarpaisexp','id', 'idpersona', 'idlocalidad', 'orden'], 'integer'],
            [['descripcionpaisexp','puesto', 'descripcion'], 'string'],
            [['fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion'], 'safe'],
            [['entidad'], 'string', 'max' => 254],
            [['periodo'], 'string', 'max' => 100],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'puesto' => 'Puesto',
            'entidad' => 'Entidad',
            'periodo' => 'Periodo',
            'descripcion' => 'Descripcion',
            'idpersona' => 'Idpersona',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'idlocalidad' => 'Idlocalidad',
            'orden' => 'Orden',
        ];
    }
}
