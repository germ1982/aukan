<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VehiculoOficial;

/**
 * VehiculoOficialSearch represents the model behind the search form about `app\models\VehiculoOficial`.
 */
class VehiculoOficialSearch extends VehiculoOficial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'kilometraje'], 'integer'],
            [['dominio', 'poliza', 'VTO', 'salida', 'llegada', 'lugar', 'hora', 'finalidad_viaje'], 'safe'],
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
        $query = VehiculoOficial::find();

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
            'idvehiculo' => $this->idvehiculo,
            'VTO' => $this->VTO,
            'salida' => $this->salida,
            'llegada' => $this->llegada,
            'hora' => $this->hora,
            'kilometraje' => $this->kilometraje,
        ]);

        $query->andFilterWhere(['like', 'dominio', $this->dominio])
            ->andFilterWhere(['like', 'poliza', $this->poliza])
            ->andFilterWhere(['like', 'lugar', $this->lugar])
            ->andFilterWhere(['like', 'finalidad_viaje', $this->finalidad_viaje]);

        return $dataProvider;
    }
}
