<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atp_alta;

/**
 * Mds_atp_altaSearch represents the model behind the search form about `app\models\Mds_atp_alta`.
 */
class Mds_atp_altaSearch extends Mds_atp_alta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idalta', 'idusuario', 'estado'], 'integer'],
            [['fechahora', 'path', 'observaciones'], 'safe'],
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
        $query = Mds_atp_alta::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idalta' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d H:i:s');
            $sql_desde = "fechahora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d H:i:s');
            $sql_hasta = "fechahora <= '$fecha_hasta_aux'";
        } 

        $query->andFilterWhere([
            'idalta' => $this->idalta,
            'fechahora' => $this->fechahora,
            'idusuario' => $this->idusuario,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
