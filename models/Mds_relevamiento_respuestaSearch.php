<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_relevamiento_respuesta;

/**
 * Mds_relevamiento_respuestaSearch represents the model behind the search form of `app\models\Mds_relevamiento_respuesta`.
 */
class Mds_relevamiento_respuestaSearch extends Mds_relevamiento_respuesta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrelevamientorespuesta', 'idrelevamientoregistro', 'iditem', 'posee', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['detalle', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Mds_relevamiento_respuesta::find();

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
            'idrelevamientorespuesta' => $this->idrelevamientorespuesta,
            'idrelevamientoregistro' => $this->idrelevamientoregistro,
            'iditem' => $this->iditem,
            'posee' => $this->posee,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
