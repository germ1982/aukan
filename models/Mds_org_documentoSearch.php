<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_documento;

/**
 * 
 * Mds_org_documentoSearch represents the model behind the search form about `app\models\Mds_org_documento`.
 */
class Mds_org_documentoSearch extends Mds_org_documento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddocumento', 'idusuario', 'tipo', 'idcontacto', 'idpersona', 'estado'], 'integer'],
            [['nombre', 'fecha', 'path', 'detalle', 'fdesde', 'fhasta', 'idpersona', 'nomAp', 'estado', 'medicina'], 'safe'],
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
        $query = Mds_org_documento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['fecha', 'nombre', 'iddocumento', 'idusuario', 'idpersona', 'tipo', 'nomAp', 'detalle', 'estado', 'medicina'],]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->idpersona == null) {
            $this->idpersona = -1;
        }

        //Verifico que el usuario tenga idusuario asignado, caso contrario redirecciono a Login
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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }
        $tipoconfiguracion=($this->medicina==0 ? Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO : Sds_com_configuracion_tipo::DOC_MEDICINA_LABORAL);
        $query->select(['doc.*, concat(pers.apellido,\', \',pers.nombre) nomAp, c.idpersona idpersona'])
        ->from('mds_org_documento doc')
		->innerJoin('mds_org_contacto c', 'c.idcontacto=doc.idcontacto')
        ->innerJoin('sds_com_persona pers', 'pers.idpersona=c.idpersona')
        ->innerJoin('mds_org_dispositivo d', 'd.iddispositivo=c.iddispositivo')
        ->innerJoin('sds_com_configuracion conf', 'doc.tipo=conf.idconfiguracion')
        ->innerJoin('sds_com_configuracion_tipo tc', 'conf.idconfiguraciontipo=tc.idconfiguraciontipo')
        ->where('d.idcapaitem IN (SELECT uci.idcapaitem FROM mds_seg_usuario_capa_item uci WHERE uci.idusuario='.$idusuario.')
        OR IFNULL((SElECT COUNT(uci.idusuario) FROM mds_seg_usuario_capa_item uci WHERE uci.idusuario='.$idusuario.'), 0) = 0')
        ->andWhere('tc.idconfiguraciontipo='.$tipoconfiguracion);

        $query->andFilterWhere([
            'iddocumento' => $this->iddocumento,
            'idusuario' => $this->idusuario,
            'tipo' => $this->tipo,
            'fecha' => $this->fecha,
            'idcontacto' => $this->idcontacto,
            'estado' => $this->estado
        ]);

        $query->andFilterWhere(['like', 'doc.nombre', $this->nombre])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->orderBy(['fecha' => SORT_DESC]);
        //->andWhere("idcontacto in (SELECT idcontacto  from mds_org_contacto where legajo like '%".$this->legajo."%')");
        $query->having('idpersona=' . $this->idpersona . ' or 0>' . $this->idpersona)
            ->having("nomAp like '%" . $this->nomAp . "%'");
        return $dataProvider;
    }
}
