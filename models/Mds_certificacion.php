<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion".
 *
 * @property int $idcertificacion
 * @property int $idusuario_carga Usuario que carga
 * @property int $idusuario_borra Usuario que borra
 * 
 * @property int $idlocalidad
 * @property string|null $periodo_desde
 * @property string|null $periodo_hasta
 * @property int $idbeneficiario
 * @property int|null $idprograma
 * @property int|null $idarea
 * @property int|null $idnivel_autorizacion
 * @property int|null $iddireccion
 * @property string|null $nro_expediente
 * @property string|null $observaciones
 * @property int|null $idcaracter
 * @property int|null $tipo_jubilacion
 * @property int|null $idorganismo_solicitante
 * @property int|null $idestado
 * @property int|null $idorganismo 
 * @property string|null $tipo_certificacion
 * 
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComPersona $idbeneficiario0
 * @property SdsComConfiguracion $idprograma
 * @property SdsComLocalidad $idlocalidad0
 * @property MdsSegUsuario $idusuario_carga
 */
class Mds_certificacion extends \yii\db\ActiveRecord
{
    const PATH = "uploads/certificaciones/";
    const ID_PROVINCIA_NEUQUEN = 58;
    const CARACTER_VIA_RAPIDA = 3246; // local= 3246 desarrollo = 3246 produccion = 3246
    const TIPO_JUBILACION_OTRO = 3249; // local=3249 desarrollo= 3249 producción=3249
    const PARENTESCO_OTRO_OPTION = 69; // opcion otros no familiares en el select
    const PARENTESCO_TITULAR = 5848; // opcion titular en el select
    const ID_INCREMENTO = 6138;

    const ID_ROL_SOLICITANTE = 137;
    const ID_ROL_NIVEL1 = 138;
    const ID_ROL_NIVEL2 = 139;
    const ID_ROL_NIVEL3 = 140;
    const ID_ROL_NIVEL4 = 141;
    const ID_ROL_NIVEL5 = 142;
    const ID_ROL_FUNCIONARIO = 143;
    const ID_ROL_ADMINISTRADOR_GENERAL = 173;
    const ID_ROLES_CERTIFICACIONES = [137, 138, 139, 140, 141, 142, 143, 173];
    const ID_ROL_DASHBOARD = 191;

    const AREA_SOLICITANTE = "solicitudes";
    const AREA_NA1 = "na1";
    const AREA_NA2 = "na2";
    const AREA_NA3 = "na3";
    const AREA_NA4 = "na4";
    const AREA_ADMINISTRACION = "administracion";
    const AREA_FUNCIONARIO = "funcionario";
    const AREAS_CERTIFICACIONES = ["na1", "na2", "na3", "na4", "administracion"];

    const ID_NIVEL1 = 3975;
    const ID_NIVEL2 = 3976;
    const ID_NIVEL3 = 3977;
    const ID_NIVEL4 = 4313;
    const ID_NIVEL5 = 4314;
    const ID_NIVELES_CERTIFICACIONES = [3975, 3976, 3977, 4313, 4314];
    const NIVELES_CERTIFICACIONES = [1 => 3975, 2 => 3976, 4 => 4313, 5 => 4314, 3 => 3977];

