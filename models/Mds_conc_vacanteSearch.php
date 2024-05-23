<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_conc_vacante;
use app\models\Mds_conc_solicitud;

/**
 * Mds_conc_vacanteSearch represents the model behind the search form of `app\models\Mds_conc_vacante`.
 */
class Mds_conc_vacanteSearch extends Mds_conc_vacante
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'idvacante',
                ], 'integer'
            ],
            [
                [
                    'idvacante',
                    'categoria',
                    'idconcurso',
                    'cantidad',
                    'requiere_titulo',
                    'deleted_at',
                ], 'safe'
            ],
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
        $query = Mds_conc_vacante::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idvacante',
                    'categoria',
                    'idconcurso',
                    'cantidad',
                    'requiere_titulo',
                    'deleted_at',
                ],
                'defaultOrder' => ['idvacante' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_conc_vacante.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_conc_vacante.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_conc_vacante.deleted_at' => null]);
        }

        if ($this->requiere_titulo === '0') {
            $query->andWhere(['mds_conc_vacante.requiere_titulo' => 0]);
        } else if ($this->requiere_titulo === '1') {
            $query->andWhere(['mds_conc_vacante.requiere_titulo' => 1]);
        }

        $query->andFilterWhere([
            'idvacante' => $this->idvacante,
        ]);

        $query->andFilterWhere(['in', 'categoria', $this->categoria])
            ->andFilterWhere(['in', 'idconcurso', $this->idconcurso])
            ->andFilterWhere(['=', 'cantidad', $this->cantidad]);
        return $dataProvider;
    }
}
