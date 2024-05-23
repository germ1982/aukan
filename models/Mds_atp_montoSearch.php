<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atp_monto;

/**
 * Mds_atp_montoSearch represents the model behind the search form about `app\models\Mds_atp_monto`.
 */
class Mds_atp_montoSearch extends Mds_atp_monto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmonto', 'idusuario', 'estado'], 'integer'],
            [['fechahora', 'path', 'observaciones'], 'safe'],
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
        $query = Mds_atp_monto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idmonto' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idmonto' => $this->idmonto,
            'fechahora' => $this->fechahora,
            'idusuario' => $this->idusuario,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
