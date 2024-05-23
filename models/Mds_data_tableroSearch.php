<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_data_tablero;

/**
 * Mds_data_tableroSearch represents the model behind the search form about `app\models\Mds_data_tablero`.
 */
class Mds_data_tableroSearch extends Mds_data_tablero
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtablero', 'idcategoria', 'iditem', 'orden', 'estado'], 'integer'],
            [['nombre', 'descripcion', 'url'], 'safe'],
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
        $query = Mds_data_tablero::find();

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
            'idtablero' => $this->idtablero,
            'idcategoria' => $this->idcategoria,
            'iditem' => $this->iditem,
            'orden' => $this->orden,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
