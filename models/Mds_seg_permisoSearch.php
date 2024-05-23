<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_seg_permiso;

/**
 * Mds_seg_permisoSearch represents the model behind the search form about `app\models\Mds_seg_permiso`.
 */
class Mds_seg_permisoSearch extends Mds_seg_permiso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpermiso', 'idrol', 'iditem'], 'integer'],
            [['descripcion', 'alta', 'baja', 'modifica', 'ver'], 'safe'],
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
        $query = Mds_seg_permiso::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idpermiso' => $this->idpermiso,
            'idrol' => $this->idrol,
            'iditem' => $this->iditem,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'alta', $this->alta])
            ->andFilterWhere(['like', 'baja', $this->baja])
            ->andFilterWhere(['like', 'modifica', $this->modifica])
            ->andFilterWhere(['like', 'ver', $this->ver]);

        return $dataProvider;
    }
}
