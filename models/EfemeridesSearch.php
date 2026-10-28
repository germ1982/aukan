<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Efemerides;

/**
 * EfemeridesSearch represents the model behind the search form about `app\models\Efemerides`.
 */
class EfemeridesSearch extends Efemerides
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idefemeride'], 'integer'],
            [['fecha', 'titulo', 'descripcion', 'activo'], 'safe'],
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
        $query = Efemerides::find();

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
            'idefemeride' => $this->idefemeride,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'fecha', $this->fecha])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
