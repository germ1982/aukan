<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_inventario;

/**
 * Sds_stk_inventarioSearch represents the model behind the search form about `app\models\Sds_stk_inventario`.
 */
class Sds_stk_inventarioSearch extends Sds_stk_inventario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinventario', 'idusuario', 'iddeposito', 'idorganismo'], 'integer'],
            [['detalle_items'], 'string'],
            [['fecha_hora','fdesde','fhasta',], 'safe'],
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
        $query = Sds_stk_inventario::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_hora', 'idusuario', 'iddeposito','detalle_items'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
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
            $fecha_desde_aux = date_format(
                date_create($this->fdesde . ' 00:00'),
                'Y-m-d H:i'
            );
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(
                date_create($this->fhasta . ' 23:59'),
                'Y-m-d H:i'
            );
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }

        $consulta_detalle_items = "SELECT  
                                    ifnull((SELECT group_concat(concat(ii.cantidad,' ',a.descripcion) SEPARATOR ' <br> ') as detalle  
                                            FROM sds_stk_inventario_item ii
                                            join sds_stk_articulo a on a.idarticulo= ii.idarticulo
                                            where ii.idinventario = i.idinventario
                                            order by a.descripcion),'') 
                                    from sds_stk_inventario i
                                    where i.idinventario = sds_stk_inventario.idinventario";

        $query->addSelect([
        ' `sds_stk_inventario`.*',
        "($consulta_detalle_items)as detalle_items ",
        ]);
        $query->andFilterWhere([
            'idinventario' => $this->idinventario,
            'fecha_hora' => $this->fecha_hora,
            'idusuario' => $this->idusuario,
            'iddeposito' => $this->iddeposito,
            'idorganismo' => $this->idorganismo,
        ]);
        $query

        ->andWhere($sql_desde)
        ->andWhere($sql_hasta)
        ->having("detalle_items like '%" .$this->detalle_items ."%'")
        ;
        return $dataProvider;
    }
}
