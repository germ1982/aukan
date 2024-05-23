<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_orden_compra;

/**
 * Sds_stk_orden_compraSearch represents the model behind the search form about `app\models\Sds_stk_orden_compra`.
 */
class Sds_stk_orden_compraSearch extends Sds_stk_orden_compra
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idordencompra', 'tipo_norma_legal', 'proveedor', 'idorganismo'], 'integer'],
            [['detalle_items'], 'string'],
            [['fecha_emision', 'numero', 'vencimiento', 'expediente', 'norma_legal', 'fedesde', 'fehasta', 'fvdesde', 'fvhasta','detalle_items'], 'safe'],
            [['importe_total'], 'number'],
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
        $query = Sds_stk_orden_compra::find();

        
        $dataProvider = new ActiveDataProvider([
            'sort' => [
                'attributes' => [
                    'fecha_emision',
                    'vencimiento',
                    'numero',
                    'expediente',
                    'proveedor',
                    'detalle_items',

                ],
                'defaultOrder' => ['fecha_emision' => SORT_DESC,'detalle_items' => SORT_ASC]
            ],
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_edesde = '';
        $sql_ehasta = '';
        if ($this->fedesde != null) {
            $fecha_edesde_aux = date_format(date_create(str_replace('/', '-', $this->fedesde)), 'Y-m-d');
            $sql_edesde = "DATEDIFF(fecha_emision,'$fecha_edesde_aux')>=0 ";
        }
        if ($this->fehasta != null) {
            $fecha_ehasta_aux = date_format(date_create(str_replace('/', '-', $this->fehasta)), 'Y-m-d');
            $sql_ehasta = "DATEDIFF(fecha_emision,'$fecha_ehasta_aux')<=0 ";
        }

        $sql_vdesde = '';
        $sql_vhasta = '';
        if ($this->fvdesde != null) {
            $fecha_vdesde_aux = date_format(date_create(str_replace('/', '-', $this->fvdesde)), 'Y-m-d');
            $sql_vdesde = "DATEDIFF(vencimiento,'$fecha_vdesde_aux')>=0 ";
        }
        if ($this->fvhasta != null) {
            $fecha_vhasta_aux = date_format(date_create(str_replace('/', '-', $this->fvhasta)), 'Y-m-d');
            $sql_vhasta = "DATEDIFF(vencimiento,'$fecha_vhasta_aux')<=0 ";
        }

        $consulta_detalle_items = "SELECT  
                                    ifnull((SELECT group_concat(concat(oci.cantidad,' ',a.descripcion) SEPARATOR ' <br> ') as detalle  
                                            FROM sds_stk_orden_compra_item oci
                                            join sds_stk_articulo a on a.idarticulo= oci.idarticulo
                                            where oci.idordencompra = oc.idordencompra
                                            order by a.orden, a.descripcion),'') 
                                    from sds_stk_orden_compra oc 
                                    where oc.idordencompra = sds_stk_orden_compra.idordencompra";

        $query->addSelect([
            ' `sds_stk_orden_compra`.*',
            "($consulta_detalle_items)as detalle_items ",
        ]);

        $query->andFilterWhere([
            'idordencompra' => $this->idordencompra,
            'fecha_emision' => $this->fecha_emision,
            'vencimiento' => $this->vencimiento,
            'numero' => $this->numero,
            'tipo_norma_legal' => $this->tipo_norma_legal,
            'proveedor' => $this->proveedor,
            'importe_total' => $this->importe_total,
            'idorganismo' => $this->idorganismo,
        ]);

        $query->andFilterWhere(['like', 'expediente', $this->expediente])
            ->andFilterWhere(['like', 'norma_legal', $this->norma_legal])
            ->andWhere($sql_edesde)
            ->andWhere($sql_ehasta)
            ->andWhere($sql_vdesde)
            ->andWhere($sql_vhasta)
            ->having("detalle_items like '%" .$this->detalle_items ."%'");

        return $dataProvider;
    }
}
