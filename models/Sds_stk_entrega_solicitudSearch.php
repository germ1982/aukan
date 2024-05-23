<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_entrega_solicitud;
use app\models\Mds_hor_registro;
/**
 * Sds_stk_entrega_solicitudSearch represents the model behind the search form about `app\models\Sds_stk_entrega_solicitud`.
 */
class Sds_stk_entrega_solicitudSearch extends Sds_stk_entrega_solicitud
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identregasolicitud', 'idorganismo', 'idcontacto', 'idpersona', 'dni', 'identrega'], 'integer'],
            [['fecha_hora', 'observaciones','fdesde','fhasta'], 'safe'],
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
        $query = Sds_stk_entrega_solicitud::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //boolean comprueba si id entrega es null
        if($this->identrega=='0'){
            $query->andWhere(['identrega'=>null]);
        }
        if($this->identrega=='1'){
            $query->andWhere(['not', ['identrega'=>null]]);
        }
        //fecha
        $sql_desde = '';
        $sql_hasta = '';
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d H:i:s');
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d H:i:s');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }

        $query->andWhere($sql_desde)->andWhere($sql_hasta);
        
        $this->idorganismo= Yii::$app->user->identity->organismo_stock;
        
        $query->andFilterWhere([
            'identregasolicitud' => $this->identregasolicitud,
            'idorganismo' => $this->idorganismo,
            'idcontacto' => $this->idcontacto,
            'idpersona' => $this->idpersona,
            'dni' => $this->dni,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->orderBy(['fecha_hora' => SORT_DESC]);
        return $dataProvider;
    }
}
