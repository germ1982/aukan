<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrganismoDispositivo;

/**
 * OrganismoDispositivoSearch represents the model behind the search form about `app\models\OrganismoDispositivo`.
 */
class OrganismoDispositivoSearch extends OrganismoDispositivo
{
    
    public function rules()
    {
        return [
            [['iddispositivo', 'idcapaitem','idoficina'], 'integer'],
            [['descripcion', 'es_oficial', 'es_organismo', 'activo', 'direccion', 'alias', 'telefono','organismo'], 'safe'],
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
        $query = OrganismoDispositivo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'descripcion',
                    'es_oficial',
                    'es_organismo',
                    'activo',
                    'direccion',
                    'alias',
                    'telefono',
                    'idoficina',
                    // Define el ordenamiento para 'organismo'
                    'organismo' => [
                        'asc' => ['o.descripcion' => SORT_ASC],
                        'desc' => ['o.descripcion' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin('organismo', 'organismo_dispositivo.idorganismo = organismo.idorganismo');
        $query->addSelect([
            'organismo_dispositivo.*', // Selecciona todos los campos de la tabla persona
            "organismo.descripcion as organismo"
        ]);

        

        $query->andFilterWhere([
            'iddispositivo' => $this->iddispositivo,
            'idoficina' => $this->idoficina,
            'idcapaitem' => $this->idcapaitem,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->andFilterWhere(['like', 'es_oficial', $this->es_oficial])
                ->andFilterWhere(['like', 'es_organismo', $this->es_organismo])
                ->andFilterWhere(['like', 'activo', $this->activo])
                ->andFilterWhere(['like', 'direccion', $this->direccion])
                ->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'telefono', $this->telefono])
                ->andFilterWhere(['like', 'organismo.descripcion', $this->organismo]);

        return $dataProvider;
    }
}
