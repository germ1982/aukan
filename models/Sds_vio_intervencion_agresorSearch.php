<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_vio_intervencion_agresor;

/**
 * Sds_vio_intervencion_agresorSearch represents the model behind the search form about `app\models\Sds_vio_intervencion_agresor`.
 */
class Sds_vio_intervencion_agresorSearch extends Sds_vio_intervencion_agresor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idagresor', 'parentezco'], 'integer'],
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
        $query = Sds_vio_intervencion_agresor::find();

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
            'idintervencion' => $this->idintervencion,
            'idagresor' => $this->idagresor,
            'parentezco' => $this->parentezco,
        ]);

        return $dataProvider;
    }
}
