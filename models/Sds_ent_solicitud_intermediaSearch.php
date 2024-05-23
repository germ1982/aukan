<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_solicitud_intermedia;

/**
 * Sds_ent_solicitud_intermediaSearch represents the model behind the search form about `app\models\Sds_ent_solicitud_intermedia`.
 */
class Sds_ent_solicitud_intermediaSearch extends Sds_ent_solicitud_intermedia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idsolicitudintermedia', 'emisor', 'receptor', 'usuario_carga', 'usuario_aprobacion', 'estado', 'idtipo', 'cantidad'], 'integer'],
            [['fecha_hora', 'fdesde', 'fhasta', 'irregular', 'rendiciones_pendientes', 'observaciones', 'nombre_receptor', 'datos_emisor'], 'safe'],
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
        $query = Sds_ent_solicitud_intermedia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idusuario', 'fecha_hora', 'datos_emisor', 'receptor', 'nombre_receptor', 'cantidad', 'idtipo', 'estado', 'observaciones'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }
        $query->addSelect([
            "idsolicitudintermedia", "irregular", "observaciones",
            "fecha_hora", "cantidad", "idtipo", "emisor", "receptor", "usuario_carga", "usuario_aprobacion",
            "estado", "(select concat(solicitud.emisor,' - ',
            DATE_FORMAT(ent.fecha_hora,'%d/%m/%Y %H:%i'),' - ',
            (select descripcion from sds_com_configuracion conf where conf.idconfiguracion=ent.receptor),
            ' - Cant.: ', ent.cantidad)
            from sds_ent_entrega ent where ent.identrega=solicitud.emisor) datos_emisor",
            "(select descripcion from sds_com_configuracion conf 
            where conf.idconfiguracion=solicitud.receptor) nombre_receptor"
        ]);
        $query->from('sds_ent_solicitud_intermedia solicitud');
        $query->andFilterWhere([
            'idsolicitudintermedia' => $this->idsolicitudintermedia,
            'fecha_hora' => $this->fecha_hora,
            'usuario_carga' => $this->usuario_carga,
            'usuario_aprobacion' => $this->usuario_aprobacion,
            'estado' => $this->estado,
            'idtipo' => $this->idtipo,
            'cantidad' => $this->cantidad,
        ]);

        $permiso_todas = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=" . $user->idusuario . ")
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_VER_TODAS . ")")->one();
        if ($permiso_todas == null) {
            $query->andWhere("(emisor in (select identrega from sds_ent_entrega ent where ent.receptor = 
                                    (select responsable from mds_seg_usuario where idusuario=" . $user->idusuario . ")))");
        }

        $query->andFilterWhere(['like', 'irregular', $this->irregular])
            ->andFilterWhere(['like', 'rendiciones_pendientes', $this->rendiciones_pendientes])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andWhere($sql_desde)->andWhere($sql_hasta);
        $query->having("nombre_receptor like '%" . $this->nombre_receptor . "%' and 
                        datos_emisor like '%" . $this->datos_emisor . "%'");
        return $dataProvider;
    }
}
