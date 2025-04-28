<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogPlataforma;

/**
 * LogPlataformaSearch represents the model behind the search form about `app\models\LogPlataforma`.
 */
class LogPlataformaSearch extends LogPlataforma
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idlog', 'idusuario', 'modulo', 'accion', 'idregistro'], 'integer'],
            [['fecha', 'hora', 'fdesde', 'fhasta'], 'safe'],
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

     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LogPlataforma::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //se agrega este codigo para que filtre bien en la consulta las fechas desde y hasta
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
            'idlog' => $this->idlog,
            'idusuario' => $this->idusuario,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'modulo' => $this->modulo,
            'accion' => $this->accion,
            'idregistro' => $this->idregistro,
        ]);

        $query->andWhere($sql_desde)//se agrega para el filtrado por fechas
        ->andWhere($sql_hasta)//se agrega para el filtrado por fechas
        ;

        return $dataProvider;
    }
}
