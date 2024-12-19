<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MovimVehiOficial;

/**
 * MovimVehiOficialSearch represents the model behind the search form about `app\models\MovimVehiOficial`.
 */
class MovimVehiOficialSearch extends MovimVehiOficial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idvehiculo', 'chofer', 'kilometraje'], 'integer'],
            [['dominio', 'salida', 'regreso', 'finalidad_viaje', 'fecha', 'lugar', 'hora'], 'safe'],
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
        $query = MovimVehiOficial::find();

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
            'idmovimiento' => $this->idmovimiento,
            'idvehiculo' => $this->idvehiculo,
            'chofer' => $this->chofer,
            'salida' => $this->salida,
            'regreso' => $this->regreso,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'kilometraje' => $this->kilometraje,
        ]);

        $query->andFilterWhere(['like', 'dominio', $this->dominio])
            ->andFilterWhere(['like', 'finalidad_viaje', $this->finalidad_viaje])
            ->andFilterWhere(['like', 'lugar', $this->lugar]);

        return $dataProvider;
    }
}
