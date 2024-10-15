<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Automotores;

/**
 * AutomotoresSearch represents the model behind the search form about `app\models\Automotores`.
 */
class AutomotoresSearch extends Automotores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvehiculo', 'idempleado', 'idpersona', 'idmarca'], 'integer'],
            [['dominio', 'modelo', 'color', 'vehiculo_oficial'], 'safe'],
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
        $query = Automotores::find();

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
            'idvehiculo' => $this->idvehiculo,
            'idempleado' => $this->idempleado,
            'idpersona' => $this->idpersona,
            'idmarca' => $this->idmarca,
        ]);

        $query->andFilterWhere(['like', 'dominio', $this->dominio])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'vehiculo_oficial', $this->vehiculo_oficial]);

        return $dataProvider;
    }
}
