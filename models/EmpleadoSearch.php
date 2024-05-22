<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empleado;

/**
 * EmpleadoSearch represents the model behind the search form of `app\models\Empleado`.
 */
class EmpleadoSearch extends Empleado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idempleado', 'idpersona', 'iddispositivo', 'legajo', 'activo', 'categoria', 'antiguedad_legal', 'antiguedad_total', 'contratacion', 'cuil', 'funcion', 'fichado', 'afiliacion'], 'integer'],
            [['email', 'telefono', 'foto', 'ingreso_real', 'ingreso_administrativo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Empleado::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idempleado' => $this->idempleado,
            'idpersona' => $this->idpersona,
            'iddispositivo' => $this->iddispositivo,
            'legajo' => $this->legajo,
            'activo' => $this->activo,
            'categoria' => $this->categoria,
            'antiguedad_legal' => $this->antiguedad_legal,
            'antiguedad_total' => $this->antiguedad_total,
            'ingreso_real' => $this->ingreso_real,
            'ingreso_administrativo' => $this->ingreso_administrativo,
            'contratacion' => $this->contratacion,
            'cuil' => $this->cuil,
            'funcion' => $this->funcion,
            'fichado' => $this->fichado,
            'afiliacion' => $this->afiliacion,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'foto', $this->foto]);

        return $dataProvider;
    }
}
