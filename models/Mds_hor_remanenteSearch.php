<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_remanente;

/**
 * Mds_hor_remanenteSearch represents the model behind the search form about `app\models\Mds_hor_remanente`.
 */
class Mds_hor_remanenteSearch extends Mds_hor_remanente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idremanente', 'idcontacto', 'anio', 'dias'], 'integer'],
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
        $query = Mds_hor_remanente::find();

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
            'idremanente' => $this->idremanente,
            'idcontacto' => $this->idcontacto,
            'anio' => $this->anio,
            'dias' => $this->dias,
        ]);

        return $dataProvider;
    }
}
