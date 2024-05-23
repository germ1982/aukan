<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_legales_supervisor_area;

/**
 * Mds_legales_supervisor_areaSearch represents the model behind the search form of `app\models\Mds_legales_supervisor_area`.
 */
class Mds_legales_supervisor_areaSearch extends Mds_legales_supervisor_area
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalessupervisorarea',], 'integer'],
            [['idlegalessupervisorarea', 'idusuario', 'idarea', 'deleted_at'], 'safe'],
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
    public function search($params, $hasRolAdminGeneral)
    {
        $query = Mds_legales_supervisor_area::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['idlegalessupervisorarea' => SORT_ASC],
                'attributes' => ['idlegalessupervisorarea', 'idusuario', 'idarea', 'deleted_at'],
            ],
        ]);

        $this->load($params);

        if ($this->deleted_at == null && !$hasRolAdminGeneral) {
            $query->andWhere(['mds_legales_supervisor_area.deleted_at' => null]);
        }

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['mds_legales_supervisor_area.deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['mds_legales_supervisor_area.deleted_at' => null]);
        }

        if ($this->idarea) {
            $query->andWhere(
                ['in', 'mds_legales_supervisor_area.idarea', $this->idarea]
            );
        }

        if ($this->idusuario) {
            $query->andWhere(
                ['in', 'mds_legales_supervisor_area.idusuario', $this->idusuario]
            );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idlegalessupervisorarea' => $this->idlegalessupervisorarea,
        ]);

        return $dataProvider;
    }
}
