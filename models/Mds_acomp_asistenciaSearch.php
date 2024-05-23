<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_acomp_asistencia;
use Yii;

/**
 * Mds_acomp_asistenciaSearch represents the model behind the search form of `app\models\Mds_acomp_asistencia`.
 */
class Mds_acomp_asistenciaSearch extends Mds_acomp_asistencia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idasistencia'], 'integer'],
            [['idbeneficiario', 'idlocalidad', 'idlocalidad_ingreso', 'idriesgo', 'periodo_desde', 'periodo_hasta', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params)
    {
        $hasRolUsuario = Mds_legales_oficio::tieneRol(Mds_acomp_asistencia::ID_ROL_USUARIO);
        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_acomp_asistencia::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idasistencia' => SORT_DESC]],
        ]);

        $this->load($params);

        if (isset($params['Mds_acomp_asistenciaSearch']['periodo_desde']) && $params['Mds_acomp_asistenciaSearch']['periodo_desde']) {
            $periodo_desde = $params['Mds_acomp_asistenciaSearch']['periodo_desde'];
            $periodo_desde = armarDateParaMySql($periodo_desde);
            $periodo_desde = date_create($periodo_desde);
            $periodo_desde = date_format($periodo_desde, 'Y-m-d');
            $this->periodo_desde = $periodo_desde;
        }

        if (isset($params['Mds_acomp_asistenciaSearch']['periodo_hasta']) && $params['Mds_acomp_asistenciaSearch']['periodo_hasta']) {
            $periodo_hasta = $params['Mds_acomp_asistenciaSearch']['periodo_hasta'];
            $periodo_hasta = armarDateParaMySql($periodo_hasta);
            $periodo_hasta = date_create($periodo_hasta);
            $periodo_hasta = date_format($periodo_hasta, 'Y-m-d');
            $this->periodo_hasta = $periodo_hasta;
        }

        if ($hasRolUsuario) {
            $query->andWhere(['mds_acomp_asistencia.deleted_at' => null]);
        }

        $query->joinWith('riesgo');
        $query->joinWith('beneficiario');

        if ($this->idriesgo) {
            $query->andWhere(
                ['in', 'sds_com_configuracion.descripcion', $this->idriesgo]
            );
        };

        if ($this->idbeneficiario) {
            $query->andWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->idbeneficiario],
                ['like', 'sds_com_persona.nombre', $this->idbeneficiario],
                ['like', 'sds_com_persona.apellido', $this->idbeneficiario]
            ]);
        };

        if (!$hasRolUsuario) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['deleted_at' => null]);
            }
        }

        // grid filtering conditions
        if ($this->idasistencia) {
            $query->andFilterWhere(['idasistencia' => $this->idasistencia]);
        }

        if ($this->idlocalidad) {
            $query->andWhere(
                ['in', 'mds_acomp_asistencia.idlocalidad', $this->idlocalidad]
            );
        }

        if ($this->idlocalidad_ingreso) {
            $query->andWhere(
                ['in', 'mds_acomp_asistencia.idlocalidad_ingreso', $this->idlocalidad_ingreso]
            );
        }

        $query->andFilterWhere(['>=', 'periodo_desde', $this->periodo_desde]);
        $query->andFilterWhere(['<=', 'periodo_hasta', $this->periodo_hasta]);

        $this->periodo_desde ? date('d/m/Y', strtotime(str_replace('-', '/', $this->periodo_desde))) :  null;
        $this->periodo_hasta ? date('d/m/Y', strtotime(str_replace('-', '/', $this->periodo_hasta))) :  null;

        if (isset($params['Mds_acomp_asistenciaSearch']['periodo_desde']) && $params['Mds_acomp_asistenciaSearch']['periodo_desde']) {
            $this->periodo_desde = $params['Mds_acomp_asistenciaSearch']['periodo_desde'];
        }

        if (isset($params['Mds_acomp_asistenciaSearch']['periodo_hasta']) && $params['Mds_acomp_asistenciaSearch']['periodo_hasta']) {
            $this->periodo_hasta = $params['Mds_acomp_asistenciaSearch']['periodo_hasta'];
        }

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
