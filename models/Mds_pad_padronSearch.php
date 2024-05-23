<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_pad_padron;

/**
 * Mds_pad_padronSearch represents the model behind the search form about `app\models\Mds_pad_padron`.
 */
class Mds_pad_padronSearch extends Mds_pad_padron
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpadron'], 'integer'],
            [['circuito_anterior', 'circuito_nuevo', 'denominacion_circuito', 'afiliacion', 'documento', 'apellido', 'nombre', 'calle', 'altura'], 'safe'],
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
        $query = Mds_pad_padron::find();

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
            'idpadron' => $this->idpadron,
        ]);

        $query->andFilterWhere(['like', 'circuito_anterior', $this->circuito_anterior])
            ->andFilterWhere(['like', 'circuito_nuevo', $this->circuito_nuevo])
            ->andFilterWhere(['like', 'denominacion_circuito', $this->denominacion_circuito])
            ->andFilterWhere(['like', 'afiliacion', $this->afiliacion])
            ->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'calle', $this->calle])
            ->andFilterWhere(['like', 'altura', $this->altura]);

        return $dataProvider;
    }
}
