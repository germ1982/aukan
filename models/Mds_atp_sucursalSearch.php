<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atp_sucursal;

/**
 * Mds_atp_sucursalSearch represents the model behind the search form about `app\models\Mds_atp_sucursal`.
 */
class Mds_atp_sucursalSearch extends Mds_atp_sucursal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idsucursal', 'codigo'], 'integer'],
            [['direccion'], 'safe'],
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
        $query = Mds_atp_sucursal::find();

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
            'idsucursal' => $this->idsucursal,
            'codigo' => $this->codigo,
        ]);

        $query->andFilterWhere(['like', 'direccion', $this->direccion]);

        return $dataProvider;
    }
}
