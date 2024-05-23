<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_entrega;

/**
 * Sds_stk_entregaSearch represents the model behind the search form about `app\models\Sds_stk_entrega`.
 */
class Sds_stk_entregaSearch extends Sds_stk_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['identrega', 'organismo', 'idpersona', 'acta_original','organizacion_social','es_organizacion_social'],
                'integer',
            ],
            [['observaciones', 'ordenes'], 'string'],
            [
                [
                    'fecha_hora',
                    'fdesde',
                    'fhasta',
                    'idcontacto',
                    'acta_original',
                    'detalle_items',
                ],
                'safe',
            ],
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
        $query = Sds_stk_entrega::find();

        /* $query = Sds_stk_entrega::findBySql("SELECT *
        FROM sds_stk_entrega e 
        JOIN sds_stk_entrega_item ei on e.identrega = ei.identrega
        JOIN sds_stk_movimiento m on m.item_entrega = ei.identregaitem")->all(); */

        $dataProvider = new ActiveDataProvider([
            'sort' => [
                'attributes' => [
                    'fecha_hora',
                    'idpersona',
                    'idcontacto',
                    'acta_original',
                    'detalle_items',
                    'ordenes',
                    'observaciones',
                    'organizacion_social',
                    'es_organizacion_social',
                ],
                'defaultOrder' => [
                    'fecha_hora' => SORT_DESC,
                    'detalle_items' => SORT_ASC,
                    'ordenes' => SORT_ASC,
                ],
            ],
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
        /* $query->addSelect([" `sds_stk_entrega`.*", "(select group_concat(concat(it.cantidad,' ',art.descripcion)SEPARATOR ' <br> ') detalle
        from sds_stk_entrega_item it
        join sds_stk_recepcion_item itrec on itrec.idrecepcionitem=it.recepcion_item 
        join sds_stk_articulo art on art.idarticulo=itrec.idarticulo
        where sds_stk_entrega.identrega=it.identrega) AS detalle_items"]); */
        $consulta_detalle_items = "SELECT ifnull((SELECT group_concat(CONCAT(it.cantidad,' ',art.descripcion)SEPARATOR ' <br> ') detalle 
                                    FROM sds_stk_entrega_item it 
                                    JOIN sds_stk_articulo art ON art.idarticulo=it.idarticulo 
                                    WHERE e.identrega=it.identrega
                                    ORDER BY art.orden, art.descripcion),'') FROM sds_stk_entrega e WHERE e.identrega = sds_stk_entrega.identrega";

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        $consulta_mostrar = 'SELECT 2 as mostrar'; //si el usuario no tiene deposito asignado setea mostrar en 2 para no dejar vacia la columna
        $having_mostrar = ''; //si el usuario no tiene deposito asignado setea el having vacio para que no haya ninguna seleccion

        if ($usuario->iddeposito) {
            /* Si el usuario tiene  deposito asignado hace la siguinte consulta, 
        donde compara si la cantidad de items de la entrega es igual a 
        la cantidad de items con ese deposito, si es igual, setea mostrar en 1 para mostrar esa entrega
        si no hubiera items, los contadores darian = por lo que tambien muestra la entrega para poder asignarle items*/
            $consulta_mostrar = "SELECT IF(
                    (SELECT count(*)
                    from sds_stk_entrega_item eei 
                    join sds_stk_movimiento em on eei.identregaitem = em.item_entrega
                    where eei.identrega = sds_stk_entrega.identrega)
                    = 
                    (SELECT count(*)
                    from sds_stk_entrega_item dei 
                    join sds_stk_movimiento dm on dei.identregaitem = dm.item_entrega
                    where dei.identrega = sds_stk_entrega.identrega AND dm.deposito_egreso = $usuario->iddeposito)
                ,1,0) as mostrar";

            $having_mostrar = 'and mostrar = 1'; //Setea el having en 1 para que muestre los items con mostrar en 1
        }

        $consulta_oc = "SELECT ifnull(GROUP_CONCAT(DISTINCT oc.numero SEPARATOR ' <br> '),'') as ordenes
        FROM sds_stk_entrega_item ei 
        JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
        JOIN sds_stk_orden_compra_item oci on ri.idordencompraitem = oci.idordencompraitem
        JOIN sds_stk_orden_compra oc on oc.idordencompra = oci.idordencompra
        where ei.identrega = sds_stk_entrega.identrega";

        $query->addSelect([
            ' `sds_stk_entrega`.*',
            "($consulta_detalle_items)as detalle_items ",
            "($consulta_mostrar) as mostrar",
            "($consulta_oc) as ordenes",
        ]);
        $query->andFilterWhere([
            'identrega' => $this->identrega,
            'fecha_hora' => $this->fecha_hora,
            'organismo' => $this->organismo,
            'idpersona' => $this->idpersona,
            'acta_original' => $this->acta_original,
            'organizacion_social' => $this->organizacion_social,
            'es_organizacion_social' => $this->es_organizacion_social,
            
        ]);
        if (is_array($this->idcontacto)) {
            $responsables = [];
            foreach ((array) $this->idcontacto as $responsable) {
                array_push($responsables, "'" . $responsable . "'");
            }
            $responsable_filter = implode(',', $responsables);
        } else {
            $responsable_filter = "'" . $this->idcontacto . "'";
        }
        if ($responsable_filter != "''") {
            $query->andWhere(
                'SUBSTRING_INDEX(sds_stk_entrega.idcontacto,\'/\',1) in (' .
                    $responsable_filter .
                    ')'
            );
        }
        $query
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->having(
                "ordenes like '%" .
                    $this->ordenes .
                    "%' and detalle_items like '%" .
                    $this->detalle_items .
                    "%' $having_mostrar"
            );
        return $dataProvider;
    }
}
