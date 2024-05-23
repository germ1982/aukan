<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_legales_caratula;

/**
 * Mds_legales_caratulaSearch represents the model behind the search form of `app\models\Mds_legales_caratula`.
 */
class Mds_legales_caratulaSearch extends Mds_legales_caratula
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalescaratula',], 'integer'],
            [['idlegalescaratula', 'caratula', 'numero_expediente', 'anio_expediente', 'caso', 'deleted_at'], 'safe'],
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
    public function search($params, $hasRolAdminGeneral, $fechaInicio, $fechaFin)
    {
        $query = Mds_legales_caratula::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['idlegalescaratula' => SORT_ASC],
                'attributes' => ['idlegalescaratula', 'caratula', 'numero_expediente', 'anio_expediente', 'caso', 'deleted_at'],
            ],
        ]);

        $this->load($params);

        if ($this->deleted_at == null && !$hasRolAdminGeneral) {
            $query->andWhere(['mds_legales_caratula.deleted_at' => null]);
        }

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['mds_legales_caratula.deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['mds_legales_caratula.deleted_at' => null]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'caratula', $this->caratula]);

        $query->andFilterWhere([
            'idlegalescaratula' => $this->idlegalescaratula,
            'numero_expediente' => $this->numero_expediente,
            'anio_expediente' => $this->anio_expediente,
            'caso' => $this->caso,
        ]);

        if ($fechaInicio || $fechaFin) {
            if ($fechaFin) {
                $fechaFin = date_create($fechaFin);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga = "mds_legales_caratula.created_at >= '$fechaInicio' AND mds_legales_caratula.created_at <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga = "mds_legales_caratula.created_at >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga = "mds_legales_caratula.created_at <= '$fechaFin'";
            }
            $query->andWhere($whereFechaCarga);
        }

        return $dataProvider;
    }
}
