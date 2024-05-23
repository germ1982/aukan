<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_contacto_persona;

/**
 * Mds_org_contacto_personaSearch represents the model behind the search form about `app\models\Mds_org_contacto_persona`.
 */
class Mds_org_contacto_personaSearch extends Mds_org_contacto_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legajo', 'dni'], 'integer'],
            [['apellido', 'nombre', 'domicilio', 'localidad', 'in_prov'], 'safe'],
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
        $query = Mds_org_contacto_persona::find();

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
            'legajo' => $this->legajo,
            'dni' => $this->dni,
        ]);

        $query->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'domicilio', $this->domicilio])
            ->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'in_prov', $this->in_prov]);

        return $dataProvider;
    }
}
