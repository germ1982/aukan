<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_observacion;

/**
 * Mds_rum_observacionSearch represents the model behind the search form about `app\models\Mds_rum_observacion`.
 */
class Mds_rum_observacionSearch extends Mds_rum_observacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idobservacion', 'id_cv', 'id_persona'], 'integer'],
            
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
        $query = Mds_rum_observacion::find();

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
            'idobservacion' => $this->idobservacion,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'id_cv' => $this->id_cv,
            'id_persona' => $this->id_persona,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
