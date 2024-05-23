<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mds_por_sst;

/**
 * mds_por_sstSearch represents the model behind the search form about `app\models\mds_por_sst`.
 */
class mds_por_sstSearch extends mds_por_sst
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mes', 'anio'], 'integer'],
            [['asiento', 'tipo', 'cheque', 'cantidad', 'fecha', 'dni', 'nombre', 'monto', 'PROV', 'CTA', 'LUG', 'destino', 'localidad', 'id_localidad', 'grupo', 'referente', 'pago', 'autorizo', 'observacion', 'situacion', 'retira_cheque', 'sexo', 'apellido', 'liquidacion_anterior'], 'safe'],
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
        $query = mds_por_sst::find();

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
            'id' => $this->id,
            'mes' => $this->mes,
            'anio' => $this->anio,
        ]);

        $query->andFilterWhere(['like', 'asiento', $this->asiento])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'cheque', $this->cheque])
            ->andFilterWhere(['like', 'cantidad', $this->cantidad])
            ->andFilterWhere(['like', 'fecha', $this->fecha])
            ->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'monto', $this->monto])
            ->andFilterWhere(['like', 'PROV', $this->PROV])
            ->andFilterWhere(['like', 'CTA', $this->CTA])
            ->andFilterWhere(['like', 'LUG', $this->LUG])
            ->andFilterWhere(['like', 'destino', $this->destino])
            ->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'id_localidad', $this->id_localidad])
            ->andFilterWhere(['like', 'grupo', $this->grupo])
            ->andFilterWhere(['like', 'referente', $this->referente])
            ->andFilterWhere(['like', 'pago', $this->pago])
            ->andFilterWhere(['like', 'autorizo', $this->autorizo])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'situacion', $this->situacion])
            ->andFilterWhere(['like', 'retira_cheque', $this->retira_cheque])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'liquidacion_anterior', $this->liquidacion_anterior]);

        return $dataProvider;
    }
}
