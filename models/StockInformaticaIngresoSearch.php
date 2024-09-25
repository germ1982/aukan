<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockInformaticaIngreso;

/**
 * StockInformaticaIngresoSearch represents the model behind the search form about `app\models\StockInformaticaIngreso`.
 */
class StockInformaticaIngresoSearch extends StockInformaticaIngreso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idingreso', 'idorigen', 'idempleado_recepcion', 'idusuario_carga'], 'integer'],
            [['fecha', 'origen_referencia', 'observacion'], 'safe'],
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
        $query = StockInformaticaIngreso::find();

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
            'idingreso' => $this->idingreso,
            'fecha' => $this->fecha,
            'idorigen' => $this->idorigen,
            'idempleado_recepcion' => $this->idempleado_recepcion,
            'idusuario_carga' => $this->idusuario_carga,
        ]);

        $query->andFilterWhere(['like', 'origen_referencia', $this->origen_referencia])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
