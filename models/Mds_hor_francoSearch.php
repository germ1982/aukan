<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_franco;

/**
 * Mds_hor_francoSearch represents the model behind the search form about `app\models\Mds_hor_franco`.
 */
class Mds_hor_francoSearch extends Mds_hor_franco
{
    public $desde;
    public $hasta;
    public function rules()
    {
        return [
            [['idfranco', 'idcontacto'], 'integer'],
            [['fecha', 'descripcion','desde','hasta'], 'safe'],
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
        $query = Mds_hor_franco::find();

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
            'idcontacto' => $this->idcontacto,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
