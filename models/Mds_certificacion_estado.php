<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_estado".
 *
 * @property int $idcertificacionestado
 * @property int $idcertificacion
 * @property int $idusuario
 * @property int $idestado
 * @property string|null $observaciones
 * @property string|null $fecha
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 */
class Mds_certificacion_estado extends \yii\db\ActiveRecord
{
    const ESTADO_PENDIENTE = 4020;
    const ESTADO_OBSERVADA = 4021;
    const ESTADO_APROBADA = 4022;
    const ESTADO_RECHAZADA =  4023;
    const ESTADO_ENVIADA = 4024;
    const ESTADO_BAJA = 4359;
    const ESTADO_ELIMINADA = 5569;
    const LISTADO_ESTADOS = [
        'ESTADO_PENDIENTE' => 4020,
        'ESTADO_OBSERVADA' => 4021,
        'ESTADO_APROBADA' => 4022,
        'ESTADO_RECHAZADA' =>  4023,
        'ESTADO_ENVIADA' => 4024,
        'ESTADO_BAJA' => 4359,
        'ESTADO_ELIMINADA' => 5569,
    ];
    public $idbeneficiario;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacion', 'idusuario', 'idestado'], 'required'],
            [['idcertificacion', 'idusuario', 'idestado', 'iddireccion'], 'integer'],
            [['observaciones', 'fecha'], 'string'],
            [['fecha_inicio', 'fecha_fin', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idcertificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion::class, 'targetAttribute' => ['idcertificacion' => 'idcertificacion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['iddireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['iddireccion' => 'idcertificaciondireccion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionestado' => 'Idcertificacionestado ',
            'idcertificacion' => 'Idcertificacion',
            'idusuario' => 'Idusuario',
            'iddireccion' => 'Iddireccion',
            'idestado' => 'Estado',
            'observaciones' => 'Observaciones',
            'fecha' => 'Fecha de baja',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin'
        ];
    }
    public function getEstado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idestado']);
    }
    public function getDireccion()
    {
        return $this->hasOne(Mds_certificacion_direccion::class, ['idcertificaciondireccion' => 'iddireccion']);
    }
    public function getCertificacion()
    {
        return $this->hasOne(Mds_certificacion::class, ['idcertificacion' => 'idcertificacion']);
    }
    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
    public function labelColorEstado($estado)
    {
        $string = "info";
        switch ($estado->estado) {
            case self::ESTADO_APROBADA:
                $string = 'success';
                break;
            case self::ESTADO_RECHAZADA:
                $string = 'danger';
                break;
            case self::ESTADO_PENDIENTE:
                $string = 'warning';
                break;
            case self::ESTADO_OBSERVADA:
                $string = 'info';
                break;
        }
        return $string;
    }

    /*Se actualiza la fecha fin del ultimo estado de una respuesta*/
    static function actualizarFechaFinUltimoEstado($idRespuesta)
    {
        $ultimoEstado = Mds_certificacion_estado::find()->where(['idcertificacion' => $idRespuesta])->orderBy([
            'idcertificacionestado' => SORT_DESC
        ])->one();
        $ultimoEstado->fecha_fin = date('Y-m-d h:i:s');
        $ultimoEstado->update();
    }

    static function actualizarEstado($idrespuesta, $fechaIni, $fechaFin, $obs, $idnuevoEstado)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $modelRespuestaEstado = new Mds_certificacion_estado();
        $modelRespuestaEstado->idcertificacion = $idrespuesta;
        $modelRespuestaEstado->idusuario = $usuarioAuth->idusuario;
        $modelRespuestaEstado->idestado = $idnuevoEstado;
        $modelRespuestaEstado->fecha_inicio = $fechaIni;
        $modelRespuestaEstado->fecha_fin = $fechaFin;
        $modelRespuestaEstado->observaciones = $obs;
        return $modelRespuestaEstado->save();
    }

    static function getEstadoactual($idcertificacion)
    {
        $model_certificacion_estado = Mds_certificacion_estado::find()
            ->select([
                'UPPER(CONCAT(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido)) as usuario',
                'DATE_FORMAT(mds_certificacion_estado.created_at,"%d/%m/%Y") as created_at',
                'DATE_FORMAT(mds_certificacion_estado.fecha,"%d/%m/%Y") as fecha',
                'sds_com_configuracion.descripcion estado',
                'mds_certificacion_estado.idestado',
                'mds_certificacion_estado.observaciones',
                'mds_certificacion_estado.iddireccion',
                'mds_certificacion_estado.idusuario',
                'mds_certificacion_estado.idcertificacionestado'
            ])
            ->where(['mds_certificacion_estado.idcertificacion' => $idcertificacion, 'fecha_fin' => null, 'deleted_at' => null])
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_estado.idusuario')
            ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = mds_certificacion_estado.idestado')
            //->orderBy(['mds_certificacion_estado.created_at' => SORT_DESC])
            ->asArray()
            ->one();
        return $model_certificacion_estado;
    }

