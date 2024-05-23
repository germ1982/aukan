<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_feriado;

/**
 * Mds_hor_feriadoSearch represents the model behind the search form about `app\models\Mds_hor_feriado`.
 */
class Mds_hor_feriadoSearch extends Mds_hor_feriado
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idferiado'], 'integer'],
            [['fecha', 'descripcion', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Mds_hor_feriado::find();

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
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }

        $query->andFilterWhere([
            'idferiado' => $this->idferiado,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
