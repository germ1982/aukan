<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_veh_modelo;

/**
 * Sds_veh_modeloSearch represents the model behind the search form about `app\models\Sds_veh_modelo`.
 */
class Sds_veh_modeloSearch extends Sds_veh_modelo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmodelo', 'idmarca'], 'integer'],
            [['descripcion', 'activo'], 'safe'],
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
        $query = Sds_veh_modelo::find();

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
            'idmodelo' => $this->idmodelo,
            'idmarca' => $this->idmarca,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
