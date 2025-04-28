<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroFamiliaLegajo;


class RegistroFamiliaLegajoSearch extends RegistroFamiliaLegajo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num_legajo', 'id', 'tipo_legajo'], 'integer'],
            [['dni', 'archivo_adjunto', 'nombre', 'apellido'], 'safe'],
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
        $query = RegistroFamiliaLegajo::find();

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
            'id' => $this->id,
            'tipo_legajo' => $this->tipo_legajo,
        ]);

        $query->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'archivo_adjunto', $this->archivo_adjunto])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido]);

        return $dataProvider;
    }
}
