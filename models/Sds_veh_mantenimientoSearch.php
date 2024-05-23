<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_veh_mantenimiento;

/**
 * Sds_veh_mantenimientoSearch represents the model behind the search form about `app\models\Sds_veh_mantenimiento`.
 */
class Sds_veh_mantenimientoSearch extends Sds_veh_mantenimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmantenimiento', 'idvehiculo', 'km'], 'integer'],
            [['fecha', 'detalle'], 'safe'],
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
        $query = Sds_veh_mantenimiento::find();

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
            'idmantenimiento' => $this->idmantenimiento,
            'idvehiculo' => $this->idvehiculo,
            'fecha' => $this->fecha,
            'km' => $this->km,
        ]);

        $query->andFilterWhere(['like', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
