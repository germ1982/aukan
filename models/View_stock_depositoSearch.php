<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_stock_deposito;

/**
 * View_stock_depositoSearch represents the model behind the search form about `app\models\View_stock_deposito`.
 */
class View_stock_depositoSearch extends View_stock_deposito
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticulo', 'deposito', 'organismo'], 'integer'],
            [['deposito_descripcion'], 'safe'],
            [['stock'], 'number'],
            /* [['detalle_depositos'], 'string'], */
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
        $query = View_stock_deposito::find();

        $dataProvider = new ActiveDataProvider([
            /* 'sort' => [
                'attributes' => [
                    'idarticulo',
                ],
                'defaultOrder' => ['idarticulo' => SORT_ASC]
            ], */
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $icono = '<span class="glyphicon glyphicon-arrow-right"></span>';


        $consulta_detalle_items = "SELECT GROUP_CONCAT(concat('".$icono." ',sd.deposito_descripcion,' <b>',sd.stock,'</b>') SEPARATOR ' <br> ') as detalle_depositos
                                            FROM view_stock_deposito sd 
                                            WHERE sd.stock > 0 and sd.idarticulo = view_stock_deposito.idarticulo";

        $query->addSelect([
            ' `view_stock_deposito`.*',
            "($consulta_detalle_items)as detalle_depositos",
        ]);

        $query->andFilterWhere([
            'idarticulo' => $this->idarticulo,
            'deposito' => $this->deposito,
            'organismo' => $this->organismo,
            'stock' => $this->stock,
        ]);

        $query->andFilterWhere(['like', 'deposito_descripcion', $this->deposito_descripcion])
        ->having("detalle_depositos like '%" .$this->detalle_depositos ."%'");
        $query->groupBy(['idarticulo']);
        return $dataProvider;
    }
}
