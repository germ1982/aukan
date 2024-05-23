<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_atp_monto".
 *
 * @property int $idmonto
 * @property string $fechahora
 * @property string $path
 * @property int $idusuario
 * @property int $estado 0: generado - 1: aceptado - 2: rechazado
 * @property string|null $observaciones
 *
 * @property Mds_seg_usuario $idusuario0
 */
class Mds_atp_monto extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $temp_path;
    public $monto;
    const GENERADO=0;
    const ACEPTADO=1;
    const RECHAZADO=2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_atp_monto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechahora', 'path', 'idusuario'], 'required'],
            [['fechahora', 'monto'], 'safe'],
            [['idusuario', 'estado'], 'integer'],
            [['observaciones'], 'string'],
            [['path'], 'string', 'max' => 255],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmonto' => 'Id',
            'fechahora' => 'Fecha y hora',
            'path' => 'Path',
            'idusuario' => 'Usuario',
            'estado' => 'Estado',
            'observaciones' => 'Observaciones',
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
