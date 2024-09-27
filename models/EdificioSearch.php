<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Edificio;

/**
 * EdificioSearch represents the model behind the search form about `app\models\Edificio`.
 */
class EdificioSearch extends Edificio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idedificio', 'idlocalidad', 'direccion_altura', 'activo'], 'integer'],
            [['descripcion_fija', 'descripcion_gestion', 'direccion_calle', 'direccion', 'geolocalizacion'], 'safe'],
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
        $query = Edificio::find();

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
            'idedificio' => $this->idedificio,
            'idlocalidad' => $this->idlocalidad,
            'direccion_altura' => $this->direccion_altura,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['like', 'descripcion_fija', $this->descripcion_fija])
            ->andFilterWhere(['like', 'descripcion_gestion', $this->descripcion_gestion])
            ->andFilterWhere(['like', 'direccion_calle', $this->direccion_calle])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'geolocalizacion', $this->geolocalizacion]);

        return $dataProvider;
    }
}
