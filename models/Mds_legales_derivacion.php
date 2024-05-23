<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_derivacion".
 *
 * @property int $idlegalesderivacion
 * @property int $idlegalesoficio
 * @property int $idusuario
 * @property int $idusuario_deriva
 * @property int|null $supervisor
 * @property int $re_derivado
 * @property string $fecha_derivacion
 * @property string $observaciones
 * @property boolean $activo
 */
class Mds_legales_derivacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_derivacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalesoficio', 'idusuario', 'fecha_derivacion'], 'required'],
            [['observaciones'], 'string'],
            [['idlegalesoficio', 'idusuario', 'idusuario_deriva', 'supervisor', 're_derivado', 'activo'], 'integer'],
            [['fecha_derivacion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesderivacion' => 'Idlegalesderivacion',
            'idlegalesoficio' => 'Idlegalesoficio',
            'idusuario' => 'Idusuario',
            'idusuario_deriva' => 'Idusuario deriva',
            'supervisor' => 'Supervisor',
            'fecha_derivacion' => 'Fecha Derivacion',
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getUsuarioDeriva()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_deriva']);
    }

    public function getOficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }

    public static function getSupervisoresByRequerimiento($id) {
        return Mds_legales_derivacion::find()
        ->select("idusuario")
        ->where("fecha_usu_no_corresponde IS NULL AND activo = 1 AND supervisor = 1 AND idlegalesoficio = $id")
        ->asArray()
        ->all();
    }

    public static function verificarDerivacionExistenteByIdOficioAndIdUsuarioAndRol($idOficio, $idUsuario, $rolUsuario = null) {
        $where = "fecha_usu_no_corresponde IS NULL AND activo = 1 AND idlegalesoficio = $idOficio AND idusuario = $idUsuario";
        if ($rolUsuario === 'SUPERVISOR') {
            $where .= " AND supervisor = 1";
        } else if ($rolUsuario === 'GENERADOR_RESPUESTAS') {
            $where .= " AND supervisor = 0";
        }

        return Mds_legales_derivacion::find()
        ->select("idlegalesoficio")
        ->where($where)
        ->asArray()
        ->all();
    }
}
