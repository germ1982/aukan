<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InfIps;

/**
 * InfIpsSearch represents the model behind the search form about `app\models\InfIps`.
 */
class InfIpsSearch extends InfIps
{
    /**
     * @inheritdoc
     */
    public $iddispositivo;
     public function rules()
    {
        return [
            [['idip','idoficina'], 'integer'],
            [['ip', 'idempleado','iddispositivo','observacion'], 'safe'],
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
        $query = InfIps::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'idip' => SORT_ASC,
                    //'nombre' => SORT_ASC,  // Orden predeterminado por apellido y nombre
                ],
                'attributes' => [
                  'idip',
                    'ip',
                    'idempleado',
                    'iddispositivo',
                    'idoficina',
                    'observacion',

                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin('empleado e', 'inf_ips.idempleado = e.idempleado');


        $query->andFilterWhere([
            'idip' => $this->idip,
            'idoficina' => $this->idoficina,
            'e.iddispositivo' => $this->iddispositivo,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'idempleado', $this->idempleado]);


        return $dataProvider;
    }
}
