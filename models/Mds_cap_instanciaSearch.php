<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_instancia;

/**
 * Mds_cap_instanciaSearch represents the model behind the search form about `app\models\Mds_cap_instancia`.
 */
class Mds_cap_instanciaSearch extends Mds_cap_instancia
{
    //Uso una variable auxiliar para que muestre todas en home, porque son solo para consulta.
    public $modo_consulta = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinstancia', 'idcapacitacion', 'idusuario', 'estado', 'capacidad', 'inscriptos', 'idcampania'], 'integer'],
            [['descripcion', 'presencial', 'desde', 'hasta', 'lugar', 'detalle', 'fdesde_desde', 'fdesde_hasta', 'fhasta_desde', 'fhasta_hasta', 'fecha_limite', 'observacion'], 'safe'],
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
        $query = Mds_cap_instancia::find()->select(['desde', 'hasta', 'idcapacitacion', 'descripcion', 'presencial', 'lugar', 'detalle', 'idinstancia', 'alias', 'capacidad', 'resolucion_aval', 'area_certificado', 'presencial', 'cant_horas', 'idcampania']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        if (!$this->modo_consulta) {
            $idusuario = Yii::$app->user->identity->idusuario;
            $permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
            $permiso_global = $permiso_global != null ? 1 : 0;
        } else {
            $permiso_global = 1;
        }

        // Diferencia si es usuario interno o externo

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;

        if ($idcontacto) {
            $query->andWhere("idcapacitacion in (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
            where idorganismo in (select idorganismo from mds_org_contacto contacto,mds_org_dispositivo disp
            where disp.iddispositivo=contacto.iddispositivo
            and idcontacto = $idcontacto)) or 1=" . $permiso_global);
        } else {
            $query->andWhere("idcapacitacion in (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
            where cap.idorganismoexterno = $idOrganismoExterno or 1=$permiso_global)");
        }

        $query->andFilterWhere([
            'idinstancia' => $this->idinstancia,
            'idcapacitacion' => $this->idcapacitacion,
            'idcampania' => $this->idcampania,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
            'idusuario' => $this->idusuario,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'presencial', $this->presencial])
            ->andFilterWhere(['like', 'lugar', $this->lugar])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andWhere($sql_desde_desde)
            ->andWhere($sql_desde_hasta)
            ->andWhere($sql_hasta_desde)
            ->andWhere($sql_hasta_hasta)
            ->orderBy(['desde' => SORT_DESC, 'idinstancia' => SORT_DESC]);

        return $dataProvider;
    }
}
