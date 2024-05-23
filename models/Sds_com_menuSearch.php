<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_menu;

/**
 * Sds_com_menuSearch represents the model behind the search form about `app\models\Sds_com_menu`.
 */
class Sds_com_menuSearch extends Sds_com_menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmenu', 'padre', 'iditem', 'orden'], 'integer'],
            [['descripcion', 'ruta', 'icono'], 'safe'],
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
        $query = Sds_com_menu::find();

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
            'idmenu' => $this->idmenu,
            'padre' => $this->padre,
            'iditem' => $this->iditem,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'ruta', $this->ruta])
            ->andFilterWhere(['like', 'icono', $this->icono])
            ->orderBy(['padre'=>SORT_ASC,'orden'=> SORT_ASC]);

        return $dataProvider;
    }
}
