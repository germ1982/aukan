<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_respuesta_visto".
 *
 * @property int $idlegalesrespuestavisto
 * @property int $idusuario
 * @property int $idlegalesrespuesta
 * @property int $idlegalesoficio
 * @property string|null $fecha_carga
 * @property int|null $activo
 * @property string|null $auditoria
 */
class Mds_legales_respuesta_visto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_respuesta_visto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idlegalesrespuesta', 'idlegalesoficio'], 'required'],
            [['idusuario', 'idlegalesrespuesta', 'idlegalesoficio', 'activo'], 'integer'],
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
            'idlegalesrespuestavisto' => '#',
            'idusuario' => '# Usuario',
            'idlegalesrespuesta' => '# Respuesta',
            'idlegalesoficio' => '# Requerimiento',
            'activo' => 'Activo',
            'fecha_carga' => 'Fecha Carga',
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getRespuesta()
    {
        return $this->hasOne(Mds_legales_respuesta::class, ['idlegalesrespuesta' => 'idlegalesrespuesta']);
    }

    public function getOficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }

}
