<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Persona;

/**
 * PersonaSearch represents the model behind the search form of `app\models\Persona`.
 */
class PersonaSearch extends Persona
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'conviviente', 'idlocalidad'], 'integer'],
            [['fecha_nacimiento', 'nombre', 'apellido', 'domicilio', 'domicilio_calle', 'domicilio_numero'], 'safe'],
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
        $query = Persona::find();

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
            'idpersona' => $this->idpersona,
            'documento' => $this->documento,
            'documento_tipo' => $this->documento_tipo,
            'nacionalidad' => $this->nacionalidad,
            'genero' => $this->genero,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'padre' => $this->padre,
            'conviviente' => $this->conviviente,
            'idlocalidad' => $this->idlocalidad,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'domicilio', $this->domicilio])
            ->andFilterWhere(['like', 'domicilio_calle', $this->domicilio_calle])
            ->andFilterWhere(['like', 'domicilio_numero', $this->domicilio_numero]);

        return $dataProvider;
    }
}
