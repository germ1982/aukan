<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_cel_movimiento_linea;

/**
 * Sds_cel_movimiento_lineaSearch represents the model behind the search form about `app\models\Sds_cel_movimiento_linea`.
 */
class Sds_cel_movimiento_lineaSearch extends Sds_cel_movimiento_linea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimientolinea', 'idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'equipo_anterior', 'equipo_nuevo', 'organismo_anterior', 'organismo_nuevo', 'idlinea'], 'integer'],
            [['fecha_hora', 'fdesde','fhasta', 'observaciones', 'adjunto'], 'safe'],
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
        $query = Sds_cel_movimiento_linea::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idmovimientolinea', 'idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'equipo_anterior', 'equipo_nuevo', 'organismo_anterior', 'organismo_nuevo', 'idlinea', 'fecha_hora', 'observaciones'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /* ---------------------------------------------------------------------------------------------------------------------------------------- */
        //esto es para que ande el filtro de las fechas
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            //$fecha_desde_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fdesde)));
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
            //$sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            //$fecha_hasta_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fhasta)));
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
            //$sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }    
        /* ---------------------------------------------------------------------------------------------------------------------------------------- */


        $query->andFilterWhere([
            'idmovimientolinea' => $this->idmovimientolinea,
            'fecha_hora' => $this->fecha_hora,
            'idusuario' => $this->idusuario,
            'solicitante' => $this->solicitante,
            'tipo' => $this->tipo,
            'responsable_anterior' => $this->responsable_anterior,
            'responsable_nuevo' => $this->responsable_nuevo,
            'equipo_anterior' => $this->equipo_anterior,
            'equipo_nuevo' => $this->equipo_nuevo,
            'organismo_anterior' => $this->organismo_anterior,
            'organismo_nuevo' => $this->organismo_nuevo,
            'idlinea' => $this->idlinea,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'adjunto', $this->adjunto])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
