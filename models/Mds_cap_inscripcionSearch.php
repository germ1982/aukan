<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_inscripcion;

/**
 * Mds_cap_inscripcionSearch represents the model behind the search form about `app\models\Mds_cap_inscripcion`.
 */
class Mds_cap_inscripcionSearch extends Mds_cap_inscripcion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['termino', 'fecha_inscripcion', 'dni', 'persona', 'mail', 'telefono', 'fecha_desde', 'fecha_hasta'], 'safe'],
            [['idinscripcion', 'idpersonacap', 'idcapinstancia', 'idlocalidad'], 'integer'],
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
        $query = Mds_cap_inscripcion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_inscripcion', 'idinscripcion', 'idpersonacap', 'termino', 'idcapinstancia', 'idpersonacap0', 'dni', 'persona', 'mail', 'telefono', 'idlocalidad'],
                'defaultOrder' => ['persona' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->idlocalidad == null) {
            $this->idlocalidad = -1;
        }

        //esto es para que ande el filtro de las fecha_inscripcion

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fecha_desde != null) {
            $fecha_desde_aux = date_format(date_create($this->fecha_desde), 'Y-m-d');
            $sql_desde = "fecha_inscripcion >= '$fecha_desde_aux'";
        }
        if ($this->fecha_hasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fecha_hasta), 'Y-m-d');
            $sql_hasta = "fecha_inscripcion <= '$fecha_hasta_aux'";
        }

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idusuario = Yii::$app->user->identity->idusuario;
        $permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
        $permiso_global = $permiso_global != null ? 1 : 0;
        $query->addSelect([
            'inscripcion.*', 'pers.documento as dni', 'concat(pers.apellido,\', \',pers.nombre) persona',
            'concat(pers.apellido,\'_\',pers.nombre) la_persona',
            'percap.localidad AS idlocalidad', 'percap.mail', 'percap.telefono'
        ]);
        $query->from('mds_cap_inscripcion as inscripcion');
        $query->join('join', 'mds_cap_persona percap', 'percap.idpersonacap = inscripcion.idpersonacap');
        $query->join('join', 'sds_com_persona pers', 'pers.idpersona= percap.idpersona');

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;
        if ($idcontacto) {

            $query->andWhere("idcapinstancia in 
                (SELECT idinstancia FROM mds_cap_instancia m where idcapacitacion in 
                (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
                where idorganismo in (select idorganismo from mds_org_contacto 
                                        contacto,mds_org_dispositivo disp
                                        where disp.iddispositivo=contacto.iddispositivo
                                        and idcontacto = $idcontacto))) or 1=" . $permiso_global);
        } else {
            $query->andWhere("idcapinstancia in 
                (SELECT idinstancia FROM mds_cap_instancia m where idcapacitacion in 
                (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
                where idorganismoexterno = $idOrganismoExterno)) or 1=" . $permiso_global);
        }


        $query->andFilterWhere([
            'idinscripcion' => $this->idinscripcion,
            'idpersonacap' => $this->idpersonacap,
            'idcapinstancia' => $this->idcapinstancia,
            'fecha_inscripcion' => $this->fecha_inscripcion,
        ]);

        $query->andFilterWhere(['like', 'termino', $this->termino])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->orderBy(['fecha_inscripcion' => SORT_DESC]);

        $query->andFilterWhere(['like', 'termino', $this->termino])
            ->having("dni like '%" . $this->dni . "%' and persona like '%" . $this->persona . "%'
            and (idlocalidad = " . $this->idlocalidad . " or " . $this->idlocalidad . "=-1)" .
                " and mail like '%" . $this->mail . "%' and percap.telefono like '%" . $this->telefono . "%'");

        return $dataProvider;
    }
}
