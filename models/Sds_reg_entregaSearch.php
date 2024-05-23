<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_entrega;

/**
 * Sds_reg_entregaSearch represents the model behind the search form about `app\models\Sds_reg_entrega`.
 */
class Sds_reg_entregaSearch extends Sds_reg_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idregistroentrega', 'idregistro', 'idarticulo', 'cantidad'], 'integer'],
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
        $query = Sds_reg_entrega::find();

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
            'idregistroentrega' => $this->idregistroentrega,
            'idregistro' => $this->idregistro,
            'idarticulo' => $this->idarticulo,
            'cantidad' => $this->cantidad,
        ]);

        return $dataProvider;
    }
}
