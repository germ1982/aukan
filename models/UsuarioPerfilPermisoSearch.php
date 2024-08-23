<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsuarioPerfilPermiso;

/**
 * UsuarioPerfilPermisoSearch represents the model behind the search form about `app\models\UsuarioPerfilPermiso`.
 */
class UsuarioPerfilPermisoSearch extends UsuarioPerfilPermiso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpermiso', 'idperfil', 'idtipopermiso'], 'integer'],
            [['modulo', 'item', 'descripcion'], 'safe'],
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
        $query = UsuarioPerfilPermiso::find();

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
            'idpermiso' => $this->idpermiso,
            'idperfil' => $this->idperfil,
            'idtipopermiso' => $this->idtipopermiso,
        ]);

        $query->andFilterWhere(['like', 'modulo', $this->modulo])
            ->andFilterWhere(['like', 'item', $this->item])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
