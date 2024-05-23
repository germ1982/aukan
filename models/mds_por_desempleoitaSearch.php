<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mds_por_desempleo;

/**
 * mds_por_desempleoitaSearch represents the model behind the search form about `app\models\mds_por_desempleo`.
 */
class mds_por_desempleoitaSearch extends mds_por_desempleo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddesempleo', 'dni', 'cheque', 'monto', 'prov'], 'integer'],
            [['tipo', 'fecha', 'nombre', 'lug'], 'safe'],
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
    public function search($params)
    {
        $query = mds_por_desempleo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'iddesempleo' => $this->iddesempleo,
            'fecha' => $this->fecha,
            'dni' => $this->dni,
            'cheque' => $this->cheque,
            'monto' => $this->monto,
            'prov' => $this->prov,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'lug', $this->lug]);

        return $dataProvider;
    }
}
