<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_impuganacion_motivo".
 *
 * @property int $idconcimpugnacionmotivo
 * @property int $idhistorial
 * @property int $idmotivo
 * @property int $idusuario_carga Usuario que carga
 * @property int $idusuario_borra Usuario que borra * 
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */
class Mds_conc_impugnacion_motivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_impugnacion_motivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idhistorial', 'idmotivo', 'idusuario_carga', 'created_at'], 'required'],
            [['idconcimpugnacionmotivo', 'idhistorial', 'idmotivo', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idconcimpugnacionmotivo' => '#',
            'idhistorial' => '# Historial',
            'idmotivo' => '# Motivo',
            'idusuario_carga' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
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
}
