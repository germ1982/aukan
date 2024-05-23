<?php

namespace app\models;

use Yii;
use app\controllers\Sds_cel_movimientoController;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Telefonia_vista_integradora_optic;

/**
 * Telefonia_vista_integradoraSearch represents the model behind the search form about `app\models\Telefonia_vista_integradora`.
 */
class Telefonia_vista_integradora_opticSearch extends Telefonia_vista_integradora_optic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lineanro', 'cuenta','baja','linea'], 'integer'],
            [['empresa','ultimo_movimiento','organismo','dependencia','responsable', 'equipo', 'imei', 'plan', 'fdesde', 'fhasta','baja','linea'], 'safe'],
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
        $query = Telefonia_vista_integradora::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['lineanro', 'cuenta', 'ultimo_movimiento', 'organismo', 'dependecia', 'responsable', 'imei','plan','baja'],
                'defaultOrder' => ['lineanro' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }        

        return $dataProvider;
    }
}
