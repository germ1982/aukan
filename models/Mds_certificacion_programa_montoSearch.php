<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_programa_monto;

/**
 * Mds_certificacion_programa_montoSearch represents the model behind the search form of `app\models\Mds_certificacion_programa_monto`.
 */
class Mds_certificacion_programa_montoSearch extends Mds_certificacion_programa_monto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacionprograma', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['monto', 'fecha_inicio', 'fecha_fin', 'created_at', 'updated_at', 'deleted_at', 'iddireccion', 'idprograma'], 'safe'],
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

        $query = Mds_certificacion_programa_monto::find()
            ->addSelect(['mds_certificacion_programa_monto.*', 'mds_certificacion_programa.iddireccion as iddireccion', 'mds_certificacion_programa.idprograma'])
            ->leftJoin('mds_certificacion_programa', 'mds_certificacion_programa_monto.idcertificacionprograma = mds_certificacion_programa.idcertificacionprograma AND mds_certificacion_programa.deleted_at IS NULL')
            ->andWhere(['mds_certificacion_programa_monto.deleted_at' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'mds_certificacion_programa_monto.idcertificacionprogramamonto',
                    'mds_certificacion_programa_monto.idcertificacionprograma',
                    'fecha_inicio',
                    'fecha_fin',
                    'iddireccion',
                    'idprograma',
                    'monto' => [
                        'asc' => ['CONVERT(mds_certificacion_programa_monto.monto, UNSIGNED INTEGER)' => SORT_ASC],
                        'desc' => ['CONVERT(mds_certificacion_programa_monto.monto, UNSIGNED INTEGER)' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['mds_certificacion_programa_monto.idcertificacionprograma' => SORT_DESC, 'mds_certificacion_programa_monto.idcertificacionprogramamonto' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($params['Mds_certificacion_programa_montoSearch']) && $params['Mds_certificacion_programa_montoSearch']['fecha_inicio']) {
            $fecha_desde = $params['Mds_certificacion_programa_montoSearch']['fecha_inicio'];
            $fecha_desde = armarDateParaMySql($fecha_desde);
            $fecha_desde = date_create($fecha_desde);
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
            $this->fecha_inicio = $fecha_desde;
        }

        if (isset($params['Mds_certificacion_programa_montoSearch']) && $params['Mds_certificacion_programa_montoSearch']['fecha_fin']) {
            $fecha_hasta = $params['Mds_certificacion_programa_montoSearch']['fecha_fin'];
            $fecha_hasta = armarDateParaMySql($fecha_hasta);
            $fecha_hasta = date_create($fecha_hasta);
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
            $this->fecha_fin = $fecha_hasta;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'idcertificacionprogramamonto' => $this->idcertificacionprogramamonto,
            'idcertificacionprograma' => $this->idcertificacionprograma,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mds_certificacion_programa_monto.deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'iddireccion', $this->iddireccion])
            ->andFilterWhere(['in', 'idprograma', $this->idprograma])
            ->andFilterWhere(['like', 'monto', $this->monto])
            ->andFilterWhere(['>=', 'fecha_inicio', $this->fecha_inicio])
            ->andFilterWhere(['<=', 'fecha_fin', $this->fecha_fin]);


        $this->fecha_inicio ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_inicio))) :  null;
        $this->fecha_fin ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_fin))) :  null;

        if (isset($params['Mds_certificacion_programa_montoSearch']['fecha_inicio']) && $params['Mds_certificacion_programa_montoSearch']['fecha_inicio']) {
            $this->fecha_inicio = $params['Mds_certificacion_programa_montoSearch']['fecha_inicio'];
        }

        if (isset($params['Mds_certificacion_programa_montoSearch']['fecha_fin']) && $params['Mds_certificacion_programa_montoSearch']['fecha_fin']) {
            $this->fecha_fin = $params['Mds_certificacion_programa_montoSearch']['fecha_fin'];
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
