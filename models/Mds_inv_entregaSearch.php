<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_inv_entrega;

/**
 * Mds_inv_entregaSearch represents the model behind the search form about `app\models\Mds_inv_entrega`.
 */
class Mds_inv_entregaSearch extends Mds_inv_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identrega', 'estado', 'idpersona'], 'integer'],
            
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
        $query = Mds_inv_entrega::find();

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
            'identrega' => $this->identrega,
            'idespecie' => $this->idespecie,
            'cantidad' => $this->cantidad,
            'fecha' => $this->fecha,
            'estado' => $this->estado,
            'idpersona' => $this->idpersona,
        ]);

        return $dataProvider;
    }
}
