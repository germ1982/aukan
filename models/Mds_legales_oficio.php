<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_oficio".
 *
 * @property int $idlegalesoficio
 * @property int $idemisor
 * @property string|null $lugar_libramiento
 * @property string|null $fecha_libramiento
 * @property string|null $fecha_oficio
 * @property string|null $fecha_plazo
 * @property string|null $fecha_recepcion
 * @property string|null $doctor_a_cargo
 * @property string|null $caratula
 * @property string|null $remitente
 * @property string|null $tiempo_respuesta
 * @property string|null $dni_legajo_vinculado
 * @property string|null $numero_expediente
 * @property int|null $anio_expediente
 * @property string|null $caso
 * @property string|null $donde_tramita
 * @property string|null $motivo_solicitud
 * @property string|null $providencia
 * @property string|null $area
 * @property int|null $idarea
 * @property string|null $juicio
 * @property int|null $primer_oficio
 * @property int $tipo_oficio
 * @property string|null $firma_oficio
 * @property string|null $archivo_oficio
 * @property string|null archivo_oficio_nombre
 * @property int $idusuario
 * @property string $fecha_carga
 * @property string|null $observaciones
 * @property string|null $tramite_simple
 * @property boolean $activo
 * @property string|null $auditoria
 * @property int|null $idlegalescaratula
 *
 */
class Mds_legales_oficio extends \yii\db\ActiveRecord
{
    /* 
    Usuarios:
    DIRECCIÓN PROVINCIAL DE FAMILIA: 666
    DIRECCIÓN PROVINCIAL DE PREVENCIÓN Y ASISTENCIA DE LAS VIOLENCIAS: 77
    DIRECCIÓN PROVINCIAL DE NIÑEZ Y ADOLESCENCIA: 632
    DIRECCIÓN PROVINCIAL DE POLÍTICAS PARA PERSONAS MAYORES: 173
    DIRECCIÓN PROVINCIAL DE ARTICULACIÓN TÉCNICA TERRITORIAL: 121
    DIRECCIÓN GENERAL DE ADMISIÓN: 1119
    DIRECCIÓN GENERAL DE FAMILIA: 148
    DIRECCIÓN GENERAL DE DISCAPACIDAD Y SISTEMA DE PROTECCIÓN DE DERECHOS: 595
    DIRECCIÓN GENERAL DE POLÍTICAS SOCIALES A PERSONAS MAYORES: 174
    DIRECCIÓN GENERAL DE POLÍTICAS SOCIALES A PERSONAS MAYORES: 209
    */
    const ID_ROL_REGISTRO = 80;
    const ID_ROL_RECEPTOR = 81;
    const ID_ROL_SUPERVISOR = 82;
    const ID_ROL_VINCULACION = 83;
    const ID_ROL_ADMIN_GENERAL = 84;
    const ID_ROL_SUPERVISOR_AREA = 100;
    const ID_ROL_SUPERVISOR_GENERAL = 101;
    const ID_ROLES_LEGALES = [Mds_legales_oficio::ID_ROL_REGISTRO, Mds_legales_oficio::ID_ROL_RECEPTOR, Mds_legales_oficio::ID_ROL_SUPERVISOR, Mds_legales_oficio::ID_ROL_VINCULACION, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL, Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA, Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL];
    const ID_USUARIO_PROV_FAMILIA = 666;
    const ID_USUARIO_PROV_VIOLENCIA = 77;
    const ID_USUARIO_PROV_NINIEZ = 8089;
    const ID_USUARIO_PROV_ADULTOS_MAYORES = 173;
    const ID_USUARIO_PROV_INTERIOR = 148;
    const ID_USUARIO_PROV_LEGAL_TECNICA = 158;
    const ID_USUARIO_GRAL_ADMISION = 121;
    const ID_USUARIO_GRAL_FAMILIA = 1119;
    const ID_USUARIO_GRAL_DISCAPACIDAD = 595;
    const ID_USUARIO_GRAL_ADULTOS_MAYORES = 174;
    const ID_AREA_PROV_FAMILIA = 4412;
    const ID_AREA_PROV_VIOLENCIA = 4419;
    const ID_AREA_PROV_NINIEZ = 4413;
    const ID_AREA_PROV_ADULTOS_MAYORES = 4415;
    const ID_AREA_PROV_INTERIOR = 4416;
    const ID_AREA_PROV_LEGAL_TECNICA = 4414;
    const ID_CONFIGURACION_NOTIFICACION_ELECTRONICA = 2373;
    const ID_CONFIGURACION_NOTIFICACION_ELECTRONICA_URGENTE = 2377;
    const PATH = "uploads/legales/oficios/";
    const RUNNEU_API_MODULO = "LEGALES";
    const RUNNEU_API_TIPO_REQUERIMIENTO = 'requerimiento';
    const RUNNEU_API_TIPO_RESPUESTA = 'respuesta';
    public $temp_archivo_oficio;
    public $estado;
    public $supervisores;
    public $generadoresRespuesta;
    public $respuestasEnviadas;
    public $respuestasGeneradas;
    public $respuestaPendienteVistos;
    public $nuevaObservacion;
    public $fechaAprobado;
    public $listaPersonasVinculadas;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_oficio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idemisor', 'tipo_oficio', 'idusuario', 'fecha_carga', 'idarea', 'fecha_plazo', 'fecha_recepcion', 'fecha_oficio', 'caratula'], 'required'],
            [['idemisor', 'anio_expediente', 'primer_oficio', 'tipo_oficio', 'idusuario', 'sugerencia_idusuario', 'idarea', 'idusuario_borra', 'idlegalescaratula'], 'integer'],
            [['providencia', 'area', 'juicio', 'observaciones', 'tramite_simple', 'tiempo_respuesta', 'sugerencia', 'dni_legajo_vinculado', 'auditoria', 'caratula'], 'string'],
            [['fecha_carga', 'supervisores', 'generadoresRespuesta', 'respuestasGeneradas', 'respuestasEnviadas', 'respuestaPendienteVistos', 'nuevaObservacion', 'deleted_at', 'fechaAprobado'], 'safe'],
            [['temp_archivo_oficio'], 'file', 'extensions' => 'jpg,jpeg,gif,png,pdf', 'maxSize' => 1000000],
            [['lugar_libramiento', 'fecha_libramiento', 'fecha_recepcion', 'fecha_oficio', 'fecha_plazo', 'sugerencia_fecha', 'doctor_a_cargo', 'remitente', 'numero_expediente', 'caso', 'donde_tramita', 'motivo_solicitud', 'firma_oficio', 'archivo_oficio', 'archivo_oficio_nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesoficio' => '#',
            'idemisor' => 'Emisor órgano superior',
            'lugar_libramiento' => 'Entidad Requirente',
            'fecha_libramiento' => 'Fecha Libramiento',
            'caratula' => 'Carátula',
            'remitente' => 'Remitente',
            'tiempo_respuesta' => 'Tiempo Respuesta',
            'dni_legajo_vinculado' => 'DNI de personas vinculadas',
            'numero_expediente' => 'Número Expediente',
            'anio_expediente' => 'Año',
            'caso' => 'Caso',
            'donde_tramita' => 'Donde Tramita',
            'motivo_solicitud' => 'Motivo Solicitud',
            'providencia' => 'Providencia',
            'primer_oficio' => 'Primer Requerimiento',
            'tipo_oficio' => 'Tipo de requerimiento',
            'firma_oficio' => 'Firma Oficio',
            'archivo_oficio' => 'Archivo Oficio',
            'idusuario' => 'Usuario',
            'fecha_carga' => 'Fecha Carga',
            'area' => 'Área',
            'juicio' => 'Juicio',
            'activo' => 'Activo',
            'observaciones' => 'Observaciones',
            'tramite_simple' => 'Número Tramite Simple',
            'temp_archivo_oficio' => 'Seleccionar un Archivo (imagen o PDF)',
            'sugerencia' => 'Observaciones / instrucciones',
            'fecha_recepcion' => 'Fecha recepción',
            'fecha_oficio' => 'Fecha requerimiento',
            'fecha_plazo' => 'Fecha vencimiento',
            'auditoria' => 'Auditoria',
            'fecha_inicio' => 'Fecha Aprobado',
            'idarea' => 'Derivación a: '
        ];
    }
    static function random_filename($length, $directory, $extension)
    {
        // default to this files directory if empty...
        $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

        $key = '';
        do {
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
        } while (file_exists($dir . '/' . $key . (!empty($extension) ? '.' . $extension : '')));

        return $key . (!empty($extension) ? '.' . $extension : '');
    }

