<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rendicion".
 *
 * @property int $idrendicion
 * 
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property string|null $observaciones
 * 
 * @property int $idusuario_carga Usuario que carga
 * @property int|null $idusuario_modifica Usuario que modifica
 * @property int|null $idusuario_borra Usuario que borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsRendicion $idrendicion
 * @property MdsSegUsuario $idusuario_carga
 * @property MdsSegUsuario $idusuario_modifica
 * @property MdsSegUsuario $idusuario_borra
 */
class Mds_rendicion_comprobante extends \yii\db\ActiveRecord
{
    const PATH = "uploads/rendicion/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rendicion_comprobante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrendicion', 'fecha_desde', 'fecha_hasta', 'idusuario_carga', 'created_at'], 'required'],
            [['idrendicion', 'idusuario_carga', 'idusuario_modifica', 'idusuario_borra'], 'integer'],
            [['observaciones', 'fecha_desde', 'fecha_hasta', 'created_at', 'updated_at', 'deleted_at'], 'string'],
            [['idusuario_carga', 'created_at'], 'safe'],

            [['idrendicion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_rendicion::class, 'targetAttribute' => ['idrendicion' => 'idrendicion']],

            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_modifica' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrendicion_comprobante' => '# Comprobante',
            'idrendicion' => '# Rendición',
            'observaciones' => 'Observaciones',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',

            'idusuario_carga' => 'Usuario Carga',
            'idusuario_modifica' => 'Usuario Modifica',
            'idusuario_borra' => 'Usuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Update At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idusuario_carga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    /**
     * Gets query for [[idusuario_modifica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioModifica()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_modifica']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    /**
     * Gets query for [[created_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCarga()
    {
        $date = date_create($this->created_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return $fecha . ' a las ' . $hora . ' hs';
    }

    /**
     * Gets query for [[fecha_desde]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaDesde()
    {
        $fecha = '';
        if (!is_null($this->fecha_desde)) {
            $date = date_create($this->fecha_desde);
            $fecha = date_format($date, 'd/m/Y');
        }
        return $fecha;
    }
    /**
     * Gets query for [[fecha_hasta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaHasta()
    {
        $fecha = '';
        if (!is_null($this->fecha_hasta)) {
            $date = date_create($this->fecha_hasta);
            $fecha = date_format($date, 'd/m/Y');
        }
        return $fecha;
    }

    public function getOtrosAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()->where(
            [
                'id_objeto' => $this->idrendicion_comprobante,
                'objeto' => 'mds_rendicion',
                'tipo' => 'registro_rendicion_comprobante',
                'activo' => true
            ]
        )->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH . $adjunto->path;
        }
        return $adjuntos;
    }
}
