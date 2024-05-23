<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_r_ejidos;

/**
 * Mds_r_ejidosSearch represents the model behind the search form about `app\models\Mds_r_ejidos`.
 */
class Mds_r_ejidosSearch extends Mds_r_ejidos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idejido', 'id_departamento', 'idlocalidad'], 'integer'],
            [['ejido', 'departamento'], 'safe'],
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
        $query = Mds_r_ejidos::find();

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
            'idejido' => $this->idejido,
            'id_departamento' => $this->id_departamento,
            'idlocalidad' => $this->idlocalidad,
        ]);

        $query->andFilterWhere(['like', 'ejido', $this->ejido])
            ->andFilterWhere(['like', 'departamento', $this->departamento]);

        return $dataProvider;
    }
}
