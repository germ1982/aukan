<?php


namespace app\models;

use app\models\Mds_legales_oficio;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class Mds_legales_oficioSearch extends Mds_legales_oficio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'idlegalesoficio', 'estado', 'area', 'idarea', 'juicio', 'lugar_libramiento', 'caso', 'tipo_oficio',
                'numero_expediente', 'caratula', 'remitente', 'anio_expediente', 'fecha_plazo', 'fecha_recepcion',
                'supervisores', 'generadoresRespuesta', 'respuestasGeneradas', 'respuestasEnviadas', 'respuestaPendienteVistos',
                'fechaAprobado', 'activo',
            ], 'safe'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $idArea = null, $idUsuario = null, $fechaInicio = null, $fechaFin = null, $idEstado = null, $tipo = null, $idLegalesCaratula = null, $arrayIdRequerimientos = null)
    {
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
        $hasRolSupervisorArea = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);
        $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $idEstadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;

        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_legales_oficio::find()->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC]],
        ]);
        $this->load($params);

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
            $fecha_plazo = armarDateParaMySql($fecha_plazo);
            $fecha_plazo = date_create($fecha_plazo);
            $fecha_plazo = date_format($fecha_plazo, 'Y-m-d');
            $this->fecha_plazo = $fecha_plazo;
        }

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
            $fecha_recepcion = armarDateParaMySql($fecha_recepcion);
            $fecha_recepcion = date_create($fecha_recepcion);
            $fecha_recepcion = date_format($fecha_recepcion, 'Y-m-d');
            $this->fecha_recepcion = $fecha_recepcion;
        }

        $query->innerJoin('mds_legales_derivacion', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion.idlegalesoficio');
        $query->leftJoin('mds_legales_caratula', 'mds_legales_oficio.idlegalescaratula = mds_legales_caratula.idlegalescaratula');

        $where['mds_legales_oficio.idlegalesoficio'] = $this->idlegalesoficio;
        if (!$hasRolAdminGeneral) {
            $where['mds_legales_oficio.activo'] = 1;
        }

        if ($idArea) {
            $where['mds_legales_oficio.idarea'] = $idArea;
        } else if ($idUsuario) {
            $where['mds_legales_derivacion.idusuario'] = $idUsuario;
            $where['mds_legales_derivacion.supervisor'] = 1;
            $where['mds_legales_derivacion.activo'] = 1;
            $where['mds_legales_derivacion.fecha_usu_no_corresponde'] = null;
            $where['mds_legales_derivacion.re_derivado'] = 0;
        }
        
        if ($idLegalesCaratula) {
            $where['mds_legales_oficio.idlegalescaratula'] = $idLegalesCaratula;
        }

        $query->filterWhere($where)
            ->andFilterWhere(['in', 'tipo_oficio', $this->tipo_oficio])
            ->andFilterWhere(['in', 'idarea', $this->idarea])
            ->andFilterWhere(['in', 'mds_legales_oficio.idlegalescaratula', $this->caratula])
            ->andFilterWhere(['mds_legales_caratula.caso' => $this->caso])
            ->andFilterWhere(['like', 'lugar_libramiento', $this->lugar_libramiento])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'juicio', $this->juicio])
            ->andFilterWhere(['mds_legales_caratula.anio_expediente' => $this->anio_expediente])
            ->andFilterWhere(['mds_legales_caratula.numero_expediente' => $this->numero_expediente]);

        if (is_array($arrayIdRequerimientos)) {
            $condition = !empty($arrayIdRequerimientos)
                ? ['in', 'mds_legales_oficio.idlegalesoficio', $arrayIdRequerimientos]
                : ['in', 'mds_legales_oficio.idlegalesoficio', [null]];
            $query->andFilterWhere($condition);
        }

        if ($fechaInicio || $fechaFin) {
            if ($fechaFin) {
                $fechaFin = date_create($fechaFin);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga = "mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga = "mds_legales_oficio.fecha_carga >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga = "mds_legales_oficio.fecha_carga <= '$fechaFin'";
            }
            $query->andWhere($whereFechaCarga);
        }

        if ($idEstado || $tipo == "pendiente_supervision_final") {
            if ($tipo == "pendiente_supervision_final") {
                $idEstado = $idEstadoAprobado;
            }
            $query->innerJoin('mds_legales_respuesta', 'mds_legales_oficio.idlegalesoficio = mds_legales_respuesta.idlegalesoficio');
            $query->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta');
            $whereEstado = "mds_legales_respuesta_estado.estado = $idEstado";
            if ($idEstado == $idEstadoPendiente || $tipo == "pendiente_supervision_final") {
                $whereInEstado = "$idEstadoEnviado, $idEstadoRechazado, $idEstadoObservada";
                if ($idEstado == $idEstadoPendiente) {
                    $whereInEstado .= ", $idEstadoAprobado";
                }
                $whereEstado .= " AND mds_legales_respuesta_estado.idlegalesrespuesta NOT IN 
                (SELECT mds_legales_respuesta_estado.idlegalesrespuesta 
                FROM mds_legales_respuesta_estado
                WHERE mds_legales_respuesta_estado.estado IN ($whereInEstado)
                )";
            }
            $query->andWhere($whereEstado);
        }

        if ($tipo == 'sin_respuesta') {
            $query->leftJoin('mds_legales_respuesta as respuesta_sin_respuesta', 'mds_legales_oficio.idlegalesoficio = respuesta_sin_respuesta.idlegalesoficio');
            $whereSinRespuesta = 'respuesta_sin_respuesta.idlegalesrespuesta IS NULL';
            $query->andWhere($whereSinRespuesta);
        } else if ($tipo == 'devueltos_sin_rectificacion') {
            $requerimientosDevueltos = Mds_legales_oficio::getRequerimientosDevueltosSupervisionFinal($fechaInicio, $fechaFin);
            $requerimientosDevueltosId = array();
            foreach ($requerimientosDevueltos as $requerimiento) {
                array_push($requerimientosDevueltosId, $requerimiento['idlegalesoficio']);
            }

            if (empty($requerimientosDevueltosId)) {
                $requerimientosDevueltosId = 0;
            }

            $query->andFilterWhere(['in', 'mds_legales_oficio.idlegalesoficio', $requerimientosDevueltosId]);
        }

        /* El rol supervisor general puede ver todos|no filtra*/
        if (!$hasRolSupervisorGeneral) {
            /*Si es supervisor*/
            // Se comenta para que busque los oficios que fueron derivados al usuario, sin importar si es supervisor o no
            // if(Mds_legales_oficio::tieneRol(82)){
            //     $query->andWhere(['mds_legales_derivacion.supervisor'=>1]);
            // }
            if ($hasRolSupervisorArea) {
                $idDispositivo = Mds_org_contacto::findOne($usuarioAuth->idcontacto)->iddispositivo;
                if ($idDispositivo) {
                    $query->innerJoin('mds_legales_derivacion_area', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion_area.idoficio');
                    $query->andWhere(['mds_legales_derivacion_area.iddispositivo' => $idDispositivo]);
                }
            }
            /*Muestra solo los oficios cargados por dicho usuario*/
            // if (Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO)) {
            // $query->andWhere(['mds_legales_oficio.idusuario'=>$usuarioAuth->idusuario]);
            // }
        }

        $query->andFilterWhere(['<=', 'fecha_plazo', $this->fecha_plazo]);
        $query->andFilterWhere(['<=', 'fecha_recepcion', $this->fecha_recepcion]);


        $this->fecha_plazo ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_plazo))) :  null;
        $this->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_recepcion))) :  null;

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $this->fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
        }

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $this->fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
        }

        if ($this->generadoresRespuesta) {
            if (in_array('SIN_VALOR', $this->generadoresRespuesta)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_generador_respuesta.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 0 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->innerJoin('mds_seg_usuario as mds_seg_usuario_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idusuario = mds_seg_usuario_generador_respuesta.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.supervisor' => 0
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion_generador_respuesta.idlegalesoficio IN (SELECT derivacion3.idlegalesoficio from mds_legales_derivacion as derivacion3 WHERE derivacion3.idusuario = $usuarioAuth->idusuario
                    AND derivacion3.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion3.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion3.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.idusuario' => $this->generadoresRespuesta
                ]);
            }
        }

        if ($this->supervisores) {
            if (in_array('SIN_VALOR', $this->supervisores)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_supervisor', 'mds_legales_derivacion_supervisor.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_supervisor.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 1 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_seg_usuario', 'mds_legales_derivacion.idusuario = mds_seg_usuario.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion.supervisor' => 1
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion.idlegalesoficio IN (SELECT derivacion2.idlegalesoficio from mds_legales_derivacion as derivacion2 WHERE derivacion2.idusuario = $usuarioAuth->idusuario
                    AND derivacion2.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion2.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion2.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion.idusuario' => $this->supervisores
                ]);
            }
        } else if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
            /*si es receptor o supervisor filtra por usuario*/
            if (!Yii::$app->request->queryParams || !isset(Yii::$app->request->queryParams['sort'])) {
                $query->orderBy(['mds_legales_derivacion.idlegalesderivacion' => SORT_DESC]);
            }
            $query->andFilterWhere(['mds_legales_derivacion.activo' => 1]);

            $conditionSupervisor = "";

            if ($hasRolReceptor && !$hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 0";
            } else if (!$hasRolReceptor && $hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 1";
            }

            $where = "mds_legales_derivacion.idusuario = $usuarioAuth->idusuario $conditionSupervisor";
            $query->andWhere($where);
        }

        if ($this->activo === '0') {
            $query->andWhere(['mds_legales_oficio.activo' => 0]);
        } else if ($this->activo === '1') {
            $query->andWhere(['mds_legales_oficio.activo' => 1]);
        }

        $existeFiltroRespuestasGeneradas = $this->respuestasGeneradas === '0' || $this->respuestasGeneradas;
        $existeFiltroRespuestasEnviadas = $this->respuestasEnviadas === '0' || $this->respuestasEnviadas;
        $existeFiltroRespuestaPendienteVistos = $this->respuestaPendienteVistos === '0' || $this->respuestaPendienteVistos;

        if ($existeFiltroRespuestasGeneradas || $existeFiltroRespuestasEnviadas || $existeFiltroRespuestaPendienteVistos) {
            $filtered_models = array();
            $filtered_key = array();
            foreach ($query->orderBy(['mds_legales_oficio.idlegalesoficio' => SORT_DESC])->all() as $oficio) {

                $condition = true;

                if ($existeFiltroRespuestasGeneradas) {
                    $totalRespuestasGeneradas = $oficio->getTotalRespuestasGeneradas();
                    $condition = $totalRespuestasGeneradas == $this->respuestasGeneradas;
                }

                if ($existeFiltroRespuestasEnviadas) {
                    $totalRespuestasEnviadas = count($oficio->getRespuestasAprobadas());
                    $condition = $condition && $totalRespuestasEnviadas == $this->respuestasEnviadas;
                }

                if ($existeFiltroRespuestaPendienteVistos) {
                    $totalRespuestaPendienteVistos = $oficio->getTotalRespuestaPendienteVistos();
                    $condition = $condition && $totalRespuestaPendienteVistos == $this->respuestaPendienteVistos;
                }

                if ($condition) {
                    $filtered_key[] = $oficio->idlegalesoficio;
                    $filtered_models[] = $oficio;
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $filtered_models,
            ]);
        }

        return $dataProvider;
    }

    public function searchVinculacion($params)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_legales_oficio::find();

        $this->load($params);
        $query->innerJoin('mds_legales_respuesta', 'mds_legales_oficio.idlegalesoficio = mds_legales_respuesta.idlegalesoficio');
        $query->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta');
        $query->leftJoin('mds_legales_caratula', 'mds_legales_oficio.idlegalescaratula = mds_legales_caratula.idlegalescaratula');
        $query->filterWhere([
            'mds_legales_oficio.idlegalesoficio' => $this->idlegalesoficio,
            'activo' => 1,
            'entregado' => 1,
        ])
            ->andFilterWhere(['in', 'tipo_oficio', $this->tipo_oficio])
            ->andFilterWhere(['in', 'idarea', $this->idarea])
            ->andFilterWhere(['in', 'mds_legales_oficio.idlegalescaratula', $this->caratula])
            ->andFilterWhere(['mds_legales_caratula.caso' => $this->caso])
            ->andFilterWhere(['like', 'lugar_libramiento', $this->lugar_libramiento])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'juicio', $this->juicio])
            ->andFilterWhere(['mds_legales_caratula.anio_expediente' => $this->anio_expediente])
            ->andFilterWhere(['mds_legales_caratula.numero_expediente' => $this->numero_expediente])
            ->groupBy('mds_legales_oficio.idlegalesoficio');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC]],
        ]);

        return $dataProvider;
    }

    public function searchRespuestasParaEnviar($params)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_legales_oficio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC], 'attributes' => ['idlegalesoficio', 'idarea', 'caso', 'lugar_libramiento', 'caratula', 'tipo_oficio', 'numero_expediente', 'anio_expediente', 'fecha_inicio']],
        ]);
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        $this->load($params);

        if ($this->fechaAprobado) {
            $fechaAprobadoAux = $this->fechaAprobado;
            $fechaAprobado = $this->fechaAprobado;
            $fechaAprobado = armarDateParaMySql($fechaAprobado);
            $fechaAprobado = date_create($fechaAprobado);
            $fechaAprobado = $fechaAprobado->modify('+1 day');
            $fechaAprobado = date_format($fechaAprobado, 'Y-m-d');
            $this->fechaAprobado = $fechaAprobado;
        }

        $query->innerJoin('mds_legales_respuesta', 'mds_legales_oficio.idlegalesoficio = mds_legales_respuesta.idlegalesoficio');
        $query->innerJoin('mds_legales_respuesta_estado', 'mds_legales_respuesta.idlegalesrespuesta = mds_legales_respuesta_estado.idlegalesrespuesta');
        $query->leftJoin('mds_legales_caratula', 'mds_legales_oficio.idlegalescaratula = mds_legales_caratula.idlegalescaratula');

        $query->filterWhere([
            'mds_legales_oficio.idlegalesoficio' => $this->idlegalesoficio,
            'activo' => 1,
            'mds_legales_respuesta_estado.estado' => $idEstadoAprobado,
        ])
            ->andFilterWhere(['in', 'tipo_oficio', $this->tipo_oficio])
            ->andFilterWhere(['in', 'idarea', $this->idarea])
            ->andWhere("mds_legales_respuesta_estado.idlegalesrespuesta NOT IN (select e.idlegalesrespuesta from mds_legales_respuesta_estado e where e.estado IN ({$idEstadoEnviado},{$idEstadoObservada},{$idEstadoRechazado}))")
            ->andFilterWhere(['in', 'mds_legales_oficio.idlegalescaratula', $this->caratula])
            ->andFilterWhere(['mds_legales_caratula.caso' => $this->caso])
            ->andFilterWhere(['like', 'lugar_libramiento', $this->lugar_libramiento])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'juicio', $this->juicio])
            ->andFilterWhere(['mds_legales_caratula.anio_expediente' => $this->anio_expediente])
            ->andFilterWhere(['mds_legales_caratula.numero_expediente' => $this->numero_expediente])
            ->groupBy('mds_legales_oficio.idlegalesoficio');

        $query->andFilterWhere(['<=', 'fecha_inicio', $this->fechaAprobado]);
        $this->fechaAprobado ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fechaAprobado))) :  null;

        if ($this->fechaAprobado) {
            $this->fechaAprobado = $fechaAprobadoAux;
        }

        return $dataProvider;
    }

    public function searchRequerimientosVencidos($params, $fechaInicio, $fechaFin)
    {
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
        $hasRolSupervisorArea = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);
        $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_legales_oficio::find()->distinct();
        $date = date('Y-m-d');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC]],
        ]);
        $this->load($params);

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
            $fecha_recepcion = armarDateParaMySql($fecha_recepcion);
            $fecha_recepcion = date_create($fecha_recepcion);
            $fecha_recepcion = date_format($fecha_recepcion, 'Y-m-d');
            $this->fecha_recepcion = $fecha_recepcion;
        }

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
            $fecha_plazo = armarDateParaMySql($fecha_plazo);
            $fecha_plazo = date_create($fecha_plazo);
            $fecha_plazo = date_format($fecha_plazo, 'Y-m-d');
            $this->fecha_plazo = $fecha_plazo;
        }

        $query->innerJoin('mds_legales_derivacion', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion.idlegalesoficio');
        $query->leftJoin('mds_legales_caratula', 'mds_legales_oficio.idlegalescaratula = mds_legales_caratula.idlegalescaratula');

        $where['mds_legales_oficio.idlegalesoficio'] = $this->idlegalesoficio;
        if (!$hasRolAdminGeneral) {
            $where['mds_legales_oficio.activo'] = 1;
        }

        $query->filterWhere($where)
            ->andFilterWhere(['in', 'tipo_oficio', $this->tipo_oficio])
            ->andFilterWhere(['in', 'idarea', $this->idarea])
            ->andWhere("mds_legales_oficio.fecha_plazo < '{$date}'") //Esta vencida
            ->andWhere("
            mds_legales_oficio.idlegalesoficio NOT IN 
                (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
                )
            ") //No tiene respuestas enviadas
            ->andFilterWhere(['in', 'mds_legales_oficio.idlegalescaratula', $this->caratula])
            ->andFilterWhere(['mds_legales_caratula.caso' => $this->caso])
            ->andFilterWhere(['like', 'lugar_libramiento', $this->lugar_libramiento])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'juicio', $this->juicio])
            ->andFilterWhere(['mds_legales_caratula.anio_expediente' => $this->anio_expediente])
            ->andFilterWhere(['mds_legales_caratula.numero_expediente' => $this->numero_expediente]);

        $whereFechaCarga = '';
        if ($fechaFin) {
            $fechaFin = date_create($fechaFin);
            $fechaFin = $fechaFin->modify('+1 day');
            $fechaFin = date_format($fechaFin, 'Y-m-d');
        }
        if ($fechaInicio && $fechaFin) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }
        $query->andWhere($whereFechaCarga);


        /* El rol supervisor general puede ver todos|no filtra*/
        if (!$hasRolSupervisorGeneral) {
            /*Si es supervisor*/
            // Se comenta para que busque los oficios que fueron derivados al usuario, sin importar si es supervisor o no
            // if(Mds_legales_oficio::tieneRol(82)){
            //     $query->andWhere(['mds_legales_derivacion.supervisor'=>1]);
            // }
            if ($hasRolSupervisorArea) {
                $idDispositivo = Mds_org_contacto::findOne($usuarioAuth->idcontacto)->iddispositivo;
                if ($idDispositivo) {
                    $query->innerJoin('mds_legales_derivacion_area', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion_area.idoficio');
                    $query->andWhere(['mds_legales_derivacion_area.iddispositivo' => $idDispositivo]);
                }
            }
            /*Muestra solo los oficios cargados por dicho usuario*/
            // if (Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO)) {
            // $query->andWhere(['mds_legales_oficio.idusuario'=>$usuarioAuth->idusuario]);
            // }
        }

        $query->andFilterWhere(['<=', 'fecha_plazo', $this->fecha_plazo]);
        $query->andFilterWhere(['<=', 'fecha_recepcion', $this->fecha_recepcion]);


        $this->fecha_plazo ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_plazo))) :  null;
        $this->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_recepcion))) :  null;


        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $this->fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
        }
        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $this->fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
        }

        if ($this->generadoresRespuesta) {
            if (in_array('SIN_VALOR', $this->generadoresRespuesta)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_generador_respuesta.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 0 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->innerJoin('mds_seg_usuario as mds_seg_usuario_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idusuario = mds_seg_usuario_generador_respuesta.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.supervisor' => 0
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion_generador_respuesta.idlegalesoficio IN (SELECT derivacion3.idlegalesoficio from mds_legales_derivacion as derivacion3 WHERE derivacion3.idusuario = $usuarioAuth->idusuario
                    AND derivacion3.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion3.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion3.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.idusuario' => $this->generadoresRespuesta
                ]);
            }
        }

        if ($this->supervisores) {
            if (in_array('SIN_VALOR', $this->supervisores)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_supervisor', 'mds_legales_derivacion_supervisor.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_supervisor.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 1 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_seg_usuario', 'mds_legales_derivacion.idusuario = mds_seg_usuario.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion.supervisor' => 1
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion.idlegalesoficio IN (SELECT derivacion2.idlegalesoficio from mds_legales_derivacion as derivacion2 WHERE derivacion2.idusuario = $usuarioAuth->idusuario
                    AND derivacion2.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion2.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion2.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion.idusuario' => $this->supervisores
                ]);
            }
        } else if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {

            /*si es receptor o supervisor filtra por usuario*/
            if (!Yii::$app->request->queryParams || !isset(Yii::$app->request->queryParams['sort'])) {
                $query->orderBy(['mds_legales_derivacion.idlegalesderivacion' => SORT_DESC]);
            }
            $query->andFilterWhere(['mds_legales_derivacion.activo' => 1]);

            $conditionSupervisor = "";

            if ($hasRolReceptor && !$hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 0";
            } else if (!$hasRolReceptor && $hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 1";
            }

            $where = "mds_legales_derivacion.idusuario = $usuarioAuth->idusuario $conditionSupervisor";
            $query->andWhere($where);
        }

        if ($this->activo === '0') {
            $query->andWhere(['mds_legales_oficio.activo' => 0]);
        } else if ($this->activo === '1') {
            $query->andWhere(['mds_legales_oficio.activo' => 1]);
        }

        $existeFiltroRespuestasGeneradas = $this->respuestasGeneradas === '0' || $this->respuestasGeneradas;
        $existeFiltroRespuestasEnviadas = $this->respuestasEnviadas === '0' || $this->respuestasEnviadas;
        $existeFiltroRespuestaPendienteVistos = $this->respuestaPendienteVistos === '0' || $this->respuestaPendienteVistos;

        if ($existeFiltroRespuestasGeneradas || $existeFiltroRespuestasEnviadas || $existeFiltroRespuestaPendienteVistos) {
            $filtered_models = array();
            $filtered_key = array();
            foreach ($query->orderBy(['mds_legales_oficio.idlegalesoficio' => SORT_DESC])->all() as $oficio) {

                $condition = true;

                if ($existeFiltroRespuestasGeneradas) {
                    $totalRespuestasGeneradas = $oficio->getTotalRespuestasGeneradas();
                    $condition = $totalRespuestasGeneradas == $this->respuestasGeneradas;
                }

                if ($existeFiltroRespuestasEnviadas) {
                    $totalRespuestasEnviadas = count($oficio->getRespuestasAprobadas());
                    $condition = $condition && $totalRespuestasEnviadas == $this->respuestasEnviadas;
                }

                if ($existeFiltroRespuestaPendienteVistos) {
                    $totalRespuestaPendienteVistos = $oficio->getTotalRespuestaPendienteVistos();
                    $condition = $condition && $totalRespuestaPendienteVistos == $this->respuestaPendienteVistos;
                }

                if ($condition) {
                    $filtered_key[] = $oficio->idlegalesoficio;
                    $filtered_models[] = $oficio;
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $filtered_models,
            ]);
        }
        return $dataProvider;
    }

    public function searchRespuestasconobservaciones($params, $fechaInicio, $fechaFin)
    {
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
        $hasRolSupervisorArea = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);
        $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);

        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_legales_oficio::find()->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC]],
        ]);
        $this->load($params);

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
            $fecha_recepcion = armarDateParaMySql($fecha_recepcion);
            $fecha_recepcion = date_create($fecha_recepcion);
            $fecha_recepcion = date_format($fecha_recepcion, 'Y-m-d');
            $this->fecha_recepcion = $fecha_recepcion;
        }

        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
            $fecha_plazo = armarDateParaMySql($fecha_plazo);
            $fecha_plazo = date_create($fecha_plazo);
            $fecha_plazo = date_format($fecha_plazo, 'Y-m-d');
            $this->fecha_plazo = $fecha_plazo;
        }

        $query->innerJoin('mds_legales_derivacion', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion.idlegalesoficio');
        $query->innerJoin('mds_legales_respuesta', 'mds_legales_oficio.idlegalesoficio = mds_legales_respuesta.idlegalesoficio');
        $query->leftJoin('mds_legales_caratula', 'mds_legales_oficio.idlegalescaratula = mds_legales_caratula.idlegalescaratula');

        $where['mds_legales_oficio.idlegalesoficio'] = $this->idlegalesoficio;
        if (!$hasRolAdminGeneral) {
            $where['mds_legales_oficio.activo'] = 1;
        }

        $query->filterWhere($where)
            ->andFilterWhere(['in', 'tipo_oficio', $this->tipo_oficio])
            ->andFilterWhere(['in', 'idarea', $this->idarea])
            ->andWhere("mds_legales_respuesta.observacion_final IS NOT NULL") //No tiene respuestas observadas
            ->andWhere("TRIM(mds_legales_respuesta.observacion_final) != ''") //No tiene respuestas observadas 
            ->andFilterWhere(['in', 'mds_legales_oficio.idlegalescaratula', $this->caratula])
            ->andFilterWhere(['mds_legales_caratula.caso' => $this->caso])
            ->andFilterWhere(['like', 'lugar_libramiento', $this->lugar_libramiento])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'juicio', $this->juicio])
            ->andFilterWhere(['mds_legales_caratula.anio_expediente' => $this->anio_expediente])
            ->andFilterWhere(['mds_legales_caratula.numero_expediente' => $this->numero_expediente]);

        $whereFechaCarga = '';
        if ($fechaFin) {
            $fechaFin = date_create($fechaFin);
            $fechaFin = $fechaFin->modify('+1 day');
            $fechaFin = date_format($fechaFin, 'Y-m-d');
        }
        if ($fechaInicio && $fechaFin) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga >= '$fechaInicio' AND mds_legales_oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $whereFechaCarga .= "mds_legales_oficio.fecha_carga <= '$fechaFin'";
        }
        $query->andWhere($whereFechaCarga);


        /* El rol supervisor general puede ver todos|no filtra*/
        if (!$hasRolSupervisorGeneral) {
            /*Si es supervisor*/
            // Se comenta para que busque los oficios que fueron derivados al usuario, sin importar si es supervisor o no
            // if(Mds_legales_oficio::tieneRol(82)){
            //     $query->andWhere(['mds_legales_derivacion.supervisor'=>1]);
            // }
            if ($hasRolSupervisorArea) {
                $idDispositivo = Mds_org_contacto::findOne($usuarioAuth->idcontacto)->iddispositivo;
                if ($idDispositivo) {
                    $query->innerJoin('mds_legales_derivacion_area', 'mds_legales_oficio.idlegalesoficio = mds_legales_derivacion_area.idoficio');
                    $query->andWhere(['mds_legales_derivacion_area.iddispositivo' => $idDispositivo]);
                }
            }
            /*Muestra solo los oficios cargados por dicho usuario*/
            // if (Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO)) {
            // $query->andWhere(['mds_legales_oficio.idusuario'=>$usuarioAuth->idusuario]);
            // }
        }

        $query->andFilterWhere(['<=', 'fecha_plazo', $this->fecha_plazo]);
        $query->andFilterWhere(['<=', 'fecha_recepcion', $this->fecha_recepcion]);


        $this->fecha_plazo ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_plazo))) :  null;
        $this->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_recepcion))) :  null;


        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_plazo']) {
            $this->fecha_plazo = $params['Mds_legales_oficioSearch']['fecha_plazo'];
        }
        if (isset($params['Mds_legales_oficioSearch']) && $params['Mds_legales_oficioSearch']['fecha_recepcion']) {
            $this->fecha_recepcion = $params['Mds_legales_oficioSearch']['fecha_recepcion'];
        }

        if ($this->generadoresRespuesta) {
            if (in_array('SIN_VALOR', $this->generadoresRespuesta)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_generador_respuesta.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 0 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_legales_derivacion as mds_legales_derivacion_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->innerJoin('mds_seg_usuario as mds_seg_usuario_generador_respuesta', 'mds_legales_derivacion_generador_respuesta.idusuario = mds_seg_usuario_generador_respuesta.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.supervisor' => 0
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion3.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion_generador_respuesta.idlegalesoficio IN (SELECT derivacion3.idlegalesoficio from mds_legales_derivacion as derivacion3 WHERE derivacion3.idusuario = $usuarioAuth->idusuario
                    AND derivacion3.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion3.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion3.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion_generador_respuesta.idusuario' => $this->generadoresRespuesta
                ]);
            }
        }

        if ($this->supervisores) {
            if (in_array('SIN_VALOR', $this->supervisores)) {
                $query->leftJoin('mds_legales_derivacion as mds_legales_derivacion_supervisor', 'mds_legales_derivacion_supervisor.idlegalesoficio = mds_legales_oficio.idlegalesoficio');
                $query->andWhere("mds_legales_derivacion_supervisor.idlegalesoficio NOT IN (select derivacion.idlegalesoficio from mds_legales_derivacion derivacion where derivacion.supervisor = 1 AND derivacion.activo = 1 AND derivacion.fecha_usu_no_corresponde IS NULL)");
            } else {
                $query->innerJoin('mds_seg_usuario', 'mds_legales_derivacion.idusuario = mds_seg_usuario.idusuario');
                $query->andWhere([
                    'mds_legales_derivacion.activo' => 1
                ]);
                $query->andWhere([
                    'mds_legales_derivacion.supervisor' => 1
                ]);

                if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {
                    $conditionSupervisor = "";

                    if ($hasRolReceptor && !$hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 0";
                    } else if (!$hasRolReceptor && $hasRolSupervisor) {
                        $conditionSupervisor .= "AND derivacion2.supervisor = 1";
                    }
                    $where = "mds_legales_derivacion.idlegalesoficio IN (SELECT derivacion2.idlegalesoficio from mds_legales_derivacion as derivacion2 WHERE derivacion2.idusuario = $usuarioAuth->idusuario
                    AND derivacion2.idlegalesoficio = mds_legales_oficio.idlegalesoficio and derivacion2.activo = 1 and mds_legales_oficio.activo = 1 AND derivacion2.fecha_usu_no_corresponde IS NULL $conditionSupervisor)";
                    $query->andWhere($where);
                }
                $query->andWhere([
                    'mds_legales_derivacion.idusuario' => $this->supervisores
                ]);
            }
        } else if (!$hasRolSupervisorGeneral && !$hasRolSupervisorArea && !$hasRolRegistro && !$hasRolAdminGeneral && ($hasRolReceptor || $hasRolSupervisor)) {

            /*si es receptor o supervisor filtra por usuario*/
            if (!Yii::$app->request->queryParams || !isset(Yii::$app->request->queryParams['sort'])) {
                $query->orderBy(['mds_legales_derivacion.idlegalesderivacion' => SORT_DESC]);
            }
            $query->andFilterWhere(['mds_legales_derivacion.activo' => 1]);

            $conditionSupervisor = "";

            if ($hasRolReceptor && !$hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 0";
            } else if (!$hasRolReceptor && $hasRolSupervisor) {
                $conditionSupervisor .= "AND mds_legales_derivacion.supervisor = 1";
            }

            $where = "mds_legales_derivacion.idusuario = $usuarioAuth->idusuario $conditionSupervisor";
            $query->andWhere($where);
        }

        if ($this->activo === '0') {
            $query->andWhere(['mds_legales_oficio.activo' => 0]);
        } else if ($this->activo === '1') {
            $query->andWhere(['mds_legales_oficio.activo' => 1]);
        }

        $existeFiltroRespuestasGeneradas = $this->respuestasGeneradas === '0' || $this->respuestasGeneradas;
        $existeFiltroRespuestasEnviadas = $this->respuestasEnviadas === '0' || $this->respuestasEnviadas;
        $existeFiltroRespuestaPendienteVistos = $this->respuestaPendienteVistos === '0' || $this->respuestaPendienteVistos;

        if ($existeFiltroRespuestasGeneradas || $existeFiltroRespuestasEnviadas || $existeFiltroRespuestaPendienteVistos) {
            $filtered_models = array();
            $filtered_key = array();
            foreach ($query->orderBy(['mds_legales_oficio.idlegalesoficio' => SORT_DESC])->all() as $oficio) {

                $condition = true;

                if ($existeFiltroRespuestasGeneradas) {
                    $totalRespuestasGeneradas = $oficio->getTotalRespuestasGeneradas();
                    $condition = $totalRespuestasGeneradas == $this->respuestasGeneradas;
                }

                if ($existeFiltroRespuestasEnviadas) {
                    $totalRespuestasEnviadas = count($oficio->getRespuestasAprobadas());
                    $condition = $condition && $totalRespuestasEnviadas == $this->respuestasEnviadas;
                }

                if ($existeFiltroRespuestaPendienteVistos) {
                    $totalRespuestaPendienteVistos = $oficio->getTotalRespuestaPendienteVistos();
                    $condition = $condition && $totalRespuestaPendienteVistos == $this->respuestaPendienteVistos;
                }

                if ($condition) {
                    $filtered_key[] = $oficio->idlegalesoficio;
                    $filtered_models[] = $oficio;
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $filtered_models,
            ]);
        }
        return $dataProvider;
    }

    public function searchOficiosSinResponderLimiteTiempo($params, $ids)
    {
        $ids = count($ids) == 0 ? 0 : $ids;
        $query = Mds_legales_oficio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficio' => SORT_DESC]],
        ]);

        $this->load($params);
        $query->filterWhere([
            'numero_expediente' => $this->numero_expediente,
            'caso' => $this->caso,
            'lugar_libramiento' => $this->lugar_libramiento,
            'area' => $this->area,
            'juicio' => $this->juicio,
            'caratula' => $this->caratula,
            'activo' => 1,

        ]);
        $query->andFilterWhere(['in', 'idlegalesoficio', $ids]);
        return $dataProvider;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
