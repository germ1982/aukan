<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\View_relevamiento_planta;

/**
 * View_relevamiento_plantaSearch represents the model behind the search form about `app\models\View_relevamiento_planta`.
 */
class View_relevamiento_plantaSearch extends View_relevamiento_planta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relevado', 'ultima_modificacion', 'apellido', 'nombre', 'Cuil', 'mail', 'telefono', 'organismo_funciones_actualmente', 'Categoría', 'lugar_planta_permanente', 'edificio', 'fecha_ingreso', 'fecha_nacimiento', 'funcion_actual', 'observaciones', 'lugar_carga'], 'safe'],
            [['documento', 'legajo'], 'integer'],
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
        $query = View_relevamiento_planta::find();

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
            'ultima_modificacion' => $this->ultima_modificacion,
            'documento' => $this->documento,
            'legajo' => $this->legajo,
            'fecha_ingreso' => $this->fecha_ingreso,
        ]);

        $query->andFilterWhere(['like', 'relevado', $this->relevado])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'Cuil', $this->Cuil])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'telefono', $this->telefono])            
            ->andFilterWhere(['like', 'Categoría', $this->Categoría])
            ->andFilterWhere(['like', 'lugar_planta_permanente', $this->lugar_planta_permanente])
            ->andFilterWhere(['like', 'edificio', $this->edificio])
            ->andFilterWhere(['like', 'fecha_nacimiento', $this->fecha_nacimiento])
            ->andFilterWhere(['like', 'funcion_actual', $this->funcion_actual])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'lugar_carga', $this->lugar_carga]);

        return $dataProvider;
    }
}
