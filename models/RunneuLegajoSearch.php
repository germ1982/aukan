<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RunneuLegajo;

/**
 * RunneuLegajoSearch represents the model behind the search form about `app\models\RunneuLegajo`.
 */
class RunneuLegajoSearch extends RunneuLegajo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num_legajo'], 'integer'],
            [['dni', 'archivo_adjunto'], 'safe'],
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
        $query = RunneuLegajo::find();

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
            'num_legajo' => $this->num_legajo,
        ]);

        $query->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'archivo_adjunto', $this->archivo_adjunto]);

        return $dataProvider;
    }
}
