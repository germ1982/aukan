<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_saldo;

/**
 * Sds_ent_saldoSearch represents the model behind the search form about `app\models\Sds_ent_saldo`.
 */
class Sds_ent_saldoSearch extends Sds_ent_saldo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo'], 'safe'],
            [['responsable', 'idtipo'], 'integer'],
            [['ingresos', 'egresos', 'saldo'], 'number'],
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
        $query = Sds_ent_saldo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->addSelect(["codigo","responsable","idtipo", "sum(ingresos) ingresos","sum(egresos) egresos","sum(saldo) saldo"]);
        $query->andFilterWhere([
            'responsable' => $this->responsable,
            'idtipo' => $this->idtipo,
            'ingresos' => $this->ingresos,
            'egresos' => $this->egresos,
            'saldo' => $this->saldo,
        ]);

        $query->andFilterWhere(['like', 'codigo', $this->codigo]);
        $query->groupBy("codigo");

        return $dataProvider;
    }
}
