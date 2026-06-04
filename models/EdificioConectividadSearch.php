<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EdificioConectividad;

/**
 * EdificioConectividadSearch represents the model behind the search form about `app\models\EdificioConectividad`.
 */
class EdificioConectividadSearch extends EdificioConectividad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idconectividad', 'idedificio', 'infraestructura', 'servicio', 'velocidad_en_mb', 'estado', 'tipo_conexion'], 'integer'],
            [['observacion'], 'safe'],
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
        $query = EdificioConectividad::find();

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
            'idconectividad' => $this->idconectividad,
            'idedificio' => $this->idedificio,
            'infraestructura' => $this->infraestructura,
            'servicio' => $this->servicio,
            'velocidad_en_mb' => $this->velocidad_en_mb,
            'estado' => $this->estado,
            'tipo_conexion' => $this->tipo_conexion,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
