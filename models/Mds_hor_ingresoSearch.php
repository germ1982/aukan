<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_ingreso;

/**
 * Mds_hor_ingresoSearch represents the model behind the search form about `app\models\Mds_hor_ingreso`.
 */
class Mds_hor_ingresoSearch extends Mds_hor_ingreso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idingreso', 'idcontacto'], 'integer'],
            [['fdesde', 'fhasta', 'fecha_hora', 'observaciones'], 'safe'],
            [['temperatura'], 'number'],
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
        $query = Mds_hor_ingreso::find();

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

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idingreso' => $this->idingreso,
            'idcontacto' => $this->idcontacto,
            'temperatura' => $this->temperatura,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->orderBy(["fecha_hora"=>SORT_DESC]);

        return $dataProvider;
    }
}
