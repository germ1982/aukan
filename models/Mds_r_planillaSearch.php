<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_r_planilla;

/**
 * Mds_r_planillaSearch represents the model behind the search form about `app\models\Mds_r_planilla`.
 */
class Mds_r_planillaSearch extends Mds_r_planilla
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idplanilla', 'idorganismo', 'idusuario', 'mes', 'anio', 'idplantilla', 'periodo'], 'integer'],
            [['fecha_desde', 'fecha_hasta'], 'safe'],
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
        $query = Mds_r_planilla::find();
        //$query = Mds_r_planilla::find()
        //->where(['activo'=> 1]) 
        //->all();
       

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
            //esto es para que ande el filtro de las fecha_inscripcion

            $sql_desde = '';
            $sql_hasta = '';
            if ($this->fecha_desde != null) {
                $fecha_desde_aux = date_format(date_create($this->fecha_desde), 'Y-m-d');
                $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
            }
            if ($this->fecha_hasta != null) {
                $fecha_hasta_aux = date_format(date_create($this->fecha_hasta), 'Y-m-d');
                $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
            }
        $query->andWhere("activo=1");

        $query->andFilterWhere([
            'idplanilla' => $this->idplanilla,
            'idorganismo' => $this->idorganismo,
            'idusuario' => $this->idusuario,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'idplantilla' => $this->idplantilla,
            'periodo' => $this->periodo,
        ]);

        return $dataProvider;
    }
}
