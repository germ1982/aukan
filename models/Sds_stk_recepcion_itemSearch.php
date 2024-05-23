<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_recepcion_item;

/**
 * Sds_stk_recepcion_itemSearch represents the model behind the search form about `app\models\Sds_stk_recepcion_item`.
 */
class Sds_stk_recepcion_itemSearch extends Sds_stk_recepcion_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idrecepcionitem', 'idrecepcion', 'idarticulo','cantidad'], 'integer'],
            [['descripcion'], 'safe'],
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
        $query = Sds_stk_recepcion_item::find();

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
            'idrecepcionitem' => $this->idrecepcionitem,
            'idrecepcion' => $this->idrecepcion,
            'idarticulo' => $this->idarticulo,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