    public $responsable;
    public $responsable_dni;
    public $monto;
    public $responsable_option;
    public $programa_descripcion;
    public $fecha_desde;
    public $fecha_hasta;
    public $estado_actual;
    public $direccion_actual;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario_carga', 'idlocalidad', 'idbeneficiario', 'idarea', 'idnivel_autorizacion', 'iddireccion', 'idprograma', 'tipo_certificacion', 'idcaracter', 'periodo_desde', 'periodo_hasta'], 'required'],
            [['idusuario_carga', 'idusuario_borra', 'idlocalidad', 'idbeneficiario', 'idcaracter', 'idorganismo_solicitante', 'idestado', 'idorganismo', 'idarea', 'idnivel_autorizacion', 'iddireccion', 'jubilacion', 'tipo_certificacion', 'tipo_jubilacion', 'sueldo', 'id_certificacion_incremento'], 'integer'],
            [['periodo_desde', 'periodo_hasta', 'created_at', 'updated_at', 'deleted_at', 'responsable'], 'safe'],
            [['observaciones', 'monto_jubilacion', 'codigo', 'equipo_tecnico', 'sueldo_monto'], 'string'],
            [['nro_expediente', 'nro_nota'], 'string', 'max' => 255],

            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['idbeneficiario'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idbeneficiario' => 'idpersona']],
            [['idprograma'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idprograma' => 'idconfiguracion']],
            [['idcaracter'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idcaracter' => 'idconfiguracion']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['tipo_jubilacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo_jubilacion' => 'idconfiguracion']],
            [['idarea'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['idarea' => 'idcertificaciondireccion']],
            [['idnivel_autorizacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idnivel_autorizacion' => 'idconfiguracion']],
            [['iddireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['iddireccion' => 'idcertificaciondireccion']],

            [['idorganismo_solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idorganismo_solicitante' => 'idconfiguracion']],

            [['idestado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idestado' => 'idconfiguracion']],

            // ['idnivel_riesgo', 'required', 'when' => function ($model) {
            //     return $model->idcaracter == $this::ID_CONDICION_URGENTE;
            // }],

            // ['idorganismo_solicitante', 'required', 'when' => function ($model) {
            //     return $model->tipo_certificacion == 1;
            // }],

            // ['monto_jubilacion', 'required', 'when' => function ($model) {
            //     return $model->jubilacion == 1;
            // }],

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
            'idcertificacion' => 'Idcertificacion',
            'idusuario_carga' => 'Usuario Carga',
            'idlocalidad' => 'Localidad',
            'periodo_desde' => 'Periodo Desde',
            'periodo_hasta' => 'Periodo Hasta',
            'idbeneficiario' => 'Beneficiario',
            'idprograma' => 'Programa',
            'idarea' => 'Área',
            'idnivel_autorizacion' => 'Nivel de autorización',
            'iddireccion' => 'Dirección',
            'observaciones' => 'Observaciones',
            'idcaracter' => 'Carácter',
            'tipo_certificacion' => 'Tipo Certificación',
            'idorganismo_solicitante' => 'Organismo Solicitante',
            'idestado' => 'Estado',
            'idorganismo' => 'Organismo',
            'nro_expediente' => 'Nro Expediente',
            'nro_nota' => 'Nro Nota',
            'codigo' => 'Código Interno',
            'equipo_tecnico' => 'Equipo Técnico',
            'jubilacion' => '¿Recibe jubilación/pensión?',
            'tipo_jubilacion' => 'Tipo de jubilación/pensión',
            'monto_jubilacion' => 'Monto Neto de la jubilación/pensión',
            'sueldo' => '¿Recibe sueldo?',
            'sueldo_monto' => 'Monto Neto del sueldo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
            'id_certificacion_incremento' => 'Certificación que se incrementa'
        ];
    }

    /**
     * Gets query for [[idbeneficiario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBeneficiario()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idbeneficiario']);
    }

    /**
     * Gets query for [[idarea]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Mds_certificacion_direccion::class, ['idcertificaciondireccion' => 'idarea']);
    }

    /**
     * Gets query for [[idnivel_autorizacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNivelAutorizacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idnivel_autorizacion']);
    }

    /**
     * Gets query for [[iddireccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccion()
    {
        return $this->hasOne(Mds_certificacion_direccion::class, ['idcertificaciondireccion' => 'iddireccion']);
    }

    // public function getDireccion0()
    // {
    //     $direccion =  Mds_certificacion_estado::find()
    //         ->select('sds_com_configuracion.*')
    //         ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_estado.iddireccion')
    //         ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
    //         ->where(['idcertificacion' => $this->idcertificacion])
    //         ->orderBy(['mds_certificacion_estado.idcertificacion' => SORT_DESC])
    //         ->asArray()
    //         ->one();
    //     return $direccion ? $direccion : '';
    // }

    /**
     * Gets query for [[idlocalidad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad']);
    }

    /**
     * Gets query for [[idprograma]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idprograma']);
    }

    /**
     * Gets query for [[idcaracter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCaracter()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idcaracter']);
    }

    /**
     * Gets query for [[tipo_jubilacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoJubilacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_jubilacion']);
    }

    /**
     * Gets query for [[idorganismo_solicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismoSolicitante()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idorganismo_solicitante']);
    }

    /**
     * Gets query for [[idorganismo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismo()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[idestado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idestado']);
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
     * Gets query for [[idpersona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idbeneficiario']);
    }

    /**
     * Gets query for [[idpersona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsables()
    {
        return $this->hasMany(Mds_certificacion_responsable::class, ['idcertificacion' => 'idcertificacion']);
    }

    /**
     * Gets query for [[id_certificacion_incremento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncremento()
    {
        return $this->hasOne(Mds_certificacion::class, ['idcertificacion' => 'id_certificacion_incremento']);
    }

    public function getDireccionActual()
    {
        $estado =  Mds_certificacion_estado::find()
            ->select(['mds_certificacion_direccion.idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) AS direccionActual', 'mds_certificacion_estado.idestado'])
            ->where(['idcertificacion' => $this->idcertificacion, 'fecha_fin' => null])
            ->leftJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
            ->leftJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
            ->asArray()
            ->one();

        //Se OBSERVA o se BAJA y vuelve al SOLICITANTE
        if (($estado['idestado'] == Mds_certificacion_estado::ESTADO_OBSERVADA || $estado['idestado'] == Mds_certificacion_estado::ESTADO_BAJA) && $estado['direccionActual'] == '') {
            $estado['direccionActual'] = 'SOLICITANTE';
        }

        if ($estado['idestado'] == Mds_certificacion_estado::ESTADO_ELIMINADA) {
            $estado['direccionActual'] = '';
        }
        if ($estado['idestado'] == Mds_certificacion_estado::ESTADO_ENVIADA) {
            $estado['direccionActual'] = '';
        }

        return $estado;
    }

    // /**
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDireccionPrevia0($idnivelUser)
    // {

    //     $where2 =  Mds_certificacion_estado::find()
    //         ->select('mds_certificacion_direccion.iddireccion')
    //         ->leftJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
    //         ->where(['mds_certificacion_estado.idcertificacion' => $this->idcertificacion])
    //         ->andWhere(['mds_certificacion_direccion.idnivelautorizacion' => $idnivelUser]);

    //     $where1 =  Mds_certificacion_direccion::find()
    //         ->select('mds_certificacion_direccion.idcertificaciondireccion')
    //         ->where(['mds_certificacion_direccion.iddireccion_padre' => $where2]);

    //     $direccion =  Mds_certificacion_estado::find()
    //         ->select('*')
    //         ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
    //         ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
    //         ->where(['mds_certificacion_estado.idcertificacion' => $this->idcertificacion])
    //         ->andWhere(['mds_certificacion_estado.iddireccion' => $where1])
    //         ->asArray()
    //         ->one();

    //     if ($direccion) {
    //         $string = $direccion['descripcion'];
    //         $this->direccionPrevia = $direccion['idconfiguracion'];
    //     } else {
    //         $string = 'Solicitante';
    //     }

    //     return $string;
    // }

    /**
     * Gets query for [[monto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonto0()
    {
        return $this->hasOne(Mds_certificacion_monto::class, ['idcertificacion' => 'idcertificacion'])->where("mds_certificacion_monto.deleted_at IS NULL");
    }

    public function getOtrosAdjuntos()
    {
        $adjuntos_especiales = [Mds_certificacion_programa::ADJUNTO_OBSERVAR, Mds_certificacion_programa::ADJUNTO_BAJA, Mds_certificacion_programa::ADJUNTO_RECHAZAR];

        $adjuntos =  Mds_legales_archivo::find()
            ->select(['mds_legales_archivo.nombre', 'mds_legales_archivo.path', 'mds_legales_archivo.tipo', 'mds_legales_archivo.objeto', 'configuracion.descripcion as tipoAdjunto'])
            ->where(
                [
                    'mds_legales_archivo.objeto' => 'mds_certificacion',
                    'mds_legales_archivo.activo' => true,
                    'mds_legales_archivo.id_objeto' => $this->idcertificacion
                ]
            )
            ->andWhere(['NOT IN', 'idconfiguracion', $adjuntos_especiales])
            ->innerJoin('sds_com_configuracion configuracion', 'mds_legales_archivo.tipo = configuracion.idconfiguracion')
            ->orderBy(['configuracion.descripcion' => SORT_ASC])->asArray()->all();
        foreach ($adjuntos as $key => $adjunto) {
            $adjuntos[$key]['path'] = self::PATH . $adjunto['path'];
        }
        return $adjuntos;
    }

    public function getOtrosAdjuntosBaja()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->select(['mds_legales_archivo.nombre', 'mds_legales_archivo.path', 'mds_legales_archivo.tipo', 'mds_legales_archivo.objeto'])
            ->where(
                [
                    'mds_legales_archivo.objeto' => 'mds_certificacion',
                    'mds_legales_archivo.activo' => true,
                    'mds_legales_archivo.id_objeto' => $this->idcertificacion,
                    'mds_legales_archivo.tipo' => Mds_certificacion_programa::ADJUNTO_BAJA,
                ]
            )->asArray()->all();
        foreach ($adjuntos as $key => $adjunto) {
            $adjuntos[$key]['path'] = self::PATH . $adjunto['path'];
        }
        return $adjuntos;
    }

    public function getOtrosAdjuntosObservada()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->select(['mds_legales_archivo.nombre', 'mds_legales_archivo.path', 'mds_legales_archivo.tipo', 'mds_legales_archivo.objeto'])
            ->where(
                [
                    'mds_legales_archivo.objeto' => 'mds_certificacion',
                    'mds_legales_archivo.activo' => true,
                    'mds_legales_archivo.id_objeto' => $this->idcertificacion,
                    'mds_legales_archivo.tipo' => Mds_certificacion_programa::ADJUNTO_OBSERVAR,
                ]
            )->asArray()->all();
        foreach ($adjuntos as $key => $adjunto) {
            $adjuntos[$key]['path'] = self::PATH . $adjunto['path'];
        }
        return $adjuntos;
    }

    public function getOtrosAdjuntosRechazada()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->select(['mds_legales_archivo.nombre', 'mds_legales_archivo.path', 'mds_legales_archivo.tipo', 'mds_legales_archivo.objeto'])
            ->where(
                [
                    'mds_legales_archivo.objeto' => 'mds_certificacion',
                    'mds_legales_archivo.activo' => true,
                    'mds_legales_archivo.id_objeto' => $this->idcertificacion,
                    'mds_legales_archivo.tipo' => Mds_certificacion_programa::ADJUNTO_RECHAZAR,
                ]
            )->asArray()->all();
        foreach ($adjuntos as $key => $adjunto) {
            $adjuntos[$key]['path'] = self::PATH . $adjunto['path'];
        }
        return $adjuntos;
    }

    public function getUsuarioAprueba0($idcertificacion)
    {
        $usuario =  Mds_certificacion::find()
            ->select(['concat(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) usuario '])
            ->where(['idcertificacion' => $idcertificacion])
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion.idusuario_aprueba')
            ->asArray()
            ->one();
        return $usuario ? $usuario['usuario'] : '';
    }
    public function getDirector($idcertificacion)
    {
        $director =  Mds_certificacion::find()
            ->select(['concat(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) usuario '])
            ->where(['idcertificacion' => $idcertificacion])
            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion = mds_certificacion.iddireccion')
            ->leftJoin('mds_certificacion_director', 'mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_director.idcertificaciondireccion')
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_director.idusuario')
            ->asArray()
            ->one();

        return $director ? $director['usuario'] : '';
    }

    public function getEstadoactual($idcertificacion)
    {
        $estado =  Mds_certificacion_estado::find()
            ->select(['concat(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) usuario ,mds_certificacion_estado.created_at'])
            ->where(['idcertificacion' => $idcertificacion, 'fecha_fin' => null])
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_estado.idusuario')
            //->orderBy(['mds_certificacion_estado.created_at' => SORT_DESC])
            ->asArray()
            ->one();
        return $estado ? $estado : '';
    }

    public function getUserUpdate()
    {
        $usuario =  Mds_certificacion_estado::find()
            ->select(['concat(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) usuario '])
            ->where(['idcertificacion' => $this->idcertificacion, 'fecha_fin' => null, 'deleted_at' => NULL])
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_estado.idusuario')
            ->asArray()
            ->one();
        return $usuario ? $usuario['usuario'] : '';
    }

    public function permissionUpdate($permissionUpdate, $area)
    {
        $permission = FALSE;

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        $hasRolSolicitante = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE);
        $idusuario = Yii::$app->user->identity->idusuario;

        $model_certificacion_direccion = Mds_certificacion_direccion::getDireccionUsuario($idusuario);
        $estadoActual = Mds_certificacion_estado::getEstadoactual($this->idcertificacion);
        $direccionActual = $estadoActual ? $estadoActual['iddireccion'] : null;
        $nivelActual = Mds_certificacion_direccion::getNivelActual($direccionActual);

        if ($hasRolSolicitante) {
            $model_certificacion_direccion[] = [
                'idcertificaciondireccion' => null
            ];
        }

        // $usuarioUpdate = $model_certificacion_direccion ?
        //     is_int(array_search($direccionActual, array_column($model_certificacion_direccion, 'idcertificaciondireccion'))) : true;
        $direccionesUsuario = array_column($model_certificacion_direccion, 'idcertificaciondireccion');
        $usuarioUpdate = in_array($direccionActual, $direccionesUsuario);

        $usuarioCarga = $this->idusuario_carga == $idusuario;
        $enAreaSolicitante = $area == Mds_certificacion::AREA_SOLICITANTE;
        $certificacionPendiente = $this->idestado == Mds_certificacion_estado::ESTADO_PENDIENTE;
        $certificacionObservada = $this->idestado == Mds_certificacion_estado::ESTADO_OBSERVADA;
        $enNivelActual = $area == $nivelActual;

        // Tengo permiso de modificar si
        // Tengo rol Administrador
        // Esta en estado Pendiente y soy Solicitante (solo el usuario que cargó)
        // Esta en estado observada y soy el nivel anterior al que lo observó 
        //(en el caso que el anterior sea Solicitante lo puede modificar el solicitante que lo cargo 
        // o cualquier solicitante perteneciente al area)
        if (
            $permissionUpdate
            &&
            ($hasRolAdminGeneral ||
                ($certificacionPendiente  && $usuarioCarga && $enAreaSolicitante) ||
                ($certificacionObservada && $usuarioUpdate && $enNivelActual)
            )
            &&
            is_null($this->deleted_at)
        ) {
            $permission = TRUE;
        }
        return $permission;
    }

    public function permissionDelete($permissionDelete)
    {
        $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);

        return (is_null($this->deleted_at)
            &&
            $permissionDelete
            &&
            ($this->idestado == $listadoPosiblesEstados['ESTADO_PENDIENTE'] || $hasRolAdminGeneral)
        );
    }

    public function permissionReactivate($permissionReactivate)
    {
        return (!is_null($this->deleted_at)
            &&
            $permissionReactivate
        );
    }

    public function permissionAprobar($permissionAutorizar)
    {
        $idusuario = Yii::$app->user->identity->idusuario;
        $estadoActual = Mds_certificacion_estado::getEstadoactual($this->idcertificacion);
        $direccionActual = $estadoActual ? $estadoActual['iddireccion'] : null;
        $model_certificacion_direccion = Mds_certificacion_direccion::getDireccionUsuario($idusuario);
        $direccionesUsuario = array_column($model_certificacion_direccion, 'idcertificaciondireccion');
        $usuarioAprobar = in_array($direccionActual, $direccionesUsuario);

        return ($usuarioAprobar
            &&
            $permissionAutorizar
            &&
            ($this->idestado == Mds_certificacion_estado::ESTADO_PENDIENTE
                || $this->idestado == Mds_certificacion_estado::ESTADO_APROBADA)
            &&
            is_null($this->deleted_at)
        );
    }

    // public function getEstadoActualNivel($idcertificacion, $idusuario, $nivel)
    // {
    //     $idestado = '';

    //     //Consulta el ultimo estado de la certificacion
    //     $estado =  Mds_certificacion_estado::find()
    //         ->select(['mds_certificacion_estado.*'])
    //         ->where(['idcertificacion' => $idcertificacion, 'fecha_fin' => NULL, 'deleted_at' => NULL])
    //         ->asArray()
    //         ->one();
    //     if ($estado) {
    //         $idestado = $estado['idestado'];
    //         //Si el último estado SI es baja No importa donde se ubique se mostrará así

    //         //Si el último estado NO es Baja y SI esta en un nivel entonces consultará el estado con el que se creo en ese nivel
    //         if ($idestado != Mds_certificacion_estado::ESTADO_BAJA && $nivel) {
    //             $estado =  Mds_certificacion_estado::find()
    //                 ->select(['mds_certificacion_estado.*'])
    //                 //->where(['idcertificacion' => $idcertificacion, 'idusuario' => $idusuario])
    //                 ->where(['idcertificacion' => $idcertificacion, 'deleted_at' => NULL])
    //                 ->orderBy('idcertificacionestado DESC')
    //                 ->limit(1)
    //                 ->asArray()
    //                 ->one();
    //             if ($estado) {
    //                 $idestado = $estado['idestado'];
    //             } else {
    //                 $idestado = '';
    //             }
    //         }
    //     }
    //     return $idestado;
    // }

    public function colorCertificacionSegunNivel($idnivelUser)
    {
        $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;
        $situacion = '';
        $idestado = null;

        if (
            is_null($idnivelUser)
            || $this->idestado == mds_certificacion_estado::ESTADO_ENVIADA
            || $this->idestado == mds_certificacion_estado::ESTADO_BAJA
            || $this->idestado == mds_certificacion_estado::ESTADO_ELIMINADA
            || $this->idestado == mds_certificacion_estado::ESTADO_RECHAZADA
        ) {
            $idestado = $this->idestado;
        } else {

            $estados =  Mds_certificacion_estado::find()
                ->select(['mds_certificacion_estado.*', 'mds_certificacion_direccion.idnivelautorizacion'])
                ->where(['mds_certificacion_estado.idcertificacion' => $this->idcertificacion, 'mds_certificacion_estado.deleted_at' => NULL])
                ->leftJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
                ->orderBy(['mds_certificacion_estado.idcertificacionestado' => SORT_DESC])
                ->asArray()
                ->all();

            $arrayEstadoConNivel = array_column($estados, 'idnivelautorizacion');
            $key = array_search($idnivelUser, $arrayEstadoConNivel);
            if ($key > 0) {
                $idestado = $estados[$key - 1]['idestado'];
            } else {
                $idestado = $this->idestado;
            }
        }

        switch ($idestado):
            case $listadoPosiblesEstados['ESTADO_APROBADA']:
                $situacion = 'aprobada';
                break;
            case $listadoPosiblesEstados['ESTADO_OBSERVADA']:
                $situacion = 'observada';
                break;
            case $listadoPosiblesEstados['ESTADO_RECHAZADA']:
                $situacion = 'rechazada';
                break;
            case $listadoPosiblesEstados['ESTADO_BAJA']:
                $situacion = 'baja';
                break;
            case $listadoPosiblesEstados['ESTADO_ENVIADA']:
                $situacion = 'enviada';
                break;
            case $listadoPosiblesEstados['ESTADO_PENDIENTE']:
                $situacion = '';
                break;
            default:
                $situacion = '';
        endswitch;

        return $situacion;
    }

    public static function getCertificacionById($idcertificacion)
    {
        $certificacion = Mds_certificacion::find()
            ->select([
                '*,
                sds_com_localidad.descripcion as localidadDescripcion,
                UPPER (CONCAT(sds_com_persona.apellido," ",sds_com_persona.nombre, " ", \'(\', sds_com_persona.documento, \')\') )as beneficiario,
                configuracion_tipoJubilacion.descripcion as tipoJubilacion,
                UPPER(mds_certificacion_responsable.nombre_apellido) as responsable,
                mds_certificacion_responsable.dni as responsable_dni,
                SUBSTRING_INDEX(configuracion_parentesco.descripcion, ".", -1) as parentescoResponsable,
                configuracion_tipoResponsable.descripcion as tipoResponsable,
                DATE_FORMAT(mds_certificacion.periodo_desde,"%d/%m/%Y") as fecha_desde,
                DATE_FORMAT(mds_certificacion.periodo_hasta,"%d/%m/%Y") as fecha_hasta,
                configuracion_area.descripcion as area,
                configuracion_programa.descripcion as programa,
                configuracion_nivel.descripcion as nivelAutorizacion,
                configuracion_direccion.descripcion as direccion,
                configuracion_caracter.descripcion as caracter,
                configuracion_organismo_solicitante.descripcion as organismoSolicitante,
                configuracion_estado.descripcion as estado,
                CONCAT(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) as usuario 
                '
            ])

            ->where(['mds_certificacion.idcertificacion' => $idcertificacion])
            ->innerJoin('sds_com_persona', 'mds_certificacion.idbeneficiario = sds_com_persona.idpersona')
            ->leftJoin('sds_com_localidad', 'mds_certificacion.idlocalidad = sds_com_localidad.idlocalidad')
            ->leftJoin('sds_com_configuracion configuracion_tipoJubilacion', 'mds_certificacion.tipo_jubilacion = configuracion_tipoJubilacion.idconfiguracion')
            ->innerJoin('mds_certificacion_responsable', 'mds_certificacion.idcertificacion = mds_certificacion_responsable.idcertificacion')
            ->leftJoin('sds_com_configuracion configuracion_tipoResponsable', 'mds_certificacion_responsable.tipo_responsable = configuracion_tipoResponsable.idconfiguracion')
            ->leftJoin('sds_com_configuracion configuracion_parentesco', 'mds_certificacion_responsable.idparentesco = configuracion_parentesco.idconfiguracion')
            ->innerJoin('mds_certificacion_direccion area', 'mds_certificacion.idarea = area.idcertificaciondireccion')
            ->innerJoin('sds_com_configuracion configuracion_area', 'area.iddireccion = configuracion_area.idconfiguracion')
            ->innerJoin('sds_com_configuracion configuracion_programa', 'mds_certificacion.idprograma = configuracion_programa.idconfiguracion')
            ->innerJoin('sds_com_configuracion configuracion_nivel', 'mds_certificacion.idnivel_autorizacion = configuracion_nivel.idconfiguracion')
            //->innerJoin('sds_com_configuracion configuracion_direccion', 'mds_certificacion.iddireccion = configuracion_direccion.idconfiguracion')
            ->innerJoin('mds_certificacion_monto', 'mds_certificacion.idcertificacion = mds_certificacion_monto.idcertificacion')
            ->innerJoin('sds_com_configuracion configuracion_caracter', 'mds_certificacion.idcaracter = configuracion_caracter.idconfiguracion')
            ->leftJoin('sds_com_configuracion configuracion_organismo_solicitante', 'mds_certificacion.idorganismo_solicitante = configuracion_organismo_solicitante.idconfiguracion')
            ->innerJoin('sds_com_configuracion configuracion_estado', 'mds_certificacion.idestado = configuracion_estado.idconfiguracion')
            ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion')

            ->innerJoin('mds_certificacion_direccion direccion', 'mds_certificacion_estado.iddireccion = direccion.idcertificaciondireccion')
            ->innerJoin('sds_com_configuracion configuracion_direccion', 'direccion.iddireccion = configuracion_direccion.idconfiguracion')

            ->leftJoin(
                'mds_legales_archivo',
                'mds_certificacion.idcertificacion = mds_legales_archivo.id_objeto 
                    AND mds_legales_archivo.activo IS TRUE 
                    AND mds_legales_archivo.objeto = "mds_certificacion"
                    AND mds_legales_archivo.tipo != "adjunto_baja"'
            )
            ->innerJoin('mds_seg_usuario', 'mds_certificacion_estado.idusuario = mds_seg_usuario.idusuario')
            ->andWhere(
                [
                    'mds_certificacion_responsable.deleted_at' => null,
                    'mds_certificacion_monto.deleted_at' => null
                ]
            )
            ->asArray()
            ->one();

        return $certificacion;
    }

    public function getDireccionPrevia()
    {
        $estadoActual =  Mds_certificacion_estado::find()
            ->select(['mds_certificacion_estado.idestado', 'mds_certificacion_estado.idcertificacionestado', 'mds_certificacion_direccion.idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) AS descripcionDireccion'])
            ->where(['idcertificacion' => $this->idcertificacion, 'fecha_fin' => null, 'mds_certificacion_estado.deleted_at' => NULL])
            ->leftJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
            ->leftJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
            ->asArray()
            ->one();

        if ($estadoActual['idestado'] == Mds_certificacion_estado::ESTADO_BAJA || $estadoActual['idestado'] == Mds_certificacion_estado::ESTADO_RECHAZADA || $estadoActual['idestado'] == Mds_certificacion_estado::ESTADO_ELIMINADA) {
            $estadoPrevio = $estadoActual;
            if ($estadoActual['idestado'] == Mds_certificacion_estado::ESTADO_BAJA &&  $estadoActual['descripcionDireccion'] == '') {
                $estadoPrevio['descripcionDireccion'] = 'SOLICITANTE';
            }
            if ($estadoActual['idestado'] == Mds_certificacion_estado::ESTADO_ELIMINADA &&  $estadoActual['descripcionDireccion'] == '') {
                $estadoPrevio['descripcionDireccion'] = '';
            }
        } else {
            $estadoPrevio =  Mds_certificacion_estado::find()
                ->select(['mds_certificacion_estado.idcertificacionestado', 'mds_certificacion_direccion.idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) AS descripcionDireccion'])
                ->where(['mds_certificacion_estado.idcertificacion' => $this->idcertificacion, 'mds_certificacion_estado.deleted_at' => NULL])
                ->andWhere(['<>', 'mds_certificacion_estado.fecha_fin', ''])
                ->leftJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
                ->leftJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
                ->orderBy(['idcertificacionestado' => SORT_DESC])
                ->asArray()
                ->one();
        }
        return $estadoPrevio ? ($estadoPrevio['descripcionDireccion'] != '' ? $estadoPrevio['descripcionDireccion'] : 'Solicitante')  : 'Solicitante';
    }

    static function getDireccionEstadoAnterior($idcertificacion, $iddireccionActual)
    {
        $circuitoDirecciones = getCircuitoDirecciones($idcertificacion);

        $iddireccionAnterior = NULL;
        foreach ($circuitoDirecciones as $key => $direccion) {
            if ($direccion == $iddireccionActual) {
                if ($key != 0) {
                    $iddireccionAnterior = $circuitoDirecciones[$key - 1];
                }
            }
        }

        return $iddireccionAnterior;
    }

    static function getDireccionEstadoSiguente($idcertificacion, $iddireccionActual)
    {
        $circuitoDirecciones = getCircuitoDirecciones($idcertificacion);

        if ($iddireccionActual) {
            $iddireccionSiguiente = NULL;
            foreach ($circuitoDirecciones as $key => $direccion) {
                if ($direccion == $iddireccionActual) {
                    $iddireccionSiguiente = $circuitoDirecciones[$key + 1];
                }
            }
        } else {
            $iddireccionSiguiente = $circuitoDirecciones[0];
        }
        return $iddireccionSiguiente;
    }

    static function getCertificacionesByDNI($dni)
    {
        $certificaciones = Mds_certificacion::find()
            ->select([
                'idcertificacion',
                'DATE_FORMAT(mds_certificacion.periodo_desde,"%d/%m/%Y") as fecha_desde',
                'DATE_FORMAT(mds_certificacion.periodo_hasta,"%d/%m/%Y") as fecha_hasta'
            ])
            ->where(['sds_com_persona.documento' => $dni, 'mds_certificacion.deleted_at' => null])
            ->innerJoin('sds_com_persona', 'sds_com_persona.idpersona = mds_certificacion.idbeneficiario')
            ->asArray()
            ->all();
        return $certificaciones;
    }

    public function getVistos()
    {
        return $this->hasMany(Mds_certificacion_visto::class, ['idcertificacion' => 'idcertificacion'])->where(['activo' => 1])->orderBy([
            'idcertificacionvisto' => SORT_DESC
        ]);
    }
}

function getCircuitoDirecciones($idcertificacion)
{
    $circuitoDirecciones = [];
    $seguir = true;

    $estadoInicial =  Mds_certificacion_estado::find()
        ->select(['mds_certificacion_estado.iddireccion'])
        ->where(['idcertificacion' => $idcertificacion, 'deleted_at' => NULL])
        ->asArray()
        ->one();

    $iddireccion = $estadoInicial['iddireccion'];
    array_push($circuitoDirecciones, $iddireccion);


    $direccion = Mds_certificacion_direccion::find()->where(['idcertificaciondireccion' => $iddireccion, 'deleted_at' => null])->one();


    $arrayidniveles = Mds_certificacion::ID_NIVELES_CERTIFICACIONES;

    $key = array_search($direccion->idnivelautorizacion, $arrayidniveles);
    $key = $key + 1;
    $arrayidnivel = array_slice($arrayidniveles, $key);
    $idnivelAutorizacionSiguiente = $arrayidnivel[0];
    $iddireccionSiguiente = $direccion->iddireccion_padre;

    while ($seguir) {
        $direccion = Mds_certificacion_direccion::find()->where(['iddireccion' => $iddireccionSiguiente, 'idnivelautorizacion' => $idnivelAutorizacionSiguiente, 'deleted_at' => null])->one();
        $seguir = $direccion ? true : false;

        if ($seguir) {
            array_push($circuitoDirecciones, $direccion->idcertificaciondireccion);

            $iddireccionSiguiente = $direccion->iddireccion_padre;
            $key = array_search($direccion->idnivelautorizacion, $arrayidniveles);
            $key = $key + 1;
            $arrayidnivel = array_slice($arrayidniveles, $key);
            if (count($arrayidnivel) > 0) {
                $idnivelAutorizacionSiguiente = $arrayidnivel[0];
            } else {
                $seguir = false;
            }
        }
    }

    return $circuitoDirecciones;
}
