<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_formacionacademica".
 *
 * @property int $id
 * @property int $idpersona
 * @property string $observacion
 * @property string $nombre_instituto
 * @property int $culmino
 * @property string $tiempocursado
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $nivel
 * @property string $tipodelnivel
 * @property string $detalle
 * @property int $orden
 */
class Mds_rum_formacionacademica extends \yii\db\ActiveRecord
{
    public $estadoculmino;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_formacionacademica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idpersona', 'observacion', 'nombre_instituto', 'culmino', 'tiempocursado', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'nivel', 'tipodelnivel', 'detalle', 'orden'], 'required'],
            [['id', 'idpersona', 'culmino', 'orden'], 'integer'],
            [['observacion'], 'string'],
            [['fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion'], 'safe'],
            [['nombre_instituto', 'detalle'], 'string', 'max' => 254],
            [['tiempocursado'], 'string', 'max' => 80],
            [['nivel', 'tipodelnivel'], 'string', 'max' => 200],
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
            'idpersona' => 'Idpersona',
            'observacion' => 'Observacion',
            'nombre_instituto' => 'Nombre Instituto',
            'culmino' => 'Culmino',
            'tiempocursado' => 'Tiempocursado',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'nivel' => 'Nivel',
            'tipodelnivel' => 'Tipodelnivel',
            'detalle' => 'Detalle',
            'orden' => 'Orden',
        ];
    }
}
