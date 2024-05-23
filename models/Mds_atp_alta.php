<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_atp_alta".
 *
 * @property int $idalta
 * @property string $fechahora
 * @property string $path
 * @property int $idusuario
 * @property int $estado 0: generado - 1: aceptado - 2: rechazado
 * @property string|null $observaciones
 *
 * @property MdsSegUsuario $idusuario0
 */
class Mds_atp_alta extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $temp_path;
    const GENERADO=0;
    const ACEPTADO=1;
    const RECHAZADO=2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_atp_alta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechahora', 'path', 'idusuario', 'idsucursal'], 'required'],
            [['fechahora'], 'safe'],
            [['idusuario', 'estado', 'idsucursal'], 'integer'],
            [['observaciones'], 'string'],
            [['path'], 'string', 'max' => 255],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idsucursal'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_atp_sucursal::className(), 'targetAttribute' => ['idsucursal' => 'idsucursal']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idalta' => 'Id',
            'fechahora' => 'Fecha y hora',
            'path' => 'Path',
            'idusuario' => 'Usuario',
            'estado' => 'Estado',
            'observaciones' => 'Observaciones',
            'idsucursal' => 'Sucursal',
        ];
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }
}
