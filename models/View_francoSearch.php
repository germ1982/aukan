<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_franco;

/**
 * View_francoSearch represents the model behind the search form about `app\models\View_franco`.
 */
class View_francoSearch extends View_franco
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfranco', 'anio', 'mes', 'idcontacto', 'legajo', 'documento', 'tipo'], 'integer'],
            [['fecha', 'nombre', 'apellido', 'dispositivo', 'organismo', 'tipo_descripcion', 'descripcion','desde','hasta'], 'safe'],
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
        $query = View_franco::find();

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
            'idfranco' => $this->idfranco,
            'fecha' => $this->fecha,
            'anio' => $this->anio,
            'mes' => $this->mes,
            'idcontacto' => $this->idcontacto,
            'legajo' => $this->legajo,
            'documento' => $this->documento,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'dispositivo', $this->dispositivo])
            ->andFilterWhere(['like', 'organismo', $this->organismo])
            ->andFilterWhere(['like', 'tipo_descripcion', $this->tipo_descripcion])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
