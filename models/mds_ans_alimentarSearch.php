<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mds_ans_alimentar;

/**
 * mds_ans_alimentarSearch represents the model behind the search form about `app\models\mds_ans_alimentar`.
 */
class mds_ans_alimentarSearch extends mds_ans_alimentar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dni'], 'integer'],
            [['municipio', 'nombre', 'estado', 'cuil', 'fecha'], 'safe'],
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
        $query = mds_ans_alimentar::find();

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
            'id' => $this->id,
            'dni' => $this->dni,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'municipio', $this->municipio])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'cuil', $this->cuil]);

        return $dataProvider;
    }
}
