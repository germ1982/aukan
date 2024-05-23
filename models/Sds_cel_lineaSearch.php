<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_cel_linea;

/**
 * Sds_cel_lineaSearch represents the model behind the search form about `app\models\Sds_cel_linea`.
 */
class Sds_cel_lineaSearch extends Sds_cel_linea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idlinea', 'numero', 'idplan', 'equipo_tipo', 'estado', 'idcontacto', 'idorganismo', 'organismo_padre', 'idusuario', 'iddispositivo', 'activo', 'ultimo_importe', 'id_ultimo_movimiento'], 'integer'],
            [['imei', 'equipo_detalle', 'fecha_entrega',  'fdesde', 'fhasta', 'observaciones', 'iddispositivo', 'cuenta', 'ultimo_importe', 'ultimo_movimiento'], 'safe'],
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
        $query = Sds_cel_linea::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['numero', 'cuenta', 'ultimo_importe', 'idplan', 'equipo_tipo', 'estado', 'fecha_entrega', 'idcontacto', 'idorganismo', 'organismo_padre', 'idusuario', 'iddispositivo', 'observaciones', 'activo', 'id_ultimo_movimiento', 'ultimo_movimiento'],
                'defaultOrder' => ['fecha_entrega' => SORT_DESC]
            ]
        ]);

        /*Se declara la paginación pero el sistema no la tiene en cuenta, habría que buscar porqué. 
         Se deja así ya que se debe subir lo antes posible
        */
         $dataProvider->pagination->pageSize = 30;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        /* $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fdesde)));
            $sql_desde = "fecha_entrega >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fhasta)));
            $sql_hasta = "fecha_entrega <= '$fecha_hasta_aux'";
        } */
        /*
        $having = '';
        if (!($this->iddispositivo == null)) {
            $having = 'c.iddispositivo=' . $this->iddispositivo;
        }
        if (!($this->ultimo_importe == null)) {
            if ($having == '') {
                $having = 'ultimo_importe = ' . $this->ultimo_importe;
            } else {
                $having = ' and ultimo_importe = ' . $this->ultimo_importe;
            }
        }
        //$mysql_ultimo_importe = "(SELECT SUM(i.cantidad) as ultimo_importe from sds_cel_factura f inner join sds_cel_factura_item i on f.idfactura = i.idfactura Where f.fecha_carga = (Select max(fecha_carga) from sds_cel_factura) and i.linea = sds_cel_linea.numero)";
        //$mysql_dispositivo = "(SELECT iddispositivo FROM mds_org_contacto c where c.idcontacto=sds_cel_linea.idcontacto)";*/

        //Consulta SQL sugerida por JP
        $query->select(
            "l.*, 
            ml.tipo id_ultimo_movimiento, 
            conf.descripcion ultimo_movimiento,
            ifnull(`e`.`responsable`,`l`.`idcontacto`) responsable_equipo"
            )
        ->from('sds_cel_linea l');
        $query->join('left join', 'sds_bdc_equipo e', 'l.idequipo=e.idequipo');
        $query->join('left join', 'mds_org_contacto c', 'ifnull(`e`.`responsable`,`l`.`idcontacto`)=c.idcontacto');
        $query->join('left join', 'sds_cel_movimiento_linea ml', 'l.idlinea=ml.idlinea');
        $query->join('left join', 'sds_com_configuracion conf', 'ml.tipo=conf.idconfiguracion');
        $query->join('inner join', '(SELECT idlinea, max(fecha_hora) fh FROM sds_cel_movimiento_linea GROUP BY idlinea) uml', 'uml.idlinea=l.idlinea AND uml.fh=ml.fecha_hora');
        
        /*Filtrado de datos se hace de la siguiente manera, ya que al tener un UNION en la consulta 
          costaba agregar el filtro en los dos SELECT. Como se debía solucionar rapido se implemntó así
        */
        $filterUnion='';
        if($this->idcontacto=='0'){
            $query->andWhere('l.idequipo IS NULL');
            $query->andWhere('l.idcontacto IS NULL');
            $filterUnion.=" AND l.idequipo IS NULL AND l.idcontacto IS NULL";
        }elseif($this->idcontacto!=null && $this->idcontacto>0){
            $filterUnion.=" AND e.responsable=".$this->idcontacto.' OR l.idcontacto='.$this->idcontacto;

            $query->orFilterWhere(['e.responsable' => $this->idcontacto]);
            $query->orFilterWhere(['l.idcontacto' => $this->idcontacto]);
        }

        if($this->idorganismo!=null && $this->idorganismo!=0){
            $query->orFilterWhere(['e.idorganismo' => $this->idorganismo]);
            $query->orFilterWhere(['l.idorganismo' => $this->idorganismo]);
            $filterUnion.=" AND e.idorganismo=$this->idorganismo OR l.idorganismo=$this->idorganismo";
        }

        if($this->organismo_padre!=null && $this->organismo_padre!=0){
            $query->andFilterWhere(['organismo_padre' => $this->organismo_padre]);
            $filterUnion.=" AND organismo_padre=".$this->organismo_padre;
        }
        if($this->iddispositivo!=null && $this->iddispositivo!=0){
            $query->orFilterWhere(['c.iddispositivo' => $this->iddispositivo]);
            $filterUnion.=" AND c.iddispositivo = $this->iddispositivo";
        }

        if($this->numero!=null && $this->numero!=0){
            $query->andFilterWhere(['like', 'numero', $this->numero]);
            $filterUnion.=" AND numero=".$this->numero;
        }

        if($this->cuenta!=null && $this->cuenta!=0){
            $query->andFilterWhere(['like', 'cuenta', $this->cuenta]);
            $filterUnion.=" AND cuenta=".$this->cuenta;
        }

        if($this->id_ultimo_movimiento!=null && $this->id_ultimo_movimiento!=0){
            $query->andFilterWhere(['ml.tipo' => $this->id_ultimo_movimiento]);
            //Condicion siempre falsa para evitar UNIOn, ya que no cuenta con campo ml.tipo
            $filterUnion.=" AND 1<0";
        }

        $query->union(
            "SELECT 
                l.*, 
                NULL id_ultimo_movimiento, 
                NULL ultimo_movimiento,
                e.responsable AS responsable_equipo 
            FROM sds_cel_linea l
            LEFT JOIN sds_bdc_equipo e ON l.idequipo=e.idequipo
            LEFT JOIN mds_org_contacto c ON e.responsable=c.idcontacto
            WHERE
                l.idlinea NOT IN (SELECT idlinea FROM sds_cel_movimiento_linea)
                $filterUnion", 
            true);
        
        /* $query->andFilterWhere([
            'l.idlinea' => $this->idlinea,
            'l.idplan' => $this->idplan,
            'equipo_tipo' => $this->equipo_tipo,
            'estado' => $this->estado,
            'e.idorganismo' => $this->idorganismo,
            'organismo_padre' => $this->organismo_padre,
            'c.iddispositivo' => $this->iddispositivo,
            'id_ultimo_movimiento' => $this->id_ultimo_movimiento,
            'idusuario' => $this->idusuario,
            'activo' => $this->activo,
        ]); */

        /* $query
            ->andFilterWhere(['like', 'numero', $this->numero])
            ->andFilterWhere(['like', 'cuenta', $this->cuenta])
            ->andFilterWhere(['like', 'ultimo_importe', $this->ultimo_importe])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'equipo_detalle', $this->equipo_detalle])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andWhere($sql_desde)->andWhere($sql_hasta);
        $query->having('iddispositivo='.$this->iddispositivo.' and ultimo_importe = '. $this->ultimo_importe. ' or 0>'.$this->iddispositivo.' and ultimo_importe = '. $this->ultimo_importe);
        $query->having($having); */

        return $dataProvider;
    }
}
