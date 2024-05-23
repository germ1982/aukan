<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_stock_inversion_os;

/**
 * View_stock_inversion_osSearch represents the model behind the search form of `app\models\View_stock_inversion_os`.
 */
class View_stock_inversion_osSearch extends View_stock_inversion_os
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organismo', 'idarticulo', 'anio', 'idorganizacionsocial'], 'integer'],
            [['articulo', 'organizacionsocial'], 'safe'],
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
        $query = View_stock_inversion_os::find();

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
            'idorganizacionsocial' => $this->idorganizacionsocial,
            'cantidad' => $this->cantidad,
            'importe' => $this->importe,
        ]);

        $query->andFilterWhere(['like', 'articulo', $this->articulo])
            ->andFilterWhere(['like', 'organizacionsocial', $this->organizacionsocial]);

        return $dataProvider;
    }
}
