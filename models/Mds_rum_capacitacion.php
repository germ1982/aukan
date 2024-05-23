<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_capacitacion".
 *
 * @property int $id
 * @property string $nombrecap
 * @property string $lugarcapacitacion
 * @property int $certificada
 * @property string $fechacapacitacion
 * @property string $organizador
 * @property int $idlocalidad
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $descripcion
 * @property int $idpersona
 * @property int $orden
 * @property int lugarpais
 * @property int descripcionpais
 */
class Mds_rum_capacitacion extends \yii\db\ActiveRecord
{   public $estadocertificacion;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_capacitacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nombrecap', 'lugarcapacitacion', 'certificada', 'fechacapacitacion', 'organizador', 'idlocalidad', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'descripcion', 'idpersona', 'orden'], 'required'],
            [['lugarpais','id', 'certificada', 'idlocalidad', 'idpersona', 'orden'], 'integer'],
            [['descripcionpais','nombrecap', 'descripcion'], 'string'],
            [['fechacapacitacion', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion'], 'safe'],
            [['lugarcapacitacion', 'organizador'], 'string', 'max' => 254],
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
            'nombrecap' => 'Nombrecap',
            'lugarcapacitacion' => 'Lugarcapacitacion',
            'certificada' => 'Certificada',
            'fechacapacitacion' => 'Fechacapacitacion',
            'organizador' => 'Organizador',
            'idlocalidad' => 'Idlocalidad',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'descripcion' => 'Descripcion',
            'idpersona' => 'Idpersona',
            'orden' => 'Orden',
            'lugarpais' => 'lugarpais',
            'descripcionpais' => 'descripcionpais',            
        ];
    }
}
