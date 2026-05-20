<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConfiguracionDiccionario;

/**
 * ConfiguracionDiccionarioSearch represents the model behind the search form about `app\models\ConfiguracionDiccionario`.
 */
class ConfiguracionDiccionarioSearch extends ConfiguracionDiccionario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcorreccion'], 'integer'],
            [['palabra_mal', 'palabra_correcta'], 'safe'],
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
        $query = ConfiguracionDiccionario::find();

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
            'idcorreccion' => $this->idcorreccion,
        ]);

        $query->andFilterWhere(['like', 'palabra_mal', $this->palabra_mal])
            ->andFilterWhere(['like', 'palabra_correcta', $this->palabra_correcta]);

        return $dataProvider;
    }
}
