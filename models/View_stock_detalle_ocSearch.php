<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_stock_detalle_oc;

/**
 * View_stock_detalle_ocSearch represents the model behind the search form of `app\models\View_stock_detalle_oc`.
 */
class View_stock_detalle_ocSearch extends View_stock_detalle_oc
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora'], 'safe'],
            [['idarticulo', 'deposito', 'tipo', 'cantidad', 'organismo', 'item_recepcion', 'idordencompra', 'anio', 'mes', 'organizacion_social'], 'integer'],
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
        $query = View_stock_detalle_oc::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $anio = ($this->anio != null ? $this->anio : -1);
        // grid filtering conditions
        $query->select(['voc.tipo', 'sum(cantidad) cantidad', 'voc.anio', 'voc.mes'])
            ->from(['view_stock_detalle_oc voc'])
            ->andFilterWhere([
                'fecha_hora' => $this->fecha_hora,
                'idarticulo' => $this->idarticulo,
                'deposito' => $this->deposito,
                'tipo' => $this->tipo,
                'cantidad' => $this->cantidad,
                'organismo' => $this->organismo,
                'item_recepcion' => $this->item_recepcion,
                'idordencompra' => $this->idordencompra,
                'organizacion_social' => $this->organizacion_social,
                'mes' => $this->mes,
            ])
            ->andWhere("(anio=$anio or $anio<0)");
        $query->groupBy(['anio', 'mes', 'tipo', 'idarticulo']);

        return $dataProvider;
    }
}
