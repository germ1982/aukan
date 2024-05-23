<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_conocgenerales".
 *
 * @property int $id
 * @property int $iddetalle
 * @property string $descripcion
 * @property int $idnivelcg
 * @property int $idpersona
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property int $orden
 */
class Mds_rum_conocgenerales extends \yii\db\ActiveRecord
{   public $detalle_cg;
    public $nivel_cg;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_conocgenerales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iddetalle', 'descripcion', 'idnivelcg', 'idpersona', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'orden'], 'required'],
            [['id', 'iddetalle', 'idnivelcg', 'idpersona', 'orden'], 'integer'],
            [['descripcion'], 'string'],
            [['fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion'], 'safe'],
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
            'iddetalle' => 'Iddetalle',
            'descripcion' => 'Descripcion',
            'idnivelcg' => 'Idnivelcg',
            'idpersona' => 'Idpersona',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'orden' => 'Orden',
        ];
    }
}
