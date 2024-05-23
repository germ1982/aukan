<?php


namespace app\models;

use app\models\Mds_certificacion;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Mds_certificacionSearch extends Mds_certificacion
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'idbeneficiario',
                'idcertificacion',
                'idlocalidad',
                'idprograma',
                'idarea',
                'idrisneu',
                'idnivel_autorizacion',
                'monto',
                'sueldo',
                'nro_expediente',
                'observaciones',
                'periodo_desde',
                'periodo_hasta',
                'deleted_at',
                'responsable',
                'idestado',
                'idusuario_carga',
                'direccion_actual',
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
    public function search(
        $params,
        $fechaInicio = null,
        $fechaFin = null,
        $idlocalidad = null,
        $idprograma = null,
        $idcaracter = null,
        $tipocertificacion = null,
        $idorganismosolicitante = null,
        $jubilacion = null,
        $iddireccion = null
    ) {
        $query = Mds_certificacion::find()
            ->addSelect(['mds_certificacion.*', 'mds_certificacion_monto.monto as monto'])
            ->innerJoin('mds_certificacion_responsable', 'mds_certificacion.idcertificacion = mds_certificacion_responsable.idcertificacion AND mds_certificacion_responsable.deleted_at IS NULL')
            ->innerJoin('mds_certificacion_monto', 'mds_certificacion.idcertificacion = mds_certificacion_monto.idcertificacion AND mds_certificacion_monto.deleted_at IS NULL')
            ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion AND mds_certificacion_estado.deleted_at IS NULL')
            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
            ->leftJoin('mds_certificacion_director', 'mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_director.idcertificaciondireccion')
            ->innerJoin('sds_com_configuracion confPrograma', 'mds_certificacion.idprograma = confPrograma.idconfiguracion');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idcertificacion',
                    'mds_certificacion.idcertificacion',
                    'mds_certificacion_responsable.dni',
                    'deleted_at',
                    'monto' => [
                        'asc' => ['CONVERT(mds_certificacion_monto.monto, UNSIGNED INTEGER)' => SORT_ASC],
                        'desc' => ['CONVERT(mds_certificacion_monto.monto, UNSIGNED INTEGER)' => SORT_DESC],
                    ],
                    'responsable' => [
                        'asc' => ['mds_certificacion_responsable.nombre_apellido' => SORT_ASC],
                        'desc' => ['mds_certificacion_responsable.nombre_apellido' => SORT_DESC],
                    ],
                    'direccionPrevia',
                    'idbeneficiario' => [
                        'asc' => ['sds_com_persona.apellido' => SORT_ASC],
                        'desc' => ['sds_com_persona.apellido' => SORT_DESC],
                    ],
                    'idprograma' => [
                        'asc' => ['confPrograma.descripcion' => SORT_ASC],
                        'desc' => ['confPrograma.descripcion' => SORT_DESC],
                    ],
                    'nro_expediente',
                    'periodo_desde',
                    'periodo_hasta',
                    'idestado',
                    'idrisneu',
                    'idusuario_carga',
                ],
                'defaultOrder' => ['mds_certificacion.idcertificacion' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if ($this->direccion_actual) {
            $queryAux = Mds_certificacion::find()
                ->addSelect(['mds_certificacion.idcertificacion'])
                ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion AND mds_certificacion_estado.deleted_at IS NULL')
                ->andWhere(['mds_certificacion_estado.fecha_fin' => NULL]);

            if (in_array(0, $this->direccion_actual)) {
                if (count($this->direccion_actual) > 1) {
                    $queryAux->andWhere(
                        [
                            'or',
                            ['mds_certificacion_estado.iddireccion' => $this->direccion_actual],
                            [
                                'and',
                                ['mds_certificacion_estado.iddireccion' => null],
                                ['<>', 'mds_certificacion_estado.idestado', Mds_certificacion_estado::ESTADO_ENVIADA],
                                ['<>', 'mds_certificacion_estado.idestado', Mds_certificacion_estado::ESTADO_ELIMINADA]
                            ]
                        ]
                    );
                } else {
                    $queryAux->andWhere(
                        [
                            'and',
                            ['mds_certificacion_estado.iddireccion' => null],
                            ['<>', 'mds_certificacion_estado.idestado', Mds_certificacion_estado::ESTADO_ENVIADA],
                            ['<>', 'mds_certificacion_estado.idestado', Mds_certificacion_estado::ESTADO_ELIMINADA]
                        ]

                    );
                }
            } else {
                $queryAux->andWhere(['mds_certificacion_estado.iddireccion' => $this->direccion_actual]);
            }
            $query->where(['in', 'mds_certificacion.idcertificacion', $queryAux]);
        }

        if (isset($params['Mds_certificacionSearch']) && $params['Mds_certificacionSearch']['periodo_desde']) {
            $periodo_desde = $params['Mds_certificacionSearch']['periodo_desde'];
            $periodo_desde = armarDateParaMySql($periodo_desde);
            $periodo_desde = date_create($periodo_desde);
            $periodo_desde = date_format($periodo_desde, 'Y-m-d');
            $this->periodo_desde = $periodo_desde;
        }
        if (isset($params['Mds_certificacionSearch']) && $params['Mds_certificacionSearch']['periodo_hasta']) {
            $periodo_hasta = $params['Mds_certificacionSearch']['periodo_hasta'];
            $periodo_hasta = armarDateParaMySql($periodo_hasta);
            $periodo_hasta = date_create($periodo_hasta);
            $periodo_hasta = date_format($periodo_hasta, 'Y-m-d');
            $this->periodo_hasta = $periodo_hasta;
        }

        if ($fechaInicio || $fechaFin) {
            if ($fechaFin) {
                $fechaFin = date_create($fechaFin);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga = "mds_certificacion.created_at >= '$fechaInicio' AND mds_certificacion.created_at <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga = "mds_certificacion.created_at >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga = "mds_certificacion.created_at <= '$fechaFin'";
            }
            $query->andWhere($whereFechaCarga);
        }

        $query->joinWith('beneficiario');
        $query->joinWith('localidad');
        $query->joinWith('usuarioCarga');

        $query->filterWhere([
            'mds_certificacion.idcertificacion' => $this->idcertificacion,
            'mds_certificacion.idusuario_carga' => $this->idusuario_carga,
        ])
            ->andFilterWhere(['like', 'monto', $this->monto])
            ->andFilterWhere(['like', 'nro_expediente', $this->nro_expediente])
            ->andFilterWhere(['>=', 'periodo_desde', $this->periodo_desde])
            ->andFilterWhere(['idrisneu' => $this->idrisneu])
            ->andFilterWhere(['<=', 'periodo_hasta', $this->periodo_hasta])
            ->andFilterWhere(['in', 'idprograma', $this->idprograma])
            ->andFilterWhere(['in', 'mds_certificacion.idestado', $this->idestado]);

        if ($this->idbeneficiario) {
            $query->andWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->idbeneficiario],
                ['like', 'sds_com_persona.nombre', $this->idbeneficiario],
                ['like', 'sds_com_persona.apellido', $this->idbeneficiario]
            ]);
        };

        if ($this->responsable) {
            $query->andWhere([
                'or',
                ['like', 'mds_certificacion_responsable.nombre_apellido', $this->responsable],
                ['like', 'mds_certificacion_responsable.dni', $this->responsable]
            ]);
        };
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_certificacion.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_certificacion.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_certificacion.deleted_at' => null]);
        }

        $this->periodo_desde ? date('d/m/Y', strtotime(str_replace('-', '/', $this->periodo_desde))) :  null;
        $this->periodo_hasta ? date('d/m/Y', strtotime(str_replace('-', '/', $this->periodo_hasta))) :  null;

        if (isset($params['Mds_certificacionSearch']) && $params['Mds_certificacionSearch']['periodo_desde']) {

            $this->periodo_desde = $params['Mds_certificacionSearch']['periodo_desde'];
        }
        if (isset($params['Mds_certificacionSearch']) && $params['Mds_certificacionSearch']['periodo_hasta']) {
            $this->periodo_hasta = $params['Mds_certificacionSearch']['periodo_hasta'];
        }

        if ($idlocalidad) {
            $query->andWhere(['mds_certificacion.idlocalidad' => $idlocalidad]);
        }

        if ($idprograma) {
            $query->andWhere(['mds_certificacion.idprograma' => $idprograma]);
        }

        if ($idcaracter) {
            $query->andWhere(['mds_certificacion.idcaracter' => $idcaracter]);
        }

        if ($tipocertificacion != null) {
            $query->andWhere(['mds_certificacion.tipo_certificacion' => $tipocertificacion]);
        }

        if ($idorganismosolicitante) {
            $query->andWhere(['mds_certificacion.idorganismo_solicitante' => $idorganismosolicitante]);
        }

        if ($jubilacion != null) {
            if ($jubilacion == 2) {
                $query->andWhere("mds_certificacion.jubilacion IS NULL");
            } else {
                $query->andWhere(['mds_certificacion.jubilacion' => $jubilacion]);
            }
        }

        $query->groupBy(['mds_certificacion.idcertificacion']);

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
