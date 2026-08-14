<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaIp;

class InformaticaIpSearch extends InformaticaIp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idip', 'iddispositivo_red', 'mascara', 'puerta_enlace', 'dns', 'usada'], 'integer'],
            [['ip', 'mac', 'observacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = InformaticaIp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idip' => $this->idip,
            'iddispositivo_red' => $this->iddispositivo_red,
            'mascara' => $this->mascara,
            'puerta_enlace' => $this->puerta_enlace,
            'dns' => $this->dns,
            'usada' => $this->usada,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}