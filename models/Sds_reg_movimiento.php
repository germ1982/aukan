<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_movimiento".
 *
 * @property int $idmovimiento
 * @property int $idregistro
 * @property string $fecha
 * @property string $descripcion
 * @property int $idusuario
 * @property int $idtecnico Quien carga el movimiento
 * @property int $tipo 0: Común, 1: Ingreso Equipo, 2: Solución
 *
 * @property SdsRegRegistro $idregistro0
 * @property MdsSegUsuario $idusuario0
 * @property MdsSegUsuario $idusuario1
 */
class Sds_reg_movimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro', 'fecha', 'descripcion', 'idusuario', 'idtecnico', 'tipo'], 'required'],
            [['idregistro', 'idusuario', 'idtecnico', 'tipo'], 'integer'],
            [['fecha'], 'safe'],
            [['descripcion'], 'string'],
            [['idregistro'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_reg_registro::className(), 'targetAttribute' => ['idregistro' => 'idregistro']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Idmovimiento',
            'idregistro' => 'Idregistro',
            'fecha' => 'Fecha',
            'descripcion' => 'Descripcion',
            'idusuario' => 'Idusuario',
            'idtecnico' => 'Idtecnico',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Idregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdregistro0()
    {
        return $this->hasOne(SdsRegRegistro::className(), ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(MdsSegUsuario::className(), ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[Idusuario1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario1()
    {
        return $this->hasOne(MdsSegUsuario::className(), ['idusuario' => 'idusuario']);
    }
}
