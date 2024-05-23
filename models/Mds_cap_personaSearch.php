<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_persona;

/**
 * Mds_cap_personaSearch represents the model behind the search form about `app\models\Mds_cap_persona`.
 */
class Mds_cap_personaSearch extends Mds_cap_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersona', 'ultimo_año', 'localidad', 'dni'], 'integer'],
            [['telefono', 'mail', 'dni', 'nombrecompuesto', 'nombrecompuesto'], 'safe'],
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
        $query = Mds_cap_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['dni', 'telefono', 'nombre', 'apellido', 'mail', 'idpersona', 'nombrecompuesto'],

            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect(['cp.*', 'pers.documento as dni', 'concat(pers.apellido,\', \',pers.nombre) nombrecompuesto']);
        $query->from('mds_cap_persona as cp');
        $query->join('join', 'sds_com_persona pers', 'pers.idpersona=cp.idpersona');
        $query->andFilterWhere([

            'idpersonacap' => $this->idpersonacap,
            'idpersona' => $this->idpersona,
            'ultimo_año' => $this->ultimo_año,
            'localidad' => $this->localidad,

        ]);

        $query->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'ultimo_año', $this->ultimo_año])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->having("dni like '%" . $this->dni . "%' and nombrecompuesto like '%" . $this->nombrecompuesto . "%'");
        return $dataProvider;
    }
}
