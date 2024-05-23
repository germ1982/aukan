<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_registro;
use yii\data\Pagination;

/**
 * Mds_hor_registroSearch represents the model behind the search form about `app\models\Mds_hor_registro`.
 */
class Mds_hor_registroSearch extends Mds_hor_registro
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idregistrohorario', 'idcontacto', 'origen'], 'integer'],
            [['fecha', 'observaciones', 'activo', 'fdesde', 'fhasta','contacto'], 'safe'],
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
        $query = Mds_hor_registro::find();
        /*
        *Se realiza paginado con yii\data\Pagination y no con paginado del ActiveDataProvider porque optimiza los tiempos de consultas
        *En el paginador se setea totalCount contando todos los registros de mds_hor_registro.
        *_togc9414601: es el parametro que setea el botón toggleData al ser seleccionado, sus valores son 'all' para ver todo 
         y 'page' para paginar. Se toma ese valor con la variable global $_GET y en función de ello se pagina o no.
        */
        if(Yii::$app->request->get('_togc9414601')=='all'){
            $all_data=true;
        }
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize'=> isset($all_data) ? $query->count():30]);
        //Se aplican los valores del paginador para limitar la query
        $query->offset($pagination->offset)
        ->limit($pagination->limit);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false //Se desactiva el paginado para implementar el antes creado
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //Verifico que el usuario tenga idusuario asignado, caso contrario redirecciono a Login
        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model
            ]);
        }

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->select(['r.*', 'CONCAT(c.legajo,\' - \',c.apellido, \', \', c.nombre) contacto'])
        ->from('mds_hor_registro  r')
        ->leftJoin('view_contactos_personas c', 'r.idcontacto=c.idcontacto');
        /* $query->select(['r.*', 'CONCAT(c.legajo,\' - \',c.apellido, \', \', c.nombre) contacto'])
            ->from('mds_hor_registro  r')
            ->innerJoin('view_contactos_personas c', 'r.idcontacto=c.idcontacto') */

            //->innerJoin('mds_org_dispositivo d', 'c.iddispositivo=d.iddispositivo')
            //->innerJoin('sds_com_persona p', 'c.idpersona=p.idpersona')
            /* ->where('d.idcapaitem IN (SELECT ici.idcapaitem FROM mds_seg_usuario_capa_item ici 
                    WHERE ici.idusuario=' . $idusuario . ')
                    OR IFNULL((SELECT COUNT(ici.idusuario) FROM mds_seg_usuario_capa_item ici 
                    WHERE ici.idusuario=' . $idusuario . '), 0) = 0') */;

        /* 
        select count(distinct idcontacto) total from mds_hor_registro
        union
        select count(distinct idcontacto) total from mds_hor_registro
        where origen=2
        union
        select count(distinct idcontacto) total from mds_hor_registro
        where origen=0 and idcontacto not in (
        select idcontacto from mds_hor_registro where origen=2)
        union
        select count(distinct idcontacto) total from mds_hor_registro
        where origen=1 and idcontacto not in (
        select idcontacto from mds_hor_registro where origen=2 or origen=0); */
        $query->andFilterWhere([
            'r.idregistrohorario' => $this->idregistrohorario,
            'r.idcontacto' => $this->idcontacto,
            'r.origen' => $this->origen,
        ]);

        $query->andFilterWhere(['like', 'r.observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'r.activo', $this->activo])
            ->andWhere($sql_desde)->andWhere($sql_hasta)
            ->orderBy(['fecha' => SORT_DESC]);

        return [
            'dataProvider' => $dataProvider,
            'pagination' => $pagination
        ];
    }
}
