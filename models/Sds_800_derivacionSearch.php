<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_derivacion;

/**
 * Sds_800_derivacionSearch represents the model behind the search form about `app\models\Sds_800_derivacion`.
 */
class Sds_800_derivacionSearch extends Sds_800_derivacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idderivacion'], 'integer'],
            [['descripcion', 'direccion', 'telefonos', 'activo'], 'safe'],
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
        $query = Sds_800_derivacion::find();

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
            'idderivacion' => $this->idderivacion,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefonos', $this->telefonos])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
