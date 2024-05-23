<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_articulo_safipro;

/**
 * Sds_stk_articulo_safiproSearch represents the model behind the search form about `app\models\Sds_stk_articulo_safipro`.
 */
class Sds_stk_articulo_safiproSearch extends Sds_stk_articulo_safipro
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticulosafipro', 'idarticulo', 'clase', 'item'], 'integer'],
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
        $query = Sds_stk_articulo_safipro::find();

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
            'idarticulosafipro' => $this->idarticulosafipro,
            'idarticulo' => $this->idarticulo,
            'clase' => $this->clase,
            'item' => $this->item,
        ]);

        return $dataProvider;
    }
}
