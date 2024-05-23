<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_campania;

/**
 * Mds_cap_campaniaSearch represents the model behind the search form about `app\models\Mds_cap_campania`.
 */
class Mds_cap_campaniaSearch extends Mds_cap_campania
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcampania', 'limite_inscripciones', 'estado'], 'integer'],
            [['descripcion'], 'safe'],
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
        $query = Mds_cap_campania::find();

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
            'idcampania' => $this->idcampania,
            'limite_inscripciones' => $this->limite_inscripciones,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