    public function getEstadosHistorial($listado, $idcertificacion)
    {
        $connection = Yii::$app->getDb();
        $estados = $connection->createCommand(
            "SELECT estado.idcertificacionestado,
                    estado.idestado,
                    estado.iddireccion,
                    estado.observaciones,
                    estado.created_at,
                    estado.deleted_at,
                    DATE_FORMAT(estado.fecha_inicio,'%d/%m/%Y %H:%ihs') as fecha_inicio,
                    DATE_FORMAT(estado.fecha_fin,'%d/%m/%Y %H:%ihs') as fecha_fin,
                    DATE_FORMAT(estado.fecha,'%d/%m/%Y') as fecha,
                    configuracion.descripcion estadoDescripcion,
                    UPPER(CONCAT(usuario.apellido, ' ',usuario.nombre)) usuarioNombre
            FROM mds_certificacion_estado estado
            INNER JOIN mds_certificacion certificacion ON estado.idcertificacion = certificacion.idcertificacion
            INNER JOIN sds_com_configuracion configuracion ON estado.idestado = configuracion.idconfiguracion
            LEFT JOIN sds_com_configuracion configuracion_estado ON estado.iddireccion = configuracion_estado.idconfiguracion
            INNER JOIN mds_seg_usuario usuario ON estado.idusuario = usuario.idusuario
            WHERE estado.idcertificacion = '$idcertificacion'
            AND estado.deleted_at IS NULL
            ORDER BY estado.created_at DESC
            "
        )->queryAll();

        $reversed = array_reverse($estados);

        $leng = count($reversed);
        foreach ($reversed as $key => $estado) {
            $consulta =
                "SELECT configuracion_direccion.descripcion direccionDescripcion,
                configuracion_nivel.descripcion nivelDescripcion,
                direccion.idnivelautorizacion
                FROM mds_certificacion_direccion direccion
                LEFT JOIN sds_com_configuracion configuracion_direccion ON direccion.iddireccion = configuracion_direccion.idconfiguracion
                LEFT JOIN sds_com_configuracion configuracion_nivel ON direccion.idnivelautorizacion = configuracion_nivel.idconfiguracion
                WHERE deleted_at IS NULL";

            if ((($leng - 1) >= ($key + 1)) && $reversed[$key + 1]['idestado'] != Mds_certificacion_estado::ESTADO_BAJA) {
                if (($leng - 1) > $key && $estado['iddireccion'] && ($estado['iddireccion'] != $reversed[$key + 1]['iddireccion'] || $estado['idestado'] != $reversed[$key + 1]['idestado'])) {
                    $iddireccion = $estado['iddireccion'];
                    $where = " AND direccion.idcertificaciondireccion = '$iddireccion'";
                    $consulta = $consulta . $where;
                    $direccion = Mds_certificacion_direccion::findBySql($consulta)->asArray()->one();
                    $reversed[$key + 1] = $reversed[$key + 1] + $direccion;
                }
            } else {
                if ($reversed[$key]['idestado'] == Mds_certificacion_estado::ESTADO_BAJA) {
                    $iddireccion = $estado['iddireccion'];
                    $where =  $estado['iddireccion'] ?
                        " AND direccion.idcertificaciondireccion = '$iddireccion'"
                        : "";
                    $consulta = $consulta . $where;
                    if ($where != "") {
                        $direccion = Mds_certificacion_direccion::findBySql($consulta)->asArray()->one();
                        $reversed[$key] = $reversed[$key] + $direccion;
                    }
                }
            }
        }

        $estados = array_reverse($reversed);

        $listado['estados'] = $estados;
        return $listado;
    }

    public function getDirecciones()
    {
        $direccion =  Mds_certificacion_estado::find()
            ->select('sds_com_configuracion.*')
            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_estado.iddireccion')
            ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = mds_certificacion_direccion.iddireccion')
            ->where(['mds_certificacion_estado.idcertificacionestado' => $this->idcertificacionestado])
            ->asArray()
            ->one();
        return $direccion ? $direccion['descripcion'] : '';
    }
}
