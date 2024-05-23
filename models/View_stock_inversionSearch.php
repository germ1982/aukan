<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_stock_inversion;

/**
 * View_stock_inversionSearch represents the model behind the search form of `app\models\View_stock_inversion`.
 */
class View_stock_inversionSearch extends View_stock_inversion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organismo', 'idarticulo', 'anio'], 'integer'],
            [['articulo'], 'safe'],
            [['cantidad', 'importe'], 'number'],
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
        $query = View_stock_inversion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'organismo' => $this->organismo,
            'idarticulo' => $this->idarticulo,
            'anio' => $this->anio,
            'cantidad' => $this->cantidad,
            'importe' => $this->importe,
        ]);

        $query->andFilterWhere(['like', 'articulo', $this->articulo]);

        return $dataProvider;
    }
}
