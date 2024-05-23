<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_tar_tarjeta".
 *
 * @property int $idtarjeta
 * @property int $dni
 * @property int $referente
 * @property int $idusuario
 * @property string $numero
 * @property string|null $observaciones
 * @property string $fecha
 *
 * @property SdsComConfiguracion $referente0
 * @property MdsSegUsuario $idusuario0
 */
class Sds_tar_tarjeta extends \yii\db\ActiveRecord
{
    public $estado; //Agrego estado para indicar si es pendiente o rendida

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_tar_tarjeta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referente', 'idusuario', 'numero', 'fecha'], 'required'],
            [['dni', 'referente', 'idusuario', 'empresa'], 'integer'],
            [['observaciones'], 'string'],
            [['fecha', 'dni','estado'], 'safe'],
            [['numero'], 'string', 'max' => 45],
            [['referente'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['referente' => 'idconfiguracion']],
            [['empresa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['empresa' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtarjeta' => 'Idtarjeta',
            'dni' => 'Dni',
            'referente' => 'Referente',
            'empresa' => 'Empresa',
            'idusuario' => 'Usuario',
            'numero' => 'Numero',
            'observaciones' => 'Observaciones',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * Gets query for [[Referente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferente()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'referente']);
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'empresa']);
    }

    /**
     * Gets query for [[Idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }
}
