<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_padron;

/**
 * Mds_org_padronSearch represents the model behind the search form about `app\models\Mds_org_padron`.
 */
class Mds_org_padronSearch extends Mds_org_padron
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpadron', 'mes', 'anio', 'legajo', 'idunidadoperativa'], 'integer'],
            [['categoria', 'apellido_nombre', 'sexo', 'dni', 'cuil', 'fecha_nacimiento', 'fecha_ingreso', 'eventual'], 'safe'],
            [['antiguedad_administrativa', 'antiguedad_privada', 'antiguedad_total'], 'number'],
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
        $query = Mds_org_padron::find();

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
            'idpadron' => $this->idpadron,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'legajo' => $this->legajo,
            'idunidadoperativa' => $this->idunidadoperativa,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'fecha_ingreso' => $this->fecha_ingreso,
            'antiguedad_administrativa' => $this->antiguedad_administrativa,
            'antiguedad_privada' => $this->antiguedad_privada,
            'antiguedad_total' => $this->antiguedad_total,
        ]);

        $query->andFilterWhere(['like', 'categoria', $this->categoria])
            ->andFilterWhere(['like', 'apellido_nombre', $this->apellido_nombre])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'cuil', $this->cuil])
            ->andFilterWhere(['like', 'eventual', $this->eventual]);

        return $dataProvider;
    }
}
