<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Localidades;

/**
 * LocalidadesSearch represents the model behind the search form about `app\models\Localidades`.
 */
class LocalidadesSearch extends Localidades
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_provincia'], 'integer'],
            [['localidad', 'codigo_postal', 'activo'], 'safe'],
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
        $query = Localidades::find();

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
            'id' => $this->id,
            'id_provincia' => $this->id_provincia,
        ]);

        $query->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'codigo_postal', $this->codigo_postal])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
