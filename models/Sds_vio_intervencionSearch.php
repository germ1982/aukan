<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_vio_intervencion;

/**
 * Sds_vio_intervencionSearch represents the model behind the search form about `app\models\Sds_vio_intervencion`.
 */
class Sds_vio_intervencionSearch extends Sds_vio_intervencion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idpersona', 'idrisneu', 'idusuario', 'tipo', 'derivacion'], 'integer'],
            [['idrisneu', 'fecha', 'ingreso', 'denuncia', 'juzgado', 'detalle', 'fdesde', 'fhasta', 'dni', 'nombrecompuesto', 'telefono', 'domicilio', 'deleted_at', 'idllamada'], 'safe'],
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
    public function search($params, $fechaInicio = null, $fechaFin = null)
    {
        $query = Sds_vio_intervencion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idintervencion', 'idrisneu', 'fecha', 'nombrecompuesto', 'dni', 'tipo', 'derivacion', 'idusuario', 'entidad', 'detalle','idllamada'],
                'defaultOrder' => ['idintervencion' => SORT_DESC]
            ]
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
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }
        if ($this->tipo == null) {
            $this->tipo = -1;
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

        $permiso_externo = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
        and (iditem=" . Mds_seg_item::MODULO_VIO_EXTERNO . ")")->one();
        $query->addSelect(['I.*,documento as dni, UPPER(CONCAT(apellido,", ", nombre)) as nombrecompuesto,nombre,apellido,temp_entidad.entidad']);
        $query->from('sds_vio_intervencion as I');
        $query->join(
            'inner join',
            '(select p.idpersona, documento, apellido, nombre from sds_com_persona as p) temp',
            'temp.idpersona = I.idpersona'
        );
        $query->join(
            'inner join',
            '(select idusuario,ifnull((select descripcion from mds_org_dispositivo dispositivo
            where contacto.iddispositivo=dispositivo.iddispositivo),
            (select descripcion from mds_org_organismo_externo externo where externo.idorganismoexterno=usuario.externo)) entidad,externo,iddispositivo
            from mds_seg_usuario usuario
            left join mds_org_contacto contacto on contacto.idcontacto=usuario.idcontacto) temp_entidad',
            'temp_entidad.idusuario = I.idusuario'
        );
        if ($permiso_externo != null) {
            $consulta_externo = "";
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model,
                ]);
            }
            if (Yii::$app->user->identity->idcontacto != null) {
                $consulta_externo = "temp_entidad.iddispositivo=" . Mds_org_contacto::findOne(Yii::$app->user->identity->idcontacto)->iddispositivo;
            } else {
                $consulta_externo = " and temp_entidad.externo=" . Yii::$app->user->identity->externo;
            }
            $query->andWhere($consulta_externo);
        }

        if ($fechaInicio || $fechaFin) {
            $whereFechaCarga = '';
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga .= "I.fecha >= '$fechaInicio' AND I.fecha <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga .= "I.fecha >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga .= "I.fecha <= '$fechaFin'";
            }

            if ($whereFechaCarga) {
                $query->andWhere($whereFechaCarga);
            }
        }

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        $query->andFilterWhere([
            'idintervencion' => $this->idintervencion,
            'idllamada' => $this->idllamada,
            'idrisneu' => $this->idrisneu,
            'idpersona' => $this->idpersona,
            'I.idusuario' => $this->idusuario,
            'derivacion' => $this->derivacion,
        ]);
        // $query->andWhere(['deleted_at' => null]);

        $query->andFilterWhere(['like', 'ingreso', $this->ingreso])
            ->andFilterWhere(['like', 'denuncia', $this->denuncia])
            ->andFilterWhere(['like', 'juzgado', $this->juzgado])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'documento', $this->dni])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        $query->andWhere("(" . $this->tipo . "=-1
            or tipo = " . $this->tipo . " 
            or (" . $this->tipo . "=" . Sds_vio_intervencion::TIPO_LISTA_ESPERA . " 
            and (SELECT tipo FROM sds_vio_intervencion where idpersona=I.idpersona order by fecha desc limit 1)=" . Sds_vio_intervencion::TIPO_LISTA_ESPERA . ")
        )");
        $query->having("nombrecompuesto like '%$this->nombrecompuesto%'");

        return $dataProvider;
    }
}
