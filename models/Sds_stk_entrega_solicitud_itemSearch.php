<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_entrega_solicitud_item;

/**
 * Sds_stk_entrega_solicitud_itemSearch represents the model behind the search form about `app\models\Sds_stk_entrega_solicitud_item`.
 */
class Sds_stk_entrega_solicitud_itemSearch extends Sds_stk_entrega_solicitud_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identregasolicituditem', 'idarticulo', 'cantidad', 'identregasolicitud'], 'integer'],
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
        $query = Sds_stk_entrega_solicitud_item::find();

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
            'identregasolicituditem' => $this->identregasolicituditem,
            'idarticulo' => $this->idarticulo,
            'cantidad' => $this->cantidad,
            'identregasolicitud' => $this->identregasolicitud,
        ]);

        return $dataProvider;
    }
}
