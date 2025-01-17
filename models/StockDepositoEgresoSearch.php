<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockDepositoEgreso;

/**
 * StockDepositoEgresoSearch represents the model behind the search form about `app\models\StockDepositoEgreso`.
 */
class StockDepositoEgresoSearch extends StockDepositoEgreso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idegreso', 'idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe'], 'integer'],
            [['fecha', 'observacion'], 'safe'],
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
        $query = StockDepositoEgreso::find();

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
            'idegreso' => $this->idegreso,
            'fecha' => $this->fecha,
            'idpersona_solicitante' => $this->idpersona_solicitante,
            'idempleado_autorizacion' => $this->idempleado_autorizacion,
            'idempleado_despacha' => $this->idempleado_despacha,
            'idpersona_recibe' => $this->idpersona_recibe,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
