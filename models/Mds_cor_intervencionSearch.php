<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cor_intervencion;

/**
 * Mds_cor_intervencionSearch represents the model behind the search form about `app\models\Mds_cor_intervencion`.
 */
class Mds_cor_intervencionSearch extends Mds_cor_intervencion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ //ANOTEZE: También comento el idintervencion acá, como se puso autoincrement, ya a yii no le interesa validar nada de ese campo, se maneja en la base y acá no se toca.
            [['idintervencion', 'idpersona','idpersona_intervencion', 'idusuario', 'referente_dni', 'profesional', 'tipo', 'dni_beneficiario'], 'integer'],
            [['fecha_hora', 'fdesde', 'fhasta', 'derivaciones_previas', 'referente_nombre', 'referente_vinculo', 'detalle', 'intervenciones', 'derivaciones', 'fecha_informe', 'referente_telefono', 'dni_beneficiario', 'deleted_at', 'idpersona_intervencion', 'idpersona'], 'safe'],
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
        $query = Mds_cor_intervencion::find()->select('*, sds_com_persona.documento as dni_beneficiario, mds_cor_intervencion.idpersona as idpersona_intervencion');
        $query->innerJoin('sds_com_persona', 'mds_cor_intervencion.idpersona = sds_com_persona.idpersona');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //ANOTEZE: Está perfecto esto, el sort es para que se ordene por cada columna y te aparezcan en azules.
            //Habían faltado 'fecha_informe' e ' intervenciones' asi que las agrego ya que estoy.
            'sort' => [
                'attributes' => ['idintervencion', 'idusuario', 'fecha_hora', 'dni_beneficiario', 'telefono', 'nombre_completo', 'fecha_informe', 'intervenciones', 'tipo', 'idderivacion', 'persona_afectada', 'idpersona_intervencion', 'profesional', 'idpersona'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
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
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }

        $sql_desde1 = '';
        $sql_hasta1 = '';
        if ($this->fdesde1 != null) {
            $fecha_desde_aux1 = date_format(date_create($this->fdesde1), 'Y-m-d');
            $sql_desde1 = "fecha_informe >= '$fecha_desde_aux1'";
        }
        if ($this->fhasta1 != null) {
            $fecha_hasta_aux1 = date_format(date_create($this->fhasta1), 'Y-m-d');
            $sql_hasta1 = "fecha_informe <= '$fecha_hasta_aux1'";
        }

        // Obtiene idusuario de quien está logueado, para mostrarle los intervenciones creados por ese usuario
        $user = Yii::$app->user->identity;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $idusuario = $user->idusuario;

        $sql_intervencionesIds = '';

        if (Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL)) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['deleted_at' => null]);
            }
        } else {
            // Obtener los ids de intervenciones compartidos con el usuario logueado
            $intervenciones = Mds_cor_intervencion_usuario::findAll(["idusuario" => $idusuario]);

            if (sizeof($intervenciones) > 0) {
                $ids = '';
                foreach ($intervenciones as $intervencion) {
                    $ids = $ids . $intervencion->idintervencion;
                    if (next($intervenciones)) {
                        $ids =  $ids . ', ';
                    }
                }
                $sql_intervencionesIds = "idusuario = " . $idusuario . " or idintervencion in (" . $ids . ")";
            } else {
                $sql_intervencionesIds = "idusuario = " . $idusuario;
            }
            $query->andWhere(['mds_cor_intervencion.deleted_at' => null]);
        }

        //ANOTEZE: aca hay que agregar también el filtro para fecha_informe, usando otras variables. Ej: fdesde_inf y fhasta_inf
        $query->andFilterWhere([
            'idintervencion' => $this->idintervencion,
            'mds_cor_intervencion.idpersona' => $this->idpersona_intervencion,
            //ANOTEZE: Saco esta comparación sino va a comparar esta fecha y no las que agregamos después.
            //'fecha_hora' => $this->fecha_hora,
            'referente_dni' => $this->referente_dni,
            'profesional' => $this->profesional,
            // 'fecha_informe' => $this->fecha_informe,
            'tipo' => $this->tipo,
        ]);

        if ($this->dni_beneficiario) {
            $query->andFilterWhere(['=', 'sds_com_persona.documento', $this->dni_beneficiario]);
        }

        $query->andFilterWhere(['like', 'derivaciones_previas', $this->derivaciones_previas])
            ->andFilterWhere(['like', 'referente_nombre', $this->referente_nombre])
            ->andFilterWhere(['like', 'referente_vinculo', $this->referente_vinculo])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'intervenciones', $this->intervenciones])
            ->andFilterWhere(['like', 'derivaciones', $this->derivaciones])
            ->andFilterWhere(['like', 'referente_telefono', $this->referente_telefono])
            ->andWhere($sql_intervencionesIds)
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            //->andWhere($sql_desde1)->andWhere($sql_hasta1)
        ;

        return $dataProvider;
    }
}
