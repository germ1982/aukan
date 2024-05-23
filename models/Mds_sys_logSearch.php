<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_sys_log;

/**
 * Mds_sys_logSearch represents the model behind the search form about `app\models\Mds_sys_log`.
 */
class Mds_sys_logSearch extends Mds_sys_log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idlog', 'idusuario', 'accion', 'id'], 'integer'],
            [['fecha_hora', 'modulo', 'datos', 'fdesde', 'fhasta'], 'safe'],
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
        $query = Mds_sys_log::find();

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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }

        $query->andFilterWhere([
            'idlog' => $this->idlog,
            'idusuario' => $this->idusuario,
            'accion' => $this->accion,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'datos', $this->datos]);
        if (is_array($this->modulo)) {
            $modulos = array();
            foreach ((array)$this->modulo as $modulo) {
                array_push($modulos, "'" . $modulo . "'");
            }
            $modulo_filter = implode(",", $modulos);
        } else {
            $modulo_filter = "'" . $this->modulo . "'";
        }
        if ($modulo_filter != "''") {
            $query->andWhere('SUBSTRING_INDEX(modulo,\'/\',1) in (' . $modulo_filter . ')');
        }
        $query->andWhere($sql_desde)->andWhere($sql_hasta);
        $query->orderBy(['idlog' => SORT_DESC]);

        return $dataProvider;
    }
}
