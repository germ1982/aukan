<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_orden_compra_item;

/**
 * Sds_stk_orden_compra_itemSearch represents the model behind the search form about `app\models\Sds_stk_orden_compra_item`.
 */
class Sds_stk_orden_compra_itemSearch extends Sds_stk_orden_compra_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idordencompraitem', 'idordencompra'], 'integer'],
            [['cantidad', 'importe_unitario'], 'number'],
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
        $query = Sds_stk_orden_compra_item::find();

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
            'idordencompraitem' => $this->idordencompraitem,
            'idordencompra' => $this->idordencompra,
            'cantidad' => $this->cantidad,
            'importe_unitario' => $this->importe_unitario,
        ]);

        return $dataProvider;
    }
}
