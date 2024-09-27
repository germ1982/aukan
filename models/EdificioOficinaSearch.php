<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EdificioOficina;

/**
 * EdificioOficinaSearch represents the model behind the search form about `app\models\EdificioOficina`.
 */
class EdificioOficinaSearch extends EdificioOficina
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idoficina', 'idedificio', 'activo'], 'integer'],
            [['descripcion', 'plano_ubicacion'], 'safe'],
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
        $query = EdificioOficina::find();

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
            'idoficina' => $this->idoficina,
            'idedificio' => $this->idedificio,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'plano_ubicacion', $this->plano_ubicacion]);

        return $dataProvider;
    }
}
