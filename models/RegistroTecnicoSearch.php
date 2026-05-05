<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroTecnico;

/**
 * RegistroTecnicoSearch represents the model behind the search form about `app\models\RegistroTecnico`.
 */
class RegistroTecnicoSearch extends RegistroTecnico
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idregistro', 'idsolicitante', 'iddispositivo', 'idtipo_registro'], 'integer'],
            [['fecha_solicitud', 'problema', 'solucion', 'fecha_solucion'], 'safe'],
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
        $query = RegistroTecnico::find();

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
            'idregistro' => $this->idregistro,
            'fecha_solicitud' => $this->fecha_solicitud,
            'idsolicitante' => $this->idsolicitante,
            'iddispositivo' => $this->iddispositivo,
            'idtipo_registro' => $this->idtipo_registro,
            'fecha_solucion' => $this->fecha_solucion,
        ]);

        $query->andFilterWhere(['like', 'problema', $this->problema])
            ->andFilterWhere(['like', 'solucion', $this->solucion]);

        return $dataProvider;
    }
}
