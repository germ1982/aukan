<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Telefonia_vista_titular;

/**
 * Telefonia_vista_titularSearch represents the model behind the search form about `app\models\Telefonia_vista_titular`.
 */
class Telefonia_vista_titularSearch extends Telefonia_vista_titular
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lineanro', 'movimientos'], 'integer'],
            [['ultimo_movimiento', 'organismo', 'dependencia', 'responsable', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Telefonia_vista_titular::find();

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
            'movimientos' => $this->movimientos,
        ]);

        $query->andFilterWhere(['like', 'organismo', $this->organismo])
            ->andFilterWhere(['like', 'dependencia', $this->dependencia])
            ->andFilterWhere(['like', 'responsable', $this->responsable])
            ->andFilterWhere(['like', 'lineanro', $this->lineanro])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
