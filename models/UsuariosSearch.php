<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuarios;

class UsuariosSearch extends Usuarios
{
    public function rules()
    {
        return [
            [['id', 'status', 'activo', 'idpersona'], 'integer'],
            [['email', 'avatar', 'password'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Usuarios::find();
        
        // Asegúrate de que 'persona' sea el nombre de la relación correcta en el modelo Usuarios
        $query->joinWith(['persona' => function ($query) {
            $query->from(['personas' => 'personas']); // Alias 'personas'
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'idpersona' => SORT_ASC,  // Orden predeterminado por apellido y nombre
                ],
                'attributes' => [
                    'idpersona' => [
                        'asc' => ['personas.apellido' => SORT_ASC, 'personas.nombre' => SORT_ASC],
                        'desc' => ['personas.apellido' => SORT_DESC, 'personas.nombre' => SORT_DESC],
                    ],
                    'email',
                    'activo', // Otras columnas para ordenar
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'activo' => $this->activo,
            'idpersona' => $this->idpersona,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
}
