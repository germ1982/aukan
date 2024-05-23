<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mds_ans_alimentar_titulares;

/**
 * mds_ans_alimentar_titularesSearch represents the model behind the search form of `app\models\mds_ans_alimentar_titulares`.
 */
class mds_ans_alimentar_titularesSearch extends mds_ans_alimentar_titulares
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'dni', 'estado_entrega'], 'integer'],
            [['apellido', 'nombre', 'cuil', 'provincia', 'municipio', 'totalHijos', 'embarazo', 'estado', 'localidad', 'departamento', 'fecha_hora'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = mds_ans_alimentar_titulares::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'dni' => $this->dni,
            'estado_entrega' => $this->estado_entrega,
        ]);

        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'cuil', $this->cuil])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'municipio', $this->municipio])
            ->andFilterWhere(['like', 'totalHijos', $this->totalHijos])
            ->andFilterWhere(['like', 'embarazo', $this->embarazo])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'departamento', $this->departamento])
            ->andFilterWhere(['like', 'fecha_hora', $this->fecha_hora]);

        return $dataProvider;
    }
}
