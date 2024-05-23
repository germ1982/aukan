<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_certificacion;

/**
 * Mds_hor_certificacionSearch represents the model behind the search form about `app\models\Mds_hor_certificacion`.
 */
class Mds_hor_certificacionSearch extends Mds_hor_certificacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcertificacion', 'certificado', 'certificante', 'periodo_mes', 'periodo_anio'], 'integer'],
            [['desde', 'hasta', 'detalle', 'estado'], 'safe'],
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
        $query = Mds_hor_certificacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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

        $query->select('cert.*')
        ->from('mds_hor_certificacion cert')
        ->innerJoin('mds_org_contacto c', 'cert.certificado=c.idcontacto')
        ->innerJoin('sds_com_persona p', 'c.idpersona=p.idpersona')
        ->innerJoin('mds_org_dispositivo d', 'c.iddispositivo=d.iddispositivo')
        ->where('d.idcapaitem IN (SELECT ic.idcapaitem FROM mds_seg_usuario_capa_item ic WHERE ic.idusuario='.$idusuario.')
            OR IFNULL((SELECT COUNT(ic.idusuario) FROM mds_seg_usuario_capa_item ic WHERE ic.idusuario='.$idusuario.'), 0) = 0');



        $query->andFilterWhere([
            'idcertificacion' => $this->idcertificacion,
            'certificado' => $this->certificado,
            'certificante' => $this->certificante,
            'periodo_mes' => $this->periodo_mes,
            'periodo_anio' => $this->periodo_anio,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
        ]);

        $query->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
