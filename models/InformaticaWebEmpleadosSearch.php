<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaWebEmpleados;

/**
 * InformaticaWebEmpleadosSearch represents the model behind the search form about `app\models\InformaticaWebEmpleados`.
 */
class InformaticaWebEmpleadosSearch extends InformaticaWebEmpleados
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idwebempleado', 'idempleado', 'orden'], 'integer'],
            [['descripcion', 'activo'], 'safe'],
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
        $query = InformaticaWebEmpleados::find();

    // Realiza el join con la tabla empleados y luego con la tabla personas
    $query->joinWith([
      'empleado.persona' => function ($query) {
          $query->from(['personas' => 'personas']); // Alias 'personas'
      }
  ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'orden' => SORT_ASC,  // Orden predeterminado por apellido y nombre
                ],
                'attributes' => [
                  'idwebempleado',
                  'orden',
                    'idempleado' => [
                        'asc' => ['personas.apellido' => SORT_ASC, 'personas.nombre' => SORT_ASC],
                        'desc' => ['personas.apellido' => SORT_DESC, 'personas.nombre' => SORT_DESC],
                    ],
                    'activo', // Otras columnas para ordenar
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idwebempleado' => $this->idwebempleado,
            'idempleado' => $this->idempleado,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
