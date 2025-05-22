<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonasNoHomologadas;

/**
 * PersonasNoHomologadasSearch represents the model behind the search form about `app\models\PersonasNoHomologadas`.
 */
class PersonasNoHomologadasSearch extends PersonasNoHomologadas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersona_no_homologada', 'documento_tipo', 'nacionalidad', 'genero'], 'integer'],
            [['documento', 'fecha_nacimiento', 'nombre', 'apellido'], 'safe'],
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
        $query = PersonasNoHomologadas::find();

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
            'idpersona_no_homologada' => $this->idpersona_no_homologada,
            'documento_tipo' => $this->documento_tipo,
            'nacionalidad' => $this->nacionalidad,
            'genero' => $this->genero,
            'fecha_nacimiento' => $this->fecha_nacimiento,
        ]);

        $query->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido]);

        return $dataProvider;
    }
}
