<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ViewStockArticulosCantidades;

/**
 * ViewStockArticulosCantidadesSearch represents the model behind the search form about `app\models\ViewStockArticulosCantidades`.
 */
class ViewStockArticulosCantidadesSearch extends ViewStockArticulosCantidades
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticulo'], 'integer'],
            [['rubro', 'descripcion'], 'safe'],
            [['ingresado', 'entregado', 'disponible'], 'number'],
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
        $query = ViewStockArticulosCantidades::find();

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
            'idarticulo' => $this->idarticulo,
            'ingresado' => $this->ingresado,
            'entregado' => $this->entregado,
            'disponible' => $this->disponible,
        ]);

        $query->andFilterWhere(['like', 'rubro', $this->rubro])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
