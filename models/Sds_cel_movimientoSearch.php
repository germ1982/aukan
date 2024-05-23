<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_cel_movimiento;

/**
 * Sds_cel_movimientoSearch represents the model behind the search form about `app\models\Sds_cel_movimiento`.
 */
class Sds_cel_movimientoSearch extends Sds_cel_movimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'linea', 'numero', 'organismo'], 'integer'],
            [['observaciones', 'baja', 'fecha', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Sds_cel_movimiento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            $fecha_desde_aux = date_format(date_create(str_replace('/','-',$this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/','-',$this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idmovimiento' => $this->idmovimiento,
            'linea' => $this->linea,
            'numero' => $this->numero,
            'organismo' => $this->organismo,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'baja', $this->baja])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
