<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_visto".
 *
 * @property int $idcertificacionvisto
 * @property int $idusuario
 * @property int $idcertificacion
 * @property string|null $fecha_carga
 * @property int|null $activo
 * @property string|null $auditoria
 */
class Mds_certificacion_visto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_visto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idcertificacion', 'fecha_carga'], 'required'],
            [['idusuario', 'idcertificacion', 'activo'], 'integer'],
            [['auditoria'], 'string'],
            [['fecha_carga'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionvisto' => '#',
            'idusuario' => '# Usuario',
            'idcertificacion' => '# Certificación',
            'activo' => 'Activo',
            'fecha_carga' => 'Fecha Carga',
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getCertificacion()
    {
        return $this->hasOne(Mds_certificacion::class, ['idcertificacion' => 'idcertificacion']);
    }

}
