<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaWebEventos;

/**
 * InformaticaWebEventosSearch represents the model behind the search form about `app\models\InformaticaWebEventos`.
 */
class InformaticaWebEventosSearch extends InformaticaWebEventos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idevento', 'iddispositivo', 'activo'], 'integer'],
            [['fecha', 'titulo', 'descripcion', 'fotos', 'fdesde', 'fhasta'], 'safe'],
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
        $query = InformaticaWebEventos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idevento' => $this->idevento,
            'fecha' => $this->fecha,
            'iddispositivo' => $this->iddispositivo,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'fotos', $this->fotos])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);;

        return $dataProvider;
    }
}