    public function getAdjuntosByTipo($tipo, $idObjeto = null, $objeto = null)
    {
        $path = self::PATH;

        if ($tipo != 'oficio' && $tipo != 'otros' && $tipo != 'sugerencia') {
            $path = "uploads/legales/$tipo/";
        }

        if (!$idObjeto) {
            $idObjeto = $this->idlegalesoficio;
        }

        if (!$objeto) {
            $objeto = 'mds_legales_oficio';
        }

        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => $objeto, 'tipo' => $tipo, 'activo' => true])
            ->andWhere(['=', 'id_objeto', $idObjeto])->all();

        foreach ($adjuntos as $adjunto) {
            $adjunto->path = $path . $adjunto->path;
        }

        return $adjuntos;
    }

    public function getEmisor()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idemisor']);
    }
    public function getTipoOficio()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_oficio']);
    }
    public function getAreaOficio()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idarea']);
    }
    public function getReceptores()
    {
        return Mds_legales_derivacion::find()->where(['idlegalesoficio' => $this->idlegalesoficio, 'supervisor' => 0, 'activo' => 1, 'fecha_usu_no_corresponde' => NULL])->all();
    }
    public function getSupervisores()
    {
        return Mds_legales_derivacion::find()->where(['idlegalesoficio' => $this->idlegalesoficio, 'supervisor' => 1, 'activo' => 1, 'fecha_usu_no_corresponde' => NULL])->all();
    }
    public function getPersonasVinculadas()
    {
        return Mds_legales_oficio_vinculado::find()->where("idlegalesoficio = {$this->idlegalesoficio} AND deleted_at IS NULL")->all();
    }
    public function getRespuestas()
    {
        return $this->hasMany(Mds_legales_respuesta::class, ['idlegalesoficio' => 'idlegalesoficio'])->orderBy(['idlegalesrespuesta' => SORT_DESC])->all();
    }
    public function getRespuestas0()
    {
        return $this->hasMany(Mds_legales_respuesta::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }
    public function getRespuestasEstado()
    {
        return $this->hasMany(Mds_legales_respuesta_estado::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->via('respuestas0');
    }
    public function getLastRespuesta()
    {
        return $this->hasOne(Mds_legales_respuesta::class, ['idlegalesoficio' => 'idlegalesoficio'])->orderBy(['idlegalesrespuesta' => SORT_DESC]);
    }
    public function getRespuestasAprobadas()
    {
        $estados = Mds_legales_respuesta_estado::find()
            ->rightJoin('mds_legales_respuesta', 'mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta')
            ->rightJoin('mds_legales_oficio', 'mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->where(['mds_legales_respuesta_estado.estado' => Mds_legales_respuesta_estado::APROBADA])
            ->andWhere(['mds_legales_oficio.idlegalesoficio' => $this->idlegalesoficio])
            ->all();
        $idrespuestas = [];
        foreach ($estados as $estado) {
            $idrespuestas[] = $estado->idlegalesrespuesta;
        }

        $respuestas = Mds_legales_respuesta::find()->where(['in', 'idlegalesrespuesta', $idrespuestas])->orderBy(['idlegalesrespuesta' => SORT_DESC])->all();
        return $respuestas;
    }
    public function getRespuestaPendienteVistos()
    {
        $estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $respuestaPendienteVistos = Mds_legales_oficio::find()
            ->select('idlegalesrespuestavisto')
            ->innerJoin('mds_legales_respuesta', 'mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta')
            ->innerJoin('mds_legales_respuesta_visto', 'mds_legales_respuesta_visto.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta')
            ->where("mds_legales_respuesta_estado.estado = $estadoPendiente 
                    AND mds_legales_respuesta_estado.fecha_fin IS NULL
                    AND mds_legales_oficio.idlegalesoficio = {$this->idlegalesoficio}
                    AND mds_legales_oficio.activo = 1
                    AND mds_legales_respuesta_visto.activo = 1
                    ")
            ->asArray()->all();

        return $respuestaPendienteVistos;
    }

    public function getTotalRespuestaPendienteVistos()
    {
        $estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $connection = Yii::$app->getDb();
        $respuestasGeneradas = $connection->createCommand("SELECT COUNT(*) as respuesta_pendiente_vistos 
                                                            FROM mds_legales_oficio 
                                                            INNER JOIN mds_legales_respuesta ON mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio
                                                            INNER JOIN mds_legales_respuesta_estado ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                                                            INNER JOIN mds_legales_respuesta_visto ON mds_legales_respuesta_visto.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                                                            WHERE mds_legales_respuesta_estado.estado = $estadoPendiente 
                                                            AND mds_legales_respuesta_estado.fecha_fin IS NULL
                                                            AND mds_legales_oficio.idlegalesoficio = {$this->idlegalesoficio}
                                                            AND mds_legales_oficio.activo = 1
                                                            AND mds_legales_respuesta_visto.activo = 1")->queryAll();
        return $respuestasGeneradas[0]['respuesta_pendiente_vistos'];
    }
    /**
     * Buscamos las respuestas de un requerimiento en el cuál, la haya generado algun receptor que fuera derivado por mi usuario, y su estado actual sea pendiente
     */
    public function getRespuestasPendientesMiSupervision()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $estados = Mds_legales_respuesta_estado::find()
            ->innerJoin('mds_legales_respuesta', 'mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta')
            ->innerJoin('mds_legales_oficio', 'mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->innerJoin('mds_legales_derivacion', 'mds_legales_derivacion.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->where(['mds_legales_oficio.idlegalesoficio' => $this->idlegalesoficio])
            ->andWhere(['mds_legales_oficio.activo' => 1])
            ->andWhere(['mds_legales_derivacion.activo' => 1])
            ->andWhere(['mds_legales_derivacion.supervisor' => 0])
            ->andWhere(['mds_legales_derivacion.idusuario_deriva' => $usuarioAuth->idusuario])
            ->orderBy(['mds_legales_respuesta_estado.idlegalesrespuesta' => SORT_ASC])
            ->all();
        $lastRespuestasEstadoPendientes = array();
        $estadoAnterior = null;
        foreach ($estados as $index => $estado) {
            if ($estadoAnterior && ($estadoAnterior->idlegalesrespuesta != $estado->idlegalesrespuesta) && ($estadoAnterior->estado == Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION)) {
                //Si al pasar al siguiente estado, el idlegalesrespuesta es distinto al estado actual, entonces es porque el anterior era el ultimo estado de esa respuesta, y ademas me interesa si son pendientes
                array_push($lastRespuestasEstadoPendientes, $estadoAnterior);
            } else if (($index == (count($estados) - 1)) && ($estado->estado == Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION)) {
                //Es el ultimo elemento, siempre va a ser el ultimo estado de esa respuesta
                array_push($lastRespuestasEstadoPendientes, $estado);
            }
            $estadoAnterior = $estado;
        }
        return $lastRespuestasEstadoPendientes;
    }

    public function getLastRespuestasEstadoByEstado($estado)
    {
        /*
        Se utiliza para saber si el estado de la ultima respuesta generada es igual al estado enviado por parametro. (esto se hace porque anteriormente se podian enviar mas de una respuesta a la vez)
        Si el estado que entra por parametro es:
            id observada: evalua si es observada o rechazada (Se utiliza para la notificacion de oficios sin responder del usuario generador de respuestas)
            id rechazada: evalua si es rechazada (Se utiliza para devolver de usuario generador de respuestas)
            id aprobada: evalua si es aprobada o enviada (Se utiliza para botones del index mds_legales_oficio)
            'aprobado': evalua si es aprobado (Se utiliza para 'fecha aprobado' en respuestas para enviar de vinculacion)
        */
        $usuarioAuth = Yii::$app->user->identity;
        $idEstadoAprobada = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoEnviada = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $idEstadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $where = "mds_legales_oficio.idlegalesoficio = {$this->idlegalesoficio} AND mds_legales_oficio.activo = 1";
        if ($estado === $idEstadoObservada) {
            // Esto se hace porque cuando la respuesta esta observada, ya sale una notificacion de que debe corregir esa respuesta, por lo tanto nos interesa las respuestas que no son del usuario logueado
            $where .= " AND NOT mds_legales_respuesta.idusuario = {$usuarioAuth->idusuario}";
        } else if ($estado === $idEstadoRechazado) {
            // Se utiliza para saber si alguna de las respuestas generadas por el usuario logueado estan rechazadas
            $where .= " AND mds_legales_respuesta.idusuario = {$usuarioAuth->idusuario}";
        }
        $respuestasEstado = Mds_legales_respuesta_estado::find()
            ->innerJoin('mds_legales_respuesta', 'mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta')
            ->innerJoin('mds_legales_oficio', 'mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->where($where)
            ->orderBy(['mds_legales_respuesta_estado.idlegalesrespuesta' => SORT_ASC])
            ->all();
        $lastRespuestasEstado = array();
        $estadoAnterior = null;
        $condicionEstadoAnterior = false;
        foreach ($respuestasEstado as $index => $respuestaEstado) {
            switch ($estado) {
                case $idEstadoAprobada:
                    $condicionEstado  = $respuestaEstado->estado === $idEstadoAprobada || $respuestaEstado->estado === $idEstadoEnviada;
                    $condicionEstadoAnterior = $estadoAnterior ? $estadoAnterior->estado === $idEstadoAprobada || $estadoAnterior->estado === $idEstadoEnviada : false;
                    break;
                case $idEstadoObservada:
                    $condicionEstado  = $respuestaEstado->estado === $idEstadoObservada || $respuestaEstado->estado === $idEstadoRechazado;
                    $condicionEstadoAnterior  = $estadoAnterior ? $estadoAnterior->estado === $idEstadoObservada || $estadoAnterior->estado === $idEstadoRechazado : false;
                    break;
                case $idEstadoRechazado:
                    $condicionEstado = $respuestaEstado->estado === $idEstadoRechazado;
                    $condicionEstadoAnterior = $estadoAnterior ? $estadoAnterior->estado === $idEstadoRechazado : false;
                    break;
                case $idEstadoPendiente:
                    $condicionEstado = $respuestaEstado->estado === $idEstadoPendiente;
                    $condicionEstadoAnterior = $estadoAnterior ? $estadoAnterior->estado === $idEstadoPendiente : false;
                    break;
                case 'APROBADO':
                    $condicionEstado  = $respuestaEstado->estado === $idEstadoAprobada;
                    $condicionEstadoAnterior = $estadoAnterior ? $estadoAnterior->estado === $idEstadoAprobada : false;
                    break;
                default:
                    $condicionEstado  = false;
                    $condicionEstadoAnterior = false;
                    break;
            }

            if ($estadoAnterior && ($estadoAnterior->idlegalesrespuesta !== $respuestaEstado->idlegalesrespuesta) && ($condicionEstadoAnterior)) {
                //Si al pasar al siguiente estado, el idlegalesrespuesta es distinto al estado actual, entonces es porque el anterior era el ultimo estado de esa respuesta, y nos quedamos con las que tengan el estado que entra como parametro
                array_push($lastRespuestasEstado, $estadoAnterior);
            } else if (($index == count($respuestasEstado) - 1) && $condicionEstado) {
                //Si es el ultimo elemento del arreglo siempre va a ser el ultimo estado de esa respuesta, nos fijamos si esta en el estado que entra como parametro
                array_push($lastRespuestasEstado, $respuestaEstado);
            }

            $estadoAnterior = $respuestaEstado;
        }
        return $lastRespuestasEstado;
    }

    public function getRespuestasAprobadasSinOtrosEstados()
    {
        $idEstadoAprobada = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoEnviada = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $respuestas = Mds_legales_respuesta::find()
            ->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta')
            ->where(['idlegalesoficio' => $this->idlegalesoficio, 'estado' => $idEstadoAprobada])
            ->andWhere("mds_legales_respuesta_estado.idlegalesrespuesta NOT IN (select e.idlegalesrespuesta from mds_legales_respuesta_estado e where e.estado IN ({$idEstadoEnviada},{$idEstadoObservada},{$idEstadoRechazado}))")
            ->orderBy(['mds_legales_respuesta_estado.idlegalesrespuestaestado' => SORT_DESC])
            ->all();

        return $respuestas;
    }
    public static function getRoles()
    {
        //rol supervior es idrol = 64;
        if (Yii::$app->user && Yii::$app->user->identity) {
            $usuarioAuth = Yii::$app->user->identity;
            $roles = Mds_seg_usuario_rol::find()->where(['idusuario' => $usuarioAuth->idusuario])->all();
        } else {
            $roles = [];
        }
        return $roles;
    }
    public static function tieneRol($idRol)
    {
        $roles = self::getRoles();
        $existe = false;
        $columna = array_column($roles, 'idrol');
        if (in_array($idRol, $columna)) {
            $existe = true;
        }

        return $existe;
    }
    public function getTotalRespuestasEnviadas()
    {
        $connection = Yii::$app->getDb();
        $respuestasEnviadas = $connection->createCommand("SELECT COUNT(*) as respuestas_enviadas FROM mds_legales_respuesta  WHERE entregado IS NOT NULL AND idlegalesoficio={$this->idlegalesoficio}")->queryAll();
        return $respuestasEnviadas[0]['respuestas_enviadas'];
    }
    public function getTotalRespuestasGeneradas()
    {
        $connection = Yii::$app->getDb();
        $respuestasGeneradas = $connection->createCommand("SELECT COUNT(*) as respuestas_generadas FROM mds_legales_respuesta WHERE idlegalesoficio={$this->idlegalesoficio}")->queryAll();
        return $respuestasGeneradas[0]['respuestas_generadas'];
    }

    public function getTotalRespuestasGeneradasByIdOficio($idOficio)
    {
        $connection = Yii::$app->getDb();
        $respuestasGeneradasByIdOficio = $connection->createCommand("SELECT COUNT(*) as respuestas_generadas FROM mds_legales_respuesta WHERE idlegalesoficio=$idOficio")->queryAll();
        return $respuestasGeneradasByIdOficio[0]['respuestas_generadas'];
    }

    public function getTotalRequerimientosByIdEstado($idEstado, $fechaInicio = null, $fechaFin = null)
    {
        $where = "mds_legales_oficio.activo = 1 AND mds_legales_respuesta_estado.estado = $idEstado";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }

        $connection = Yii::$app->getDb();
        $requerimientos = $connection->createCommand(
            "SELECT DISTINCT mds_legales_oficio.idlegalesoficio 
            FROM mds_legales_oficio
            INNER JOIN mds_legales_respuesta ON  mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado ON  mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
            WHERE $where
            "
        )->queryAll();

        return count($requerimientos);
    }

    public function getTotalRequerimientosPendientesSupervision($fechaInicio = null, $fechaFin = null)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $idEstadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $connection = Yii::$app->getDb();

        $where = "mds_legales_oficio.activo = 1 AND mds_legales_respuesta_estado.estado = $idEstadoPendiente";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' ";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        }
        $pendientesSupervisionFinal = $connection->createCommand(
            "SELECT DISTINCT mds_legales_oficio.idlegalesoficio 
            FROM mds_legales_oficio
            INNER JOIN mds_legales_respuesta ON  mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado ON  mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
            WHERE $where
            AND mds_legales_respuesta_estado.idlegalesrespuesta NOT IN 
            (SELECT mds_legales_respuesta_estado.idlegalesrespuesta 
            FROM mds_legales_respuesta_estado
            WHERE mds_legales_respuesta_estado.estado IN ($idEstadoAprobado, $idEstadoEnviado, $idEstadoRechazado, $idEstadoObservada)
            )
            "
        )->queryAll();

        return count($pendientesSupervisionFinal);
    }

    public function getTotalRequerimientosPendientesSupervisionFinal($fechaInicio = null, $fechaFin = null)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $connection = Yii::$app->getDb();

        $where = "mds_legales_oficio.activo = 1 AND mds_legales_respuesta_estado.estado = $idEstadoAprobado";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' ";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        }
        $pendientesSupervisionFinal = $connection->createCommand(
            "SELECT DISTINCT mds_legales_oficio.idlegalesoficio 
            FROM mds_legales_oficio
            INNER JOIN mds_legales_respuesta ON  mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado ON  mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
            WHERE $where
            AND mds_legales_respuesta_estado.idlegalesrespuesta NOT IN 
            (SELECT mds_legales_respuesta_estado.idlegalesrespuesta 
            FROM mds_legales_respuesta_estado
            WHERE mds_legales_respuesta_estado.estado IN ($idEstadoEnviado, $idEstadoRechazado, $idEstadoObservada)
            )
            "
        )->queryAll();

        return count($pendientesSupervisionFinal);
    }

    public function getTotalRequerimientosDevueltosSupervisionFinal($fechaInicio = null, $fechaFin = null)
    {
        return count($this->getRequerimientosDevueltosSupervisionFinal($fechaInicio, $fechaFin));
    }

    public function getRequerimientosDevueltosSupervisionFinal($fechaInicio = null, $fechaFin = null)
    {
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $connection = Yii::$app->getDb();

        $where = "mds_legales_oficio.activo = 1 AND mds_legales_respuesta_estado.estado = $idEstadoRechazado";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' ";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin' ";
        }
        $devueltasSupervisionFinal = $connection->createCommand(
            "SELECT DISTINCT mds_legales_oficio.idlegalesoficio 
            FROM mds_legales_oficio
            INNER JOIN mds_legales_respuesta ON  mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado ON  mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
            WHERE $where
            "
        )->queryAll();

        $devueltasSupervisionFinalFiltradas = array();
        foreach ($devueltasSupervisionFinal as $oficioDevuelto) {
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $oficioDevuelto['idlegalesoficio']])->one();
            if (count($oficio->getLastRespuestasEstadoByEstado($idEstadoAprobado)) === 0) {
                array_push($devueltasSupervisionFinalFiltradas, $oficioDevuelto);
            }
        }

        return $devueltasSupervisionFinalFiltradas;
    }

    public function getRequerimientosConDerivacionByFecha($fechaInicio = null, $fechaFin = null)
    {
        $connection = Yii::$app->getDb();

        $where = "mds_legales_oficio.activo = 1";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }

        $requerimientos = $connection->createCommand(
            "SELECT mds_legales_oficio.idlegalesoficio, mds_legales_oficio.idarea, mds_legales_derivacion.idusuario,
             mds_legales_derivacion.supervisor, mds_legales_derivacion.activo, 
             mds_legales_derivacion.fecha_usu_no_corresponde, mds_legales_derivacion.re_derivado
            FROM mds_legales_oficio
            LEFT JOIN mds_legales_derivacion ON mds_legales_derivacion.idlegalesoficio = mds_legales_oficio.idlegalesoficio
            WHERE $where
            ORDER BY mds_legales_oficio.idlegalesoficio ASC
            "
        )->queryAll();

        return $requerimientos;
    }

    public static function getNotificaciones($calledFrom)
    {
        $notifications = [
            'notificaciones' => [],
            'total' => 0
        ];

        $roleFunctions = [
            self::ID_ROL_REGISTRO => [
                'oficiosParaReDerivarASupervisor' => 'getOficiosParaReDerivarASupervisor'
            ],
            self::ID_ROL_RECEPTOR => [
                'oficiosSinRespuestas' => 'getOficiosSinRespuesta',
                'respuestasObservadas' => 'getRespuestasObservadas'
            ],
            self::ID_ROL_SUPERVISOR => [
                'respuestasSinSupervisar' => 'getRespuestasSinSupervisar',
                'oficiosSinDerivarAUsuarios' => 'getOficiosSinDerivarAUsuarios',
                'respuestasRechazadas' => 'getRespuestasRechazadas',
                'oficiosParaReDerivar' => 'getOficiosParaReDerivar',
                'oficiosConRespuestasVistas' => 'getOficiosConRespuestasVistas'
            ],
            self::ID_ROL_VINCULACION => [
                'oficiosRespuestasAprobadasNoEnviadas' => 'getOficiosRespuestasAprobadasNoEnviadas'
            ]
        ];

        foreach ($roleFunctions as $roleId => $notificationFunctions) {
            $hasRole = Mds_legales_oficio::tieneRol($roleId);

            if ($hasRole) {
                $notifications = self::setNotificacionesArray($notifications, $calledFrom, $notificationFunctions);
            }

            if ($roleId === self::ID_ROL_RECEPTOR) {
                $hasRolReceptor = $hasRole;
            } else if ($roleId === self::ID_ROL_SUPERVISOR) {
                $hasRolSupervisor = $hasRole;
            }
        }
        /**
        if ($hasRolReceptor || $hasRolSupervisor) {
            $notifications = self::getNotificacionRequerimientosConObservacionFinal($notifications, $hasRolReceptor, $hasRolSupervisor);
        }
         */
        $notifications = self::setNotificacionesArray($notifications, $calledFrom, ['vencimientoPlazoOficios' => 'getVencimientoPlazoOficios']);
        $notifications['total'] = self::getTotalNotificaciones($notifications['notificaciones']);

        return $notifications;
    }

    public static function getNotificacionRequerimientosConObservacionFinal($notifications, $hasRolReceptor, $hasRolSupervisor)
    {
        $requerimientosConObservacionFinal = self::getRequerimientosConObservacionFinal($hasRolReceptor, $hasRolSupervisor);
        if ($requerimientosConObservacionFinal) {
            $notifications['notificaciones']['requerimientosConObservacionFinal'] = $requerimientosConObservacionFinal;
        }
        return $notifications;
    }

    public function setNotificacionesArray($notifications, $calledFrom, $notificationFunctions)
    {
        foreach ($notificationFunctions as $notificationKey => $functionName) {
            $data = self::$functionName($calledFrom);
            if ($data) {
                $notifications['notificaciones'][$notificationKey] = $data;
            }
        }

        return $notifications;
    }

    public static function getOficiosParaReDerivarASupervisor($calledFrom)
    {
        $estadoAprobada = Mds_legales_respuesta_estado::APROBADA;

        $select = 'derivacion.idlegalesderivacion, derivacion.idlegalesoficio';
        if ($calledFrom === 'requerimientosDevueltos') {
            $select .= ", configuracion.descripcion";
        }
        //Buscamos aquellas derivaciones que sean rechazadas por un supervisor y que no tenga generadores de respuesta
        $oficiosParaReDerivar = Mds_legales_derivacion::find()
            ->select($select)
            ->from('mds_legales_derivacion as derivacion')
            ->innerJoin('mds_legales_oficio as oficio', 'derivacion.idlegalesoficio = oficio.idlegalesoficio');

        if ($calledFrom === 'requerimientosDevueltos') {
            $oficiosParaReDerivar = $oficiosParaReDerivar->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = oficio.tipo_oficio');
        }

        $oficiosParaReDerivar = $oficiosParaReDerivar->where([
            'derivacion.supervisor' => 1,
            'derivacion.activo' => 0,
            'derivacion.re_derivado' => 0,
            'oficio.activo' => 1,
        ])
            ->andWhere(['not', ['derivacion.observaciones' => null]])
            ->andWhere(['not', ['derivacion.fecha_usu_no_corresponde' => null]])
            ->groupBy('derivacion.idlegalesoficio')
            ->orderBy(['oficio.idlegalesoficio' => SORT_DESC])
            ->asArray()
            ->all();


        $indexOficiosParaReDerivar = 0;
        $existeOficioParaReDerivar = false;
        $oficiosParaReDerivarFiltrados = array();
        while ($indexOficiosParaReDerivar < count($oficiosParaReDerivar) && !$existeOficioParaReDerivar) {
            $oficioParaReDerivar = $oficiosParaReDerivar[$indexOficiosParaReDerivar];
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $oficioParaReDerivar['idlegalesoficio']])->one();
            if (count($oficio->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0) {
                if ($calledFrom === 'layout') {
                    $existeOficioParaReDerivar = true;
                } else {
                    array_push($oficiosParaReDerivarFiltrados, $oficioParaReDerivar);
                }
            }
            $indexOficiosParaReDerivar++;
        }

        return $calledFrom === 'layout' ? $existeOficioParaReDerivar : $oficiosParaReDerivarFiltrados;
    }

    public static function getOficiosSinRespuesta($calledFrom)
    {
        $idRtaObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $usuarioAuth = Yii::$app->user->identity;

        $oficiosDerivadosAlUsuario = Mds_legales_oficio::find()
            ->select('derivacion.idlegalesderivacion, 
                            derivacion.idlegalesoficio,
                            mds_legales_oficio.fecha_carga,
                            respuesta.idlegalesrespuesta')
            ->from('mds_legales_oficio')
            ->innerJoinWith('derivaciones derivacion', false)
            ->joinWith('respuestas0 respuesta', false)
            ->where("derivacion.idusuario = {$usuarioAuth->idusuario} 
                            AND derivacion.supervisor = 0 
                            AND derivacion.fecha_usu_no_corresponde IS NULL 
                            AND derivacion.activo = 1 
                            AND mds_legales_oficio.activo = 1")
            ->orderBy(['derivacion.idlegalesderivacion' => SORT_DESC])
            ->asArray()
            ->all();

        $oficioModel = new Mds_legales_oficio();
        $indexoficiosDerivadosAlUsuario = 0;
        $existeOficiosDerivadosAlUsuario = false;
        $oficiosSinRespuesta = array();

        while ($indexoficiosDerivadosAlUsuario < count($oficiosDerivadosAlUsuario) && !$existeOficiosDerivadosAlUsuario) {
            $oficioDerivadoAlUsuario = $oficiosDerivadosAlUsuario[$indexoficiosDerivadosAlUsuario];
            $oficio = $oficioModel->findOne($oficioDerivadoAlUsuario['idlegalesoficio']);
            if (count($oficio->getLastRespuestasEstadoByEstado($idRtaObservada)) == $oficio->getTotalRespuestasGeneradas() || $oficioDerivadoAlUsuario['idlegalesrespuesta'] == null) {
                /*Si todas las respuestas de ese requerimiento el ultimo estado es observada o rechazada (y ademas esas respuestas no son mias, 
                    ya que si fueran mias me llegaria la notificacion de que esta observada), debemos mostrarle la notificacion que debe generar una respuesta */
                if ($calledFrom === 'layout') {
                    $existeOficiosDerivadosAlUsuario = true;
                } else {
                    array_push($oficiosSinRespuesta, $oficioDerivadoAlUsuario);
                }
            };
            $indexoficiosDerivadosAlUsuario++;
        }

        return  $calledFrom === 'layout' ? $existeOficiosDerivadosAlUsuario : $oficiosSinRespuesta;
    }

    public static function getRespuestasObservadas($calledFrom)
    {
        $idRtaAprobada = Mds_legales_respuesta_estado::APROBADA;
        $idRtaObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $usuarioAuth = Yii::$app->user->identity;

        $respuestasObservadas = Mds_legales_respuesta_estado::find()
            ->select(['respuesta.*', 'derivacion.idlegalesderivacion as idDerivacion'])
            ->innerJoinWith('respuesta respuesta', false)
            ->innerJoinWith('derivaciones derivacion', false)
            ->innerJoinWith('oficio oficio', false)
            ->where([
                'derivacion.supervisor' => 0,
                'derivacion.fecha_usu_no_corresponde' => null,
                'derivacion.idusuario' => $usuarioAuth->idusuario,
                'derivacion.activo' => 1,
                'mds_legales_respuesta_estado.estado' => $idRtaObservada,
                'respuesta.idusuario' => $usuarioAuth->idusuario,
                'respuesta.idrespuestacorreccion' => null,
                'oficio.activo' => 1,
            ])
            ->orderBy(['oficio.idlegalesoficio' => SORT_DESC])
            ->asArray()
            ->all();

        $indexRespuestasObservadas = 0;
        $existeRespuestasObservadas = false;
        $respuestasObservadasFiltradas = array();
        while ($indexRespuestasObservadas < count($respuestasObservadas) && !$existeRespuestasObservadas) {
            $respuesta = $respuestasObservadas[$indexRespuestasObservadas];
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $respuesta['idlegalesoficio']])->one();
            if (count($oficio->getLastRespuestasEstadoByEstado($idRtaAprobada)) === 0) {
                if ($calledFrom === 'layout') {
                    $existeRespuestasObservadas = true;
                } else {
                    array_push($respuestasObservadasFiltradas, $respuesta);
                }
            }
            $indexRespuestasObservadas++;
        }

        return $calledFrom === 'layout' ? $existeRespuestasObservadas : $respuestasObservadasFiltradas;
    }

    public static function getRespuestasSinSupervisar($calledFrom)
    {
        $usuarioAuth = Yii::$app->user->identity;

        $subqueryRespuestasSinSupervisar = Mds_legales_respuesta_visto::find()
            ->select('idlegalesrespuesta')
            ->where(['activo' => 1, 'idusuario' => $usuarioAuth->idusuario]);

        $respuestasSinSupervisar = Mds_legales_respuesta::find()
            ->select(['respuesta.idlegalesoficio', 'respuestaEstado.idlegalesrespuesta', 'COUNT(respuestaEstado.idlegalesrespuesta) AS total_estados'])
            ->from('mds_legales_respuesta as respuesta')
            ->innerJoinWith('estados respuestaEstado', false)
            ->innerJoin('mds_legales_oficio as oficio', 'respuesta.idlegalesoficio = oficio.idlegalesoficio AND oficio.activo = 1')
            ->innerJoin('mds_legales_derivacion as derivacion', "oficio.idlegalesoficio = derivacion.idlegalesoficio AND derivacion.activo = 1 AND derivacion.idusuario = $usuarioAuth->idusuario AND derivacion.supervisor = 1")
            ->where(['not in', 'respuesta.idlegalesrespuesta', $subqueryRespuestasSinSupervisar])
            ->groupBy('respuestaEstado.idlegalesrespuesta')
            ->having(['total_estados' => 1])
            ->orderBy(['respuesta.idlegalesrespuesta' => SORT_DESC])
            ->asArray();

        if ($calledFrom === 'layout') {
            $respuestasSinSupervisar = $respuestasSinSupervisar->one();
        } else {
            $respuestasSinSupervisar = $respuestasSinSupervisar->all();
        }

        $existeRespuestasSinSupervisar = $respuestasSinSupervisar ? true : false;

        return $calledFrom === 'layout' ? $existeRespuestasSinSupervisar : $respuestasSinSupervisar;
    }

    public static function getOficiosSinDerivarAUsuarios($calledFrom)
    {
        $usuarioAuth = Yii::$app->user->identity;

        $oficiosSinDerivarAUsuarios = Mds_legales_derivacion::find()
            ->select('derivacion.idlegalesderivacion, derivacion.idlegalesoficio')
            ->from('mds_legales_derivacion as derivacion')
            ->innerJoin('mds_legales_oficio as oficio', 'derivacion.idlegalesoficio = oficio.idlegalesoficio AND oficio.activo = 1')
            ->where([
                'derivacion.idusuario' => $usuarioAuth->idusuario,
                'derivacion.supervisor' => 1,
                'derivacion.activo' => 1,
                'derivacion.fecha_usu_no_corresponde' => null,
            ])
            ->andWhere(
                ['not in', 'derivacion.idlegalesoficio', (new \yii\db\Query())
                    ->select('deri.idlegalesoficio')
                    ->from('mds_legales_derivacion as deri')
                    ->where(['deri.supervisor' => 0])]
            )
            ->orderBy(['derivacion.idlegalesderivacion' => SORT_DESC])
            ->asArray();

        if ($calledFrom === 'layout') {
            $oficiosSinDerivarAUsuarios = $oficiosSinDerivarAUsuarios->one();
        } else {
            $oficiosSinDerivarAUsuarios = $oficiosSinDerivarAUsuarios->all();
        }

        $existenOficiosSinDerivarAUsuarios = $oficiosSinDerivarAUsuarios ? true : false;

        return $calledFrom === 'layout' ? $existenOficiosSinDerivarAUsuarios : $oficiosSinDerivarAUsuarios;
    }

    public static function getRespuestasRechazadas($calledFrom)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $estadoObservado = Mds_legales_respuesta_estado::OBSERVADA;
        $estadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;

        $subqueryRespuestasRechazadas = Mds_legales_respuesta_estado::find()
            ->select('idlegalesrespuesta')
            ->where(['estado' => $estadoObservado]);

        $respuestasRechazadas = Mds_legales_respuesta_estado::find()
            ->select(['oficio.idlegalesoficio', 'mds_legales_respuesta_estado.idlegalesrespuesta'])
            ->distinct('oficio.idlegalesoficio')
            ->innerJoinWith('respuesta respuesta', false)
            ->innerJoinWith('oficio oficio', false)
            ->innerJoinWith('derivaciones as derivacionSupervisor', false)
            ->where([
                'mds_legales_respuesta_estado.estado' => $estadoRechazado,
                'derivacionSupervisor.idusuario' => $usuarioAuth->idusuario,
                'derivacionSupervisor.activo' => 1,
                'oficio.activo' => 1,
                'derivacionSupervisor.supervisor' => 1
            ])
            ->andWhere(['not in', 'mds_legales_respuesta_estado.idlegalesrespuesta', $subqueryRespuestasRechazadas])
            ->groupBy(['respuesta.idlegalesoficio'])
            ->orderBy(['oficio.idlegalesoficio' => SORT_DESC])
            ->asArray()
            ->all();

        $indexRespuestasRechazadas = 0;
        $existeRespuestasRechazadas = false;
        $respuestasRechazadasFiltradas = array();

        while ($indexRespuestasRechazadas < count($respuestasRechazadas) && !$existeRespuestasRechazadas) {
            $respuestaRechazada = $respuestasRechazadas[$indexRespuestasRechazadas];
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $respuestaRechazada['idlegalesoficio']])->one();
            $index = 0;
            $puedeDevolverReceptor = false;
            while ($index < count($oficio->respuestas) && !$puedeDevolverReceptor) {
                if ($oficio->respuestas[$index]->ultimoEstado->estado == $estadoRechazado && $oficio->respuestas[$index]->derivacion) {
                    //Aquellas respuestas que su estado sea rechazada y el usuario receptor no haya rechazado
                    $puedeDevolverReceptor = true;
                }
                $index++;
            }

            if ($puedeDevolverReceptor) {
                if ($calledFrom === 'layout') {
                    $existeRespuestasRechazadas = true;
                } else {
                    array_push($respuestasRechazadasFiltradas, $respuestaRechazada);
                }
            }
            $indexRespuestasRechazadas++;
        }
        return $calledFrom === 'layout' ? $existeRespuestasRechazadas : $respuestasRechazadasFiltradas;
    }

    public static function getOficiosParaReDerivar($calledFrom)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $estadoAprobada = Mds_legales_respuesta_estado::APROBADA;

        $subqueryOficiosParaReDerivar = Mds_legales_derivacion::find()
            ->select('deri.idlegalesoficio')
            ->from('mds_legales_derivacion as deri')
            ->where([
                'deri.idusuario' => $usuarioAuth->idusuario,
                'deri.fecha_usu_no_corresponde' => null,
                'deri.supervisor' => 1,
                'deri.activo' => 1,
            ]);

        $oficiosParaReDerivar = Mds_legales_derivacion::find()
            ->select('derivacion.idlegalesoficio')
            ->from('mds_legales_derivacion as derivacion')
            ->innerJoin('mds_legales_oficio', 'mds_legales_oficio.idlegalesoficio = derivacion.idlegalesoficio')
            ->where([
                'mds_legales_oficio.activo' => 1,
                'derivacion.supervisor' => 0,
                'derivacion.activo' => 0,
                'derivacion.re_derivado' => 0,
            ])
            ->andWhere(['derivacion.idlegalesoficio' => $subqueryOficiosParaReDerivar])
            ->groupBy('derivacion.idlegalesoficio')
            ->orderBy(['mds_legales_oficio.idlegalesoficio' => SORT_DESC])
            ->asArray()
            ->all();

        $indexOficiosParaReDerivar = 0;
        $existeOficiosParaReDerivar = false;
        $oficiosParaReDerivarFiltrados = array();

        while ($indexOficiosParaReDerivar < count($oficiosParaReDerivar) && !$existeOficiosParaReDerivar) {
            $derivacion = $oficiosParaReDerivar[$indexOficiosParaReDerivar];
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $derivacion['idlegalesoficio']])->one();
            if (count($oficio->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0) {
                if ($calledFrom === 'layout') {
                    $existeOficiosParaReDerivar = true;
                } else {
                    array_push($oficiosParaReDerivarFiltrados, $derivacion);
                }
            }
            $indexOficiosParaReDerivar++;
        }

        return $calledFrom === 'layout' ? $existeOficiosParaReDerivar : $oficiosParaReDerivarFiltrados;
    }

    public static function getOficiosConRespuestasVistas($calledFrom)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;

        $oficiosConRespuestasVistas = Mds_legales_respuesta::find()
            ->select(['respuestaVisto.idlegalesoficio', 'respuestaVisto.fecha_carga'])
            ->from('mds_legales_respuesta as respuesta')
            ->innerJoin('mds_legales_respuesta_estado as respuestaEstado', "respuesta.idlegalesrespuesta = respuestaEstado.idlegalesrespuesta AND respuestaEstado.estado = $estadoPendiente AND respuestaEstado.fecha_fin IS NULL")
            ->innerJoinWith('vistos0 as respuestaVisto', false)
            ->innerJoin('mds_legales_oficio as oficio', 'respuesta.idlegalesoficio = oficio.idlegalesoficio AND oficio.activo = 1')
            ->innerJoin('mds_legales_derivacion as derivacion', "oficio.idlegalesoficio = derivacion.idlegalesoficio AND derivacion.activo = 1 AND derivacion.idusuario = $usuarioAuth->idusuario AND derivacion.supervisor = 1")
            ->where(['respuestaVisto.activo' => 1])
            ->andWhere(['not', ['respuestaVisto.idusuario' => $usuarioAuth->idusuario]])
            ->groupBy('respuesta.idlegalesoficio')
            ->orderBy(['respuestaVisto.idlegalesrespuestavisto' => SORT_DESC])
            ->asArray();

        if ($calledFrom === 'layout') {
            $oficiosConRespuestasVistas = $oficiosConRespuestasVistas->one();
        } else {
            $oficiosConRespuestasVistas = $oficiosConRespuestasVistas->all();
        }

        $existeOficiosConRespuestasVistas = $oficiosConRespuestasVistas ? true : false;

        return $calledFrom === 'layout' ? $existeOficiosConRespuestasVistas : $oficiosConRespuestasVistas;
    }

    public static function getOficiosRespuestasAprobadasNoEnviadas($calledFrom)
    {
        $idEstadoAprobada = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoEnviada = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        /*Respuestas aprobas que no fueron enviadas ni rechazadas*/

        $subquery = Mds_legales_respuesta_estado::find()
            ->select(['idlegalesrespuesta'])
            ->from('mds_legales_respuesta_estado')
            ->where(['in', 'estado', [$idEstadoEnviada, $idEstadoRechazado]]);

        $result = Mds_legales_respuesta_estado::find()
            ->select(['oficio.idlegalesoficio'])
            ->distinct('oficio.idlegalesoficio')
            ->from('mds_legales_respuesta_estado as respuestaEstado')
            ->innerJoin('mds_legales_respuesta as respuesta', 'respuesta.idlegalesrespuesta = respuestaEstado.idlegalesrespuesta')
            ->innerJoin('mds_legales_oficio as oficio', 'respuesta.idlegalesoficio = oficio.idlegalesoficio')
            ->where(['respuestaEstado.estado' => $idEstadoAprobada])
            ->andWhere(['oficio.activo' => 1])
            ->andWhere(['not in', 'respuestaEstado.idlegalesrespuesta', $subquery])
            ->orderBy(['oficio.idlegalesoficio' => SORT_DESC])
            ->asArray();

        if ($calledFrom === 'layout') {
            $result = $result->one();
        } else {
            $result = $result->all();
        }

        $existeOficiosRespuestasAprobadasNoEnviadas = $result ? true : false;

        return $calledFrom === 'layout' ? $existeOficiosRespuestasAprobadasNoEnviadas : $result;
    }

    public static function getVencimientoPlazoOficios($calledFrom)
    {
        $idEstadoEnviada = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobada = Mds_legales_respuesta_estado::APROBADA;
        $today = date('Y-m-d');
        $minLimitDay =  date('Y-m-d', strtotime($today . ' - 3 days'));
        $dayOfWeek = date('w', strtotime($today));
        //Si es viernes hay que sumar 4 dias a la fecha de plazo, sino 2 dias.
        if ($dayOfWeek == 5) {
            $limitDay = date('Y-m-d', strtotime($today . ' + 4 days'));
        } else {
            $limitDay = date('Y-m-d', strtotime($today . ' + 2 days'));
        }

        $usuarioAuth = Yii::$app->user->identity;
        $idusuario = $usuarioAuth->idusuario;

        $oficiosProntoVencimiento = Mds_legales_oficio::find()
            ->select(['oficio.idlegalesoficio'])
            ->distinct()
            ->from('mds_legales_oficio as oficio')
            ->innerJoin('mds_legales_derivacion as derivacion', 'derivacion.idlegalesoficio = oficio.idlegalesoficio')
            ->innerJoin('mds_seg_usuario_rol as usuario_rol', ['usuario_rol.idusuario' => $idusuario])
            ->where([
                'between', 'fecha_plazo', $minLimitDay, $limitDay
            ])
            ->andWhere([
                'or',
                ['oficio.idusuario' => $idusuario],
                ['and', ['derivacion.idusuario' => $idusuario, 'derivacion.activo' => 1, 'derivacion.fecha_usu_no_corresponde' => null]],
                ['usuario_rol.idrol' => [Mds_legales_oficio::ID_ROL_VINCULACION, Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL]],
            ])
            ->andWhere(['oficio.activo' => 1])
            ->andWhere([
                'not in', 'oficio.idlegalesoficio', (new \yii\db\Query())
                    ->select('idlegalesoficio')
                    ->from('mds_legales_respuesta')
                    ->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta')
                    ->where(['or', ['estado' => $idEstadoEnviada], ['estado' => $idEstadoAprobada]])
            ])
            ->orderBy(['oficio.idlegalesoficio' => SORT_DESC])
            ->asArray();

        if ($calledFrom === 'layout') {
            $oficiosProntoVencimiento = $oficiosProntoVencimiento->one();
        } else {
            $oficiosProntoVencimiento = $oficiosProntoVencimiento->all();
        }

        $existeOficioProntoVencimiento = $oficiosProntoVencimiento ? true : false;

        return $calledFrom === 'layout' ? $existeOficioProntoVencimiento : $oficiosProntoVencimiento;
    }

    public static function getRequerimientosConObservacionFinal($hasRolReceptor, $hasRolSupervisor)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $usuarioAuth = Yii::$app->user->identity;
        $connection = Yii::$app->getDb();

        $innerJoinDerivacion = "INNER JOIN mds_legales_derivacion AS derivacion ON oficio.idlegalesoficio = derivacion.idlegalesoficio AND derivacion.activo = 1 AND derivacion.idusuario = {$usuarioAuth->idusuario}";
        if ($hasRolReceptor && !$hasRolSupervisor) {
            $innerJoinDerivacion .= " AND derivacion.supervisor = 0 ";
        } else if (!$hasRolReceptor && $hasRolSupervisor) {
            $innerJoinDerivacion .= " AND derivacion.supervisor = 1 ";
        }

        $requerimientosConObservacionFinal = $connection->createCommand(
            "SELECT respuesta.idlegalesoficio, mds_legales_respuesta_estado.fecha_inicio
            FROM mds_legales_respuesta AS respuesta
            INNER JOIN mds_legales_respuesta_estado ON respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta AND mds_legales_respuesta_estado.estado = $idEstadoEnviado
            INNER JOIN mds_legales_oficio AS oficio ON respuesta.idlegalesoficio = oficio.idlegalesoficio AND oficio.activo = 1
            $innerJoinDerivacion
            WHERE respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != ''
            GROUP BY respuesta.idlegalesoficio
            ORDER BY mds_legales_respuesta_estado.fecha_inicio DESC
            "
        )->queryAll();

        if (!empty($requerimientosConObservacionFinal)) {
            $fechaUltimoRequerimientoConObservacionFinal = date('Y-m-d', strtotime($requerimientosConObservacionFinal[0]['fecha_inicio'])); //Lo hago para quitarle las horas
            $strtotimeFechaUltimoRequerimientoConObservacionFinal = strtotime($fechaUltimoRequerimientoConObservacionFinal);

            $today =  strtotime(date('Y-m-d'));
            $dayOfWeek = date('w', $strtotimeFechaUltimoRequerimientoConObservacionFinal);
            //Si es viernes hay que sumar 4 dias a la fecha de plazo, sino 2 dias.
            if ($dayOfWeek == 5) {
                $limitDay = strtotime($fechaUltimoRequerimientoConObservacionFinal . ' + 4 days');
            } else {
                $limitDay = strtotime($fechaUltimoRequerimientoConObservacionFinal . ' + 2 days');
            }

            if ($today < $strtotimeFechaUltimoRequerimientoConObservacionFinal || $today > $limitDay) {
                //Si hoy es menor a la fecha de la ultima observacion final o hoy es mayor al limite (2 dias) limpiamos el arreglo para no mostrar las notificaciones
                $requerimientosConObservacionFinal = array();
            }
        }


        return $requerimientosConObservacionFinal;
    }

    public function getTotalNotificaciones($notificaciones)
    {
        $total = 0;
        foreach ($notificaciones as $notificacion) {
            if ($notificacion) {
                $total++;
            }
        }
        return $total;
    }

    public static function getUsuariosSegunRol($idRol)
    {
        $connection = Yii::$app->getDb();
        $usuariosSegunRol = $connection->createCommand("SELECT usuario.idusuario,UPPER(CONCAT(apellido,', ',nombre)) as nombre_apellido 
                                                        FROM mds_seg_usuario_rol AS usuarioRol 
                                                        INNER JOIN mds_seg_usuario AS usuario ON usuario.idusuario = usuarioRol.idusuario 
                                                        AND usuarioRol.idrol = {$idRol} 
                                                        AND (activo = 1 OR (activo = 0 AND attemps >= 3))
                                                        ORDER BY usuario.apellido,usuario.nombre;")->queryAll();
        return $usuariosSegunRol;
    }

    public function getDerivacion()
    {
        return Mds_legales_derivacion::find()->where(['idlegalesoficio' => $this->idlegalesoficio, 'supervisor' => 0, 'activo' => 1])->one();
    }

    public function getDerivaciones()
    {
        return $this->hasMany(Mds_legales_derivacion::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getSugerenciaUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'sugerencia_idusuario']);
    }

    public function getCaratulaModel()
    {
        return $this->hasOne(Mds_legales_caratula::class, ['idlegalescaratula' => 'idlegalescaratula']);
    }

    public function getUsuariosDerivacionRechazo()
    {
        return Mds_legales_derivacion::find()->where('fecha_usu_no_corresponde IS NOT NULL')->andWhere(['idlegalesoficio' => $this->idlegalesoficio, 'activo' => 0])->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();
    }

    public function getUsuariosReceptoresPorRechazoSupervisor()
    {
        return Mds_legales_derivacion::find()->where('fecha_usu_no_corresponde IS NULL AND observaciones IS NULL')->andWhere(['idlegalesoficio' => $this->idlegalesoficio, 'activo' => 0, 'supervisor' => 0])->all();
    }

    public function getReceptoresRechazo()
    {
        return Mds_legales_derivacion::find()->where(['idlegalesoficio' => $this->idlegalesoficio, 'activo' => 0, 'supervisor' => 0])->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();
    }

    public function getOficiosSinResponder($fechaInicio = null, $fechaFin = null)
    {
        $where = "mds_legales_respuesta.idlegalesrespuesta IS NULL AND mds_legales_oficio.activo = 1";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }

        return $this->find()
            ->select('mds_legales_oficio.*')
            ->leftJoin('mds_legales_respuesta', 'mds_legales_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->where($where)
            ->all();
    }

    public function getOficiosSinEnviarPasadoLimiteTiempo($fechaInicio = null, $fechaFin = null)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        $where =
            "mds_legales_oficio.idlegalesoficio NOT IN 
            (
            SELECT mds_legales_respuesta.idlegalesoficio 
            FROM mds_legales_respuesta 
            INNER JOIN mds_legales_respuesta_estado 
            ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
            WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
        AND mds_legales_oficio.activo = 1";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }

        return $this->find()
            ->select('mds_legales_oficio.*')
            ->where($where)
            ->all();
    }

    public function getListapersonasvinculadas()
    {
        $personasVinculadas = $this->getPersonasVinculadas();
        $listaPersonasVinculadas = "<div style='margin-bottom: 10px;'>No existen personas vinculadas.</div>";
        if (!empty($personasVinculadas)) {
            $listaPersonasVinculadas = "<div style='margin-bottom: 10px;'><ul>";
            $liPersonasVinculadas = "";
            foreach ($personasVinculadas as $keyPersona => $personaVinculada) {
                if ($personaVinculada->idpersona) {
                    $nroDocumento = $personaVinculada->persona->documento ? $personaVinculada->persona->documento : '';
                    $nombre = $personaVinculada->persona->nombre ? mb_strtoupper($personaVinculada->persona->nombre) : '';
                    $apellido = $personaVinculada->persona->apellido ? ($nombre ? mb_strtoupper($personaVinculada->persona->apellido) . ', ' : mb_strtoupper($personaVinculada->persona->apellido)) : '';
                    $domicilioCalle = $personaVinculada->persona->domicilio_calle ? $personaVinculada->persona->domicilio_calle : '';
                    $domicilioNumero = $personaVinculada->persona->domicilio_numero ? $personaVinculada->persona->domicilio_numero : '';
                } else {
                    $nroDocumento = $personaVinculada->documento ? $personaVinculada->documento : '';
                    $nombre = $personaVinculada->nombre ? mb_strtoupper($personaVinculada->nombre) : '';
                    $apellido = $personaVinculada->apellido ? ($nombre ? mb_strtoupper($personaVinculada->apellido) . ', ' : mb_strtoupper($personaVinculada->apellido))  : '';
                    $domicilioCalle = $personaVinculada->domicilio_calle ? $personaVinculada->domicilio_calle : '';
                    $domicilioNumero = $personaVinculada->domicilio_numero ? $personaVinculada->domicilio_numero : '';
                }
                $nroDocumentoString = $nroDocumento ? "<b>DNI:</b> $nroDocumento" : '';
                $nombreString = $apellido || $nombre ? "<b>Nombre:</b> $apellido $nombre" : '';
                $domicilioString = $domicilioCalle || $domicilioNumero ? "<b>Domicilio:</b> $domicilioCalle $domicilioNumero" : '';
                $parentesco = $personaVinculada->parentesco ? $personaVinculada->parentesco->descripcion : '';
                $parentescoPointStart = strpos($parentesco, ".") ? strpos($parentesco, ".") + 1 : 0;
                $parentescoDescripcion = $parentesco ? substr($parentesco, $parentescoPointStart)  : '';
                $parentescoString = $parentescoDescripcion ? "<b>Parentesco:</b> {$parentescoDescripcion}" : '';
                $mail = $personaVinculada->mail ? "<b>Mail:</b> $personaVinculada->mail" : '';
                $telefono = $personaVinculada->telefono ? "<b>Teléfono:</b> $personaVinculada->telefono" : '';
                $observaciones = $personaVinculada->observaciones ? "<b>Observaciones:</b> $personaVinculada->observaciones" : '';
                $stringDatosPersona = "$nroDocumentoString $parentescoString $nombreString $domicilioString $mail $telefono $observaciones";
                $nroPersona = $keyPersona + 1;
                $liPersonasVinculadas .= "<li><b>#$nroPersona</b> - $stringDatosPersona</li>";
            }
            $listaPersonasVinculadas .= "$liPersonasVinculadas</ul></div>";
        }
        return $listaPersonasVinculadas;
    }

    public static function getRequerimientosByCaratula($id)
    {
        return Mds_legales_oficio::find()
            ->select("idlegalesoficio,
                    idemisor, 
                    lugar_libramiento, 
                    donde_tramita, 
                    doctor_a_cargo,
                    mds_legales_caratula.caratula, 
                    mds_legales_caratula.numero_expediente, 
                    mds_legales_caratula.anio_expediente, 
                    mds_legales_caratula.caso,
                    idarea
                    ")
            ->where("mds_legales_caratula.deleted_at IS NULL AND mds_legales_oficio.idlegalescaratula = $id AND mds_legales_oficio.activo = 1")
            ->innerJoin('mds_legales_caratula', 'mds_legales_caratula.idlegalescaratula = mds_legales_oficio.idlegalescaratula')
            ->orderBy(["idlegalesoficio" => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getUltimoRequerimientoByCaratula($id)
    {
        return Mds_legales_oficio::find()
            ->select("idlegalesoficio,
                    idemisor, 
                    lugar_libramiento, 
                    donde_tramita, 
                    doctor_a_cargo, 
                    mds_legales_caratula.caratula, 
                    mds_legales_caratula.numero_expediente, 
                    mds_legales_caratula.anio_expediente, 
                    mds_legales_caratula.caso, 
                    idarea")
            ->where("mds_legales_caratula.deleted_at IS NULL AND mds_legales_oficio.idlegalescaratula = $id AND mds_legales_oficio.activo = 1")
            ->innerJoin('mds_legales_caratula', 'mds_legales_caratula.idlegalescaratula = mds_legales_oficio.idlegalescaratula')
            ->asArray()
            ->orderBy(["idlegalesoficio" => SORT_DESC])
            ->one();
    }

    public static function buscarOficiosConCaratulasNulas()
    {
        return Mds_legales_oficio::find()
            ->select("idlegalesoficio,
                caratula,
                numero_expediente,
                anio_expediente,
                caso
                ")
            ->where("mds_legales_oficio.idlegalescaratula IS NULL
                AND mds_legales_oficio.caratula IS NOT NULL 
                AND mds_legales_oficio.caratula != '' 
                AND caratula NOT IN
                    (
                    'HOGAR CONVIVENCIA S/SITUACIÓN', 
                    'HOGAR MALEN S/SITUACIÓN', 
                    'HOGAR AMANCAY S/ PROTECCION DE DERECHOS', 
                    'HOGAR LOS BAJITOS', 
                    'Hogar Convivencia', 
                    'HOGAR AMANCAY RELEVAMIENTO',
                    'HOGAR LOS BAJITOS S/SITUACION ',
                    'HOGAR AYENHUE',
                    'HOGAR YAMPAI',
                    'HOGAR LOS BAJITOS S/ SITUACION',
                    'HOGAR YAMPAI INFORME',
                    'SITUACION DE HOGARES, RECURSOS Y APORTES S/ PROTECCION DE DERECHOS',
                    'URGENTE - HOGAR LOS BAJITOS',
                    'HOGAR CASA DE ADMISION S/ SITUACION',
                    'HOGAR AYENHUE S/SITUACION',
                    'HOGARES DE ADOLESCENTES',
                    'HOGAR CONVIVIENDO S/ SITUACION'
                    )
                    ")
            ->asArray()
            ->all();
    }

    public static function buscarOficiosByUsuarioAndTipo($usuarioId, $esSupervisor)
    {
        //Dado un id de usuario y indicando si es supervisor o generador de respuesta, busca todos los oficios que tenga derivados ese usuario
        return Mds_legales_oficio::find()
            ->select("mds_legales_oficio.idlegalesoficio")
            ->innerJoin('mds_legales_derivacion', 'mds_legales_derivacion.idlegalesoficio = mds_legales_oficio.idlegalesoficio')
            ->where("mds_legales_oficio.activo = 1 
                    AND mds_legales_derivacion.activo = 1 
                    AND mds_legales_derivacion.idusuario = $usuarioId 
                    AND mds_legales_derivacion.supervisor = $esSupervisor")
            ->groupBy('mds_legales_oficio.idlegalesoficio')
            ->all();
    }
}
