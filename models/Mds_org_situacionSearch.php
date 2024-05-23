<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_situacion;

/**
 * Mds_org_situacionSearch represents the model behind the search form about `app\models\Mds_org_situacion`.
 */
class Mds_org_situacionSearch extends Mds_org_situacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idsituacion', 'idcontacto', 'idcapaitem', 'iddocumento'], 'integer'],
            [['inicio', 'fin', 'descripcion', 'profesional_firma', 'dias_horarios'], 'safe'],
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
        $query = Mds_org_situacion::find();

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
            'idsituacion' => $this->idsituacion,
            'idcontacto' => $this->idcontacto,
            'idcapaitem' => $this->idcapaitem,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'iddocumento' => $this->iddocumento,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'profesional_firma', $this->profesional_firma])
            ->andFilterWhere(['like', 'dias_horarios', $this->dias_horarios]);

        return $dataProvider;
    }
}
