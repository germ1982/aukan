<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_cel_factura_item;

/**
 * Sds_cel_factura_itemSearch represents the model behind the search form about `app\models\Sds_cel_factura_item`.
 */
class Sds_cel_factura_itemSearch extends Sds_cel_factura_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfacturaitem', 'idfactura', 'linea'], 'integer'],
            [['concepto', 'idconcepto'], 'safe'],
            [['cantidad', 'neto', 'impuestos', 'total'], 'number'],
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
        $query = Sds_cel_factura_item::find();

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
            'idfacturaitem' => $this->idfacturaitem,
            'idfactura' => $this->idfactura,
            'linea' => $this->linea,
            'cantidad' => $this->cantidad,
            'neto' => $this->neto,
            'impuestos' => $this->impuestos,
            'total' => $this->total,
        ]);

        $query->andFilterWhere(['like', 'concepto', $this->concepto])
            ->andFilterWhere(['like', 'idconcepto', $this->idconcepto]);

        return $dataProvider;
    }
}
