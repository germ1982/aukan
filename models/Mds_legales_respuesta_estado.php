<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_respuesta_estado".
 *
 * @property int $idlegalesrespuestaestado
 * @property int $idlegalesrespuesta
 * @property int $idusuario
 * @property int $estado
 * @property string|null $observaciones
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 */
class Mds_legales_respuesta_estado extends \yii\db\ActiveRecord
{
    const ESTADO_PENDIENTE_AUTORIZACION = 1784;
    const OBSERVADA = 1785;
    const APROBADA = 1786;
    const RECHAZADA = 1787;
    const ENVIADA = 1788;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_respuesta_estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalesrespuesta', 'idusuario', 'estado'], 'required'],
            [['idlegalesrespuesta', 'idusuario', 'estado'], 'integer'],
            [['observaciones'], 'string'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesrespuestaestado' => 'Idlegalesrespuestaestado',
            'idlegalesrespuesta' => 'Idlegalesrespuesta',
            'idusuario' => 'Idusuario',
            'estado' => 'Estado',
            'observaciones' => 'Observaciones',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
        ];
    }

    public function getEstadoRespuesta()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'estado']);
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
    
    public function getRespuesta()
    {
        return $this->hasOne(Mds_legales_respuesta::class, ['idlegalesrespuesta' => 'idlegalesrespuesta']);
    }

    public function getDerivaciones()
    {
        return $this->hasMany(Mds_legales_derivacion::class, ['idlegalesoficio' => 'idlegalesoficio'])->via('respuesta');
    }
    public function getOficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idlegalesoficio' => 'idlegalesoficio'])->via('respuesta');
    }

    public function labelColorEstado($estado)
    {
        $string = "info";
        switch ($estado->estado) {
            case self::APROBADA:
                $string = 'success';
                break;
            case self::RECHAZADA:
                $string = 'danger';
                break;
            case self::ESTADO_PENDIENTE_AUTORIZACION:
                $string = 'warning';
                break;
            case self::OBSERVADA:
                $string = 'info';
                break;
        }
        return $string;
    }

    /*Se actualiza la fecha fin del ultimo estado de una respuesta*/
    static function actualizarFechaFinUltimoEstado($idRespuesta)
    {
        $ultimoEstado = Mds_legales_respuesta_estado::find()->where(['idlegalesrespuesta' => $idRespuesta])->orderBy([
            'idlegalesrespuestaestado' => SORT_DESC
        ])->one();
        $ultimoEstado->fecha_fin = date('Y-m-d H:i:s');
        $ultimoEstado->update();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta_estado', $ultimoEstado->idlegalesrespuestaestado, $ultimoEstado->getAttributes());
    }


    static function actualizarEstado($idrespuesta, $fechaIni, $fechaFin, $obs, $idnuevoEstado)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $modelRespuestaEstado = new Mds_legales_respuesta_estado();
        $modelRespuestaEstado->idlegalesrespuesta = $idrespuesta;
        $modelRespuestaEstado->idusuario = $usuarioAuth->idusuario;
        $modelRespuestaEstado->estado = $idnuevoEstado;
        $modelRespuestaEstado->fecha_inicio = $fechaIni;
        $modelRespuestaEstado->fecha_fin = $fechaFin;
        $modelRespuestaEstado->observaciones = $obs;
        $modelRespuestaEstadoSave = $modelRespuestaEstado->save();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_respuesta_estado', $modelRespuestaEstado->idlegalesrespuestaestado, $modelRespuestaEstado->getAttributes());
        return $modelRespuestaEstadoSave;
    }
}
