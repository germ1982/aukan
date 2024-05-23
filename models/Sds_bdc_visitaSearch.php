<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_bdc_visita;

/**
 * Sds_bdc_visitaSearch represents the model behind the search form about `app\models\Sds_bdc_visita`.
 */
class Sds_bdc_visitaSearch extends Sds_bdc_visita
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvisita', 'sector'], 'integer'],
            [['fecha', 'observacion', 'sector_descripcion', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Sds_bdc_visita::find(); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha', 'sector_descripcion', 'observacion'],
                //'defaultOrder' => ['fecha' => SORT_DESC]
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
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->select('v.*, c.descripcion sector_descripcion ');
        $query->from('sds_bdc_visita v');
        $query->innerjoin('sds_com_configuracion c', 'c.idconfiguracion = v.sector');
        $query->andFilterWhere([
            'idvisita' => $this->idvisita,
            'fecha' => $this->fecha,
            'sector' => $this->sector_descripcion,
        ])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
