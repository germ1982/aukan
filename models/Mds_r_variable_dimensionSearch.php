<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_r_variable_dimension;

/**
 * Mds_r_variable_dimensionSearch represents the model behind the search form about `app\models\Mds_r_variable_dimension`.
 */
class Mds_r_variable_dimensionSearch extends Mds_r_variable_dimension
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvardimension', 'idplanilla', 'idvariable', 'origen', 'iddimension', 'mapear', 'tipomapa'], 'integer'],
            [['fecha_carga', 'fecha_actualizacion'], 'safe'],
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
        $query = Mds_r_variable_dimension::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere("activo=1");
        $query->andFilterWhere([
            'idvardimension' => $this->idvardimension,
            'idplanilla' => $this->idplanilla,
            'idvariable' => $this->idvariable,
            'origen' => $this->origen,
            'iddimension' => $this->iddimension,
            'fecha_carga' => $this->fecha_carga,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'mapear' => $this->mapear,
            'tipomapa' => $this->tipomapa,
        ]);

        return $dataProvider;
    }
    public function search2($params,$idplanilla)
    {
        $query = Mds_r_variable_dimension::find()
        ->where(['idplanilla' => $idplanilla,"activo" => 1]);

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
            'idvardimension' => $this->idvardimension,
            'idplanilla' => $this->idplanilla,
            'idvariable' => $this->idvariable,
            'origen' => $this->origen,
            'iddimension' => $this->iddimension,
            'fecha_carga' => $this->fecha_carga,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'mapear' => $this->mapear,
            'tipomapa' => $this->tipomapa,
        ]);

        return $dataProvider;
    }
}
