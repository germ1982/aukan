<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_reg_contrasenia".
 *
 * @property int $idcontrasenia
 * @property string $fecha_carga
 * @property int $idorganismo corresponde al organismo stock del usuario logueado
 * @property int $tipo
 * @property string $ip
 * @property string $descripcion
 * @property string $usuario
 * @property string $contrasenia
 * @property string $ubicacion
 * @property string|null $observaciones
 *
 * @property Mds_org_organismo $idorganismo0
 * @property Sds_com_configuracion $tipo0
 */
class Mds_reg_contrasenia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_reg_contrasenia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_carga', 'idorganismo', 'tipo', 'ip', 'descripcion', 'usuario', 'contrasenia', 'ubicacion'], 'required'],
            [['fecha_carga'], 'safe'],
            [['idorganismo', 'tipo'], 'integer'],
            [['observaciones'], 'string'],
            [['ip'], 'string', 'max' => 15],
            [['descripcion', 'contrasenia', 'ubicacion'], 'string', 'max' => 255],
            [['usuario'], 'string', 'max' => 50],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcontrasenia' => 'IDContraseña',
            'fecha_carga' => 'Fecha Carga',
            'idorganismo' => 'Organismo',
            'tipo' => 'Tipo',
            'ip' => 'IP',
            'descripcion' => 'Descripción',
            'usuario' => 'Usuario',
            'contrasenia' => 'Contraseña',
            'ubicacion' => 'Ubicación',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }
}
