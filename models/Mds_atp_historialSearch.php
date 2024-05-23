<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atp_historial;

/**
 * Mds_atp_historialSearch represents the model behind the search form about `app\models\Mds_atp_historial`.
 */
class Mds_atp_historialSearch extends Mds_atp_historial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[], 'integer'],
            [[], 'safe'],
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
        $query = Mds_atp_historial::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'id_atp_historial' => $this->id_atp_historial,
            'id_atp_solicitud' => $this->id_atp_solicitud,
            'fecha_hora' => $this->fecha_hora,
            'estado_nuevo' => $this->estado_nuevo,
            'estado_anterior' => $this->estado_anterior,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);*/

        return $dataProvider;
    }
}
