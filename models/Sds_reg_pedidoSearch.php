<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_pedido;

/**
 * Sds_reg_pedidoSearch represents the model behind the search form about `app\models\Sds_reg_pedido`.
 */
class Sds_reg_pedidoSearch extends Sds_reg_pedido
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpedido', 'numero', 'estado'], 'integer'],
            [['expediente', 'descripcion'], 'safe'],
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
        $query = Sds_reg_pedido::find();

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
            'idpedido' => $this->idpedido,
            'numero' => $this->numero,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'expediente', $this->expediente])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);


        return $dataProvider;
    }
}
