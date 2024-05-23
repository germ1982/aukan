<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Telefonia_vista_linea;

/**
 * Telefonia_vista_lineaSearch represents the model behind the search form about `app\models\Telefonia_vista_linea`.
 */
class Telefonia_vista_lineaSearch extends Telefonia_vista_linea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lineanro', 'cuenta', 'movimientos'], 'integer'],
            [['ultimo_movimiento', 'simcard', 'empresa', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Telefonia_vista_linea::find();

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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "ultimo_movimiento >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "ultimo_movimiento <= '$fecha_hasta_aux'";
        }

        $query->andFilterWhere([
/*             'lineanro' => $this->lineanro,
            'ultimo_movimiento' => $this->ultimo_movimiento,
            'cuenta' => $this->cuenta,
            'movimientos' => $this->movimientos, */
        ]);

        $query->andFilterWhere(['like', 'simcard', $this->simcard])
            ->andFilterWhere(['like', 'empresa', $this->empresa])
            ->andFilterWhere(['like', 'lineanro', $this->lineanro])
            ->andFilterWhere(['like', 'cuenta', $this->cuenta])
            ->andFilterWhere(['like', 'movimientos', $this->movimientos])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
