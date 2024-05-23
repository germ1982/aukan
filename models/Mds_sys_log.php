<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_sys_log".
 *
 * @property int $idlog
 * @property string $fecha_hora
 * @property int $idusuario
 * @property int $accion 0: consulta, 1: new, 2: update, 3: delete
 * @property string $modulo
 * @property string $datos json con los datos que guarda. No aplica a accion 0
 *
 * @property MdsSegUsuario $idusuario0
 */
class Mds_sys_log extends \yii\db\ActiveRecord
{
    //0: ACCION_CONSULTA (para grilla y consulta) 1: ACCION_NUEVO 2: ACCION_EDITAR 3: ACCION_ELIMINAR
    const ACCION_CONSULTA = 0;
    const ACCION_NUEVO = 1;
    const ACCION_EDITAR = 2;
    const ACCION_ELIMINAR = 3;

    public $fdesde;
    public $fhasta;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_sys_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idusuario', 'accion', 'modulo', 'datos'], 'required'],
            [['fecha_hora','fdesde','fhasta'], 'safe'],
            [['idusuario', 'accion', 'id'], 'integer'],
            [['datos'], 'string'],
            [['modulo'], 'string', 'max' => 100],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlog' => 'ID Log',
            'fecha_hora' => 'Fecha y Hora',
            'idusuario' => 'Usuario',
            'accion' => 'Acción',
            'modulo' => 'Módulo',
            'datos' => 'Datos',
            'id' => 'ID Registro'
        ];
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    /** Guarda log de SUR     
     * @param int $accion 0: ACCION_CONSULTA (para grilla y consulta) 1: ACCION_NUEVO 2: ACCION_EDITAR 3: ACCION_ELIMINAR     
     * @param string $modulo Nombre de módulo. Ej: mds_seg_usuario
     * @param int $id Id del modelo en cuestión. Si se consulta un listado (grilla) va en null
     * @param array $datos datos que fueron guardados en la acción. Ej: $model->getAtributtes() (posteriormente se guardan en formato **JSON** ).
     * No aplica si la acción es Consulta (se pasa una cadena vacía)
     * @return boolean éxito o no de la transacción */
    public static function guardarLog($accion, $modulo, $id, $datos)
    {
        $modelo_log = new Mds_sys_log();
        $modelo_log->fecha_hora = date('Y-m-d H:i');
        $modelo_log->accion = $accion;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $modelo_log->idusuario = Yii::$app->user->identity->idusuario;
        $modelo_log->modulo = $modulo;
        $modelo_log->id = $id;
        $modelo_log->datos = empty($datos) ? "" : json_encode($datos);

        return $modelo_log->save(false);
    }
}
