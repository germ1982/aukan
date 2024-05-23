<?php

namespace app\models;

use Yii;
use app\controllers\Sds_cel_movimientoController;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Telefonia_vista_integradora_optic;

/**
 * Telefonia_vista_integradoraSearch represents the model behind the search form about `app\models\Telefonia_vista_integradora`.
 */
class Telefonia_vista_integradoraSearch extends Telefonia_vista_integradora_optic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lineanro', 'cuenta','baja','linea'], 'integer'],
            [['empresa', 'ultimo_movimiento', 'organismo', 'dependecia', 'responsable', 'equipo', 'imei', 'plan', 'fdesde', 'fhasta','baja','linea'], 'safe'],
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
        $query = Telefonia_vista_integradora::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['lineanro', 'linea','cuenta', 'ultimo_movimiento', 'organismo', 'dependecia', 'responsable', 'imei','plan','baja'],
                'defaultOrder' => ['lineanro' => SORT_ASC]
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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "ultimo_movimiento >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "ultimo_movimiento <= '$fecha_hasta_aux'";
        }

        if ($this->baja==0)
        { $having_baja = '(baja IS NULL or baja = 0)'; }

        if ($this->baja==null)
            { $having_baja = '(baja IS NULL or baja >= 0)'; }

        if ($this->baja==1)
            { $having_baja = '(baja = 1)'; }

        if ($this->linea==null)
            {$having_linea = '(linea IS NULL or linea >= 0)';}
        else
            {$having_linea = "(linea like '%$this->linea%' or lineanro like '%$this->linea%')"; }

        

        $mysql_id_ultimo_movimiento = "(SELECT MAX(m.idmovimiento) from mdsyt.sds_cel_movimiento m where m.linea = vista_integradora.lineanro)";
        $mysql_baja = "(SELECT c.baja from mdsyt.sds_cel_movimiento c where c.idmovimiento = $mysql_id_ultimo_movimiento)";

        $mysql_numero_actual="(Select l.numero from mdsyt.sds_cel_movimiento l Where l.idmovimiento = $mysql_id_ultimo_movimiento)";

        
        
        $query->addSelect(['vista_integradora.*',"$mysql_baja as baja","$mysql_numero_actual as linea"]);

        $query->andFilterWhere([


        ]);

        $query->andFilterWhere(['like', 'empresa', $this->empresa])
            ->andFilterWhere(['like', 'lineanro', $this->lineanro])
            ->andFilterWhere(['like', 'cuenta', $this->cuenta])
            ->andFilterWhere(['like', 'organismo', $this->organismo])
            ->andFilterWhere(['like', 'dependecia', $this->dependecia])
            ->andFilterWhere(['like', 'responsable', $this->responsable])
            ->andFilterWhere(['like', 'equipo', $this->equipo])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'plan', $this->plan])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);
            $query->having($having_baja." and ".$having_linea);

        return $dataProvider;
    }
}
