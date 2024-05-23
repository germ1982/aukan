<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_novedad;

/**
 * Mds_org_novedadSearch represents the model behind the search form about `app\models\Mds_org_novedad`.
 */
class Mds_org_novedadSearch extends Mds_org_novedad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idnovedad', 'estado', 'tipo'], 'integer'],
            [['titulo', 'descripcion', 'fdesde', 'fhasta', 'fechahora', 'imagen'], 'safe'],
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
        $query = Mds_org_novedad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['fechahora' => SORT_DESC]
            ]
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
            'idnovedad' => $this->idnovedad,
            'fechahora' => $this->fechahora,
            'estado' => $this->estado,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
