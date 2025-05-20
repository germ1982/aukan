<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockDepositoEgreso;

/**
 * StockDepositoEgresoSearch represents the model behind the search form about `app\models\StockDepositoEgreso`.
 */
class StockDepositoEgresoSearch extends StockDepositoEgreso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idegreso', 'idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe'], 'integer'],
            [['fecha', 'observacion', 'fdesde', 'fhasta'], 'safe'],
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
        $query = StockDepositoEgreso::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],// <-- ¡Aquí cambias la cantidad de registros por página!
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idegreso' => $this->idegreso,
            'fecha' => $this->fecha,
            'idpersona_solicitante' => $this->idpersona_solicitante,
            'idempleado_autorizacion' => $this->idempleado_autorizacion,
            'idempleado_despacha' => $this->idempleado_despacha,
            'idpersona_recibe' => $this->idpersona_recibe,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
