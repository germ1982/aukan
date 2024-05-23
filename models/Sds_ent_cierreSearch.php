<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_cierre;

/**
 * Sds_ent_cierreSearch represents the model behind the search form about `app\models\Sds_ent_cierre`.
 */
class Sds_ent_cierreSearch extends Sds_ent_cierre
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcierre', 'identrega', 'cantidad', 'motivo', 'numero'], 'integer'],
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
        $query = Sds_ent_cierre::find();

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
            'idcierre' => $this->idcierre,
            'identrega' => $this->identrega,
            'cantidad' => $this->cantidad,
            'motivo' => $this->motivo,
            'numero' => $this->numero,
        ]);

        return $dataProvider;
    }
}
