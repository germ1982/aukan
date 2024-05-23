<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_devolucion;

/**
 * Sds_stk_devolucionSearch represents the model behind the search form about `app\models\Sds_stk_devolucion`.
 */
class Sds_stk_devolucionSearch extends Sds_stk_devolucion
{
    public $fdesdee;
    public $fhastae;
    public $fdesded;
    public $fhastad;
    public function rules()
    {
        return [
            [['iddevolucion', 'idorganismo', 'idarticulo', 'destinatario', 'responsable_entrega', 'responsable_devolucion', 'estado'], 'integer'],
            [['fecha_hora_entrega', 'observaciones_entrega', 'observaciones_devolucion', 'fecha_hora_devolucion','fdesdee','fhastae','fdesded','fhastad'], 'safe'],
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
        $query = Sds_stk_devolucion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_desdee = '';
        $sql_hastae = '';
        if ($this->fdesdee != null) {
            $fecha_desdee_aux = date_format(date_create(str_replace('/', '-', $this->fdesdee)), 'Y-m-d');
            $sql_desdee = "DATEDIFF(fecha_hora_entrega,'$fecha_desdee_aux')>=0 ";
        }
        if ($this->fhastae != null) {
            $fecha_hastae_aux = date_format(date_create(str_replace('/', '-', $this->fhastae)), 'Y-m-d');
            $sql_hastae = "DATEDIFF(fecha_hora_entrega,'$fecha_hastae_aux')<=0 ";
        }

        $sql_desded = '';
        $sql_hastad = '';
        if ($this->fdesded != null) {
            $fecha_desded_aux = date_format(date_create(str_replace('/', '-', $this->fdesded)), 'Y-m-d');
            $sql_desded = "DATEDIFF(fecha_hora_devolucion,'$fecha_desded_aux')>=0 ";
        }
        if ($this->fhastad != null) {
            $fecha_hastad_aux = date_format(date_create(str_replace('/', '-', $this->fhastad)), 'Y-m-d');
            $sql_hastad = "DATEDIFF(fecha_hora_devolucion,'$fecha_hastad_aux')<=0 ";
        }

        $query->andFilterWhere([
            'iddevolucion' => $this->iddevolucion,
            'idorganismo' => $this->idorganismo,
            'idarticulo' => $this->idarticulo,
            'destinatario' => $this->destinatario,
            'responsable_entrega' => $this->responsable_entrega,
            'responsable_devolucion' => $this->responsable_devolucion,
            'estado' => $this->estado,
        ])

        ->andWhere($sql_desdee)
        ->andWhere($sql_hastae)
        ->andWhere($sql_desded)
        ->andWhere($sql_hastad);

        $query->andFilterWhere(['like', 'observaciones_entrega', $this->observaciones_entrega])
            ->andFilterWhere(['like', 'observaciones_devolucion', $this->observaciones_devolucion]);

        return $dataProvider;
    }
}
