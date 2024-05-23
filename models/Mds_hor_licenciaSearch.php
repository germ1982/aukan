<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_licencia;

/**
 * Mds_hor_licenciaSearch represents the model behind the search form about `app\models\Mds_hor_licencia`.
 */
class Mds_hor_licenciaSearch extends Mds_hor_licencia
{
    public $legajo;
    public $idpersona;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idlicencia', 'idcontacto', 'cantidad_dias', 'idusuario', 'idpersona' ], 'integer'],
            [['desde', 'hasta', 'detalle','legajo','idpersona','fdesde_desde','fdesde_hasta','fhasta_desde','fhasta_hasta','idmotivoinasistencia'], 'safe'],
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
        $query = Mds_hor_licencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //ANOTEZE: el sort es para que se ordene por cada columna y te aparezcan en azules.
            'sort' => [
                'attributes' => ['idpersona','legajo', 'cantidad_dias', 'desde', 'hasta','detalle','idmotivoinasistencia'],
                //'defaultOrder' => ['idcontacto' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
         if ($this->idpersona==null){
            $this->idpersona = -1;} 

        /* ---------------------------------------------------------------------------------------------------------------------------------------- */
        //esto es para que ande el filtro de las fechas desde

        $sql_desde_desde = '';
        $sql_desde_hasta = '';
        if ($this->fdesde_desde != null) {
            $fecha_desde_desde_aux = date_format(date_create($this->fdesde_desde), 'Y-m-d');
            $sql_desde_desde = "desde >= '$fecha_desde_desde_aux'";
        }
        if ($this->fdesde_hasta != null) {
            $fecha_desde_hasta_aux = date_format(date_create($this->fdesde_hasta), 'Y-m-d');
            $sql_desde_hasta = "desde <= '$fecha_desde_hasta_aux'";
        }
        /* ---------------------------------------------------------------------------------------------------------------------------------------- */
        //esto es para que ande el filtro de las fechas hasta

        $sql_hasta_desde = '';
        $sql_hasta_hasta = '';
        if ($this->fhasta_desde != null) {
            $fecha_hasta_desde_aux = date_format(date_create($this->fhasta_desde), 'Y-m-d');
            $sql_hasta_desde = "hasta >= '$fecha_hasta_desde_aux'";
        }
        if ($this->fhasta_hasta != null) {
            $fecha_hasta_hasta_aux = date_format(date_create($this->fhasta_hasta), 'Y-m-d');
            $sql_hasta_hasta = "hasta <= '$fecha_hasta_hasta_aux'";
        }
        /* ---------------------------------------------------------------------------------------------------------------------------------------- */

        $sql_legajo = '(SELECT idpersona FROM mds_org_contacto c where c.idcontacto=mds_hor_licencia.idcontacto) AS idpersona';

        $query->addSelect(['mds_hor_licencia.*',$sql_legajo]);  
        //$query->addSelect(['mds_hor_licencia.*','(SELECT idpersona FROM mds_org_contacto c where c.idcontacto=mds_hor_licencia.idcontacto) AS idpersona']);  
        $query->andFilterWhere([
            'idlicencia' => $this->idlicencia,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'idcontacto' => $this->idcontacto,
            'cantidad_dias' => $this->cantidad_dias,
            'idusuario' => $this->idusuario,
        ]);

        $query->andFilterWhere(['like', 'detalle', $this->detalle])
        ->andWhere($sql_desde_desde)
        ->andWhere($sql_desde_hasta)
        ->andWhere($sql_hasta_desde)
        ->andWhere($sql_hasta_hasta)
        ->andWhere("idcontacto in (SELECT idcontacto  from mds_org_contacto where legajo like '%".$this->legajo."%')")
        ->andWhere("idmotivoinasistencia in (SELECT idmotivoinasistencia from mds_hor_motivo_inasistencia where idrh like '%".$this->idmotivoinasistencia."%')");
        $query->having('idpersona='.$this->idpersona.' or 0>'.$this->idpersona)
        ->orderBy("desde DESC");   

        return $dataProvider;
    }
}
