<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_entrega".
 *
 * @property int $identrega
 * @property string $fecha_hora
 * @property int $cantidad
 * @property int|null $dni
 * @property int $idtipo
 * @property string|null $observaciones
 * @property int $idusuario
 * @property float $latitud
 * @property float $longitud
 * @property string|null $dni_frente
 * @property string|null $dni_dorso
 * @property int|null $idsolicitud
 * @property int|null $emisor
 * @property int|null $receptor
 * @property int|null $idpersona
 * @property int|null $numero
 * @property string|null $oc
 * @property int|null $proveedor
 * @property int|null $idsolicitudintermedia
 * @property string|null $acta
 * @property int|null $persona_retira
 * @property int|null $usuario_entrega
 * @property int|null $numero_desde
 * @property int|null $numero_hasta
 * @property string|null $fecha_cierre
 *
 * @property SdsEntCierre[] $sdsEntCierres
 * @property Sds_ent_entrega $emisor0
 * @property Sds_ent_entrega[] $sdsEntEntregas
 * @property MdsSegUsuario $usuarioEntrega
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $proveedor0
 * @property SdsComConfiguracion $receptor0
 * @property SdsComPersona $idpersona1
 * @property SdsEntSolicitudIntermedia $idsolicitudintermedia0
 * @property SdsEntTipo $idtipo0
 * @property MdsSegUsuario $usuarioEntrega0
 * @property SdsEntSolicitud[] $sdsEntSolicituds
 * @property SdsEntSolicitudIntermedia[] $sdsEntSolicitudIntermedia
 */
class Sds_ent_entrega extends \yii\db\ActiveRecord
{
    const ESTADO_INICIAL = 0;
    const ESTADO_INTERMEDIA = 1;
    const ESTADO_FINAL = 2;
    const ESTADO_DEUDOR = 3;

    public $fdesde;
    public $fhasta;
    public $estado;
    public $estado_acta;
    public $entidad;
    public $coordenadas;
    public $hora;
    public $archivo_dni_frente;
    public $archivo_dni_dorso;
    public $archivo_acta;
    public $archivo_adjunto_cierre;
    public $saldo;
    //Campos para sds_com_persona
    public $sexo;
    public $nacionalidad;
    public $fecha_nacimiento;
    public $nombre;
    public $apellido;
    //Campos solo para árbol:
    public $detalle_tipo;
    public $nombre_receptor;
    public $nombre_emisor;
    //para cierre de entrega
    public $tiene_numero;
    public $motivo_general;
    public $estado_cierre;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'cantidad', 'idtipo', 'idusuario', 'interior'], 'required'],
            [
                [
                    'fecha_hora',
                    'hora',
                    'detalle_tipo',
                    'nombre_receptor',
                    'nombre_emisor',
                    'idpersona',
                    'fecha_nacimiento',
                    'estado_acta',
                    'persona_retira',
                    'fecha_cierre',
                    'tiene_numero',
                    'motivo_general',
                    'estado_cierre',
                    'interior',
                ],
                'safe',
            ],
            [
                [
                    'cantidad',
                    'dni',
                    'idtipo',
                    'idusuario',
                    'idsolicitud',
                    'emisor',
                    'receptor',
                    'saldo',
                    'idpersona',
                    'sexo',
                    'nacionalidad',
                    'numero',
                    'numero_desde',
                    'numero_hasta',
                    'estado_acta',
                    'proveedor',
                    'idsolicitudintermedia',
                    'persona_retira',
                    'usuario_entrega',
                    'interior'
                ],
                'integer',
            ],
            [
                [
                    'observaciones',
                    'dni_frente',
                    'dni_dorso',
                    'acta',
                    'detalle_tipo',
                    'nombre_receptor',
                    'nombre_emisor',
                    'nombre',
                    'apellido',
                    'adjunto_cierre',
                ],
                'string',
            ],
            [['latitud', 'longitud'], 'number'],
            [['oc'], 'string', 'max' => 45],
            [
                ['archivo_dni_frente'],
                'file',
                'extensions' => 'jpg, jpeg, gif, png',
                'maxSize' => 100000000,
            ],
            [
                ['archivo_dni_dorso'],
                'file',
                'extensions' => 'jpg, jpeg, gif, png',
                'maxSize' => 100000000,
            ],
            [
                ['archivo_acta'],
                'file',
                'extensions' => 'jpg, jpeg, gif, png, pdf',
                'maxSize' => 100000000,
            ],
            [
                ['archivo_adjunto_cierre'],
                'file',
                'extensions' => 'jpg, jpeg, gif, png, pdf',
                'maxSize' => 100000000,
            ],
            [
                ['idsolicitud'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_ent_solicitud::className(),
                'targetAttribute' => ['idsolicitud' => 'idsolicitud'],
            ],
            [
                ['idtipo'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_ent_tipo::className(),
                'targetAttribute' => ['idtipo' => 'idtipo'],
            ],
            [
                ['emisor'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_ent_entrega::className(),
                'targetAttribute' => ['emisor' => 'identrega'],
            ],
            [
                ['receptor'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_com_configuracion::className(),
                'targetAttribute' => ['receptor' => 'idconfiguracion'],
            ],
            [
                ['proveedor'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_com_configuracion::className(),
                'targetAttribute' => ['proveedor' => 'idconfiguracion'],
            ],
            [
                ['idusuario'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Mds_seg_usuario::className(),
                'targetAttribute' => ['idusuario' => 'idusuario'],
            ],
            [
                ['idpersona'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_com_persona::className(),
                'targetAttribute' => ['idpersona' => 'idpersona'],
            ],
            [
                ['idsolicitudintermedia'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_ent_solicitud_intermedia::className(),
                'targetAttribute' => [
                    'idsolicitudintermedia' => 'idsolicitudintermedia',
                ],
            ],
            [
                ['persona_retira'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_com_persona::className(),
                'targetAttribute' => ['persona_retira' => 'idpersona'],
            ],
            [
                ['usuario_entrega'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Mds_seg_usuario::className(),
                'targetAttribute' => ['usuario_entrega' => 'idusuario'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identrega' => 'N°',
            'fecha_hora' => 'Fecha y Hora',
            'cantidad' => 'Cantidad',
            'dni' => 'Dni',
            'idtipo' => 'Tipo',
            'observaciones' => 'Detalle',
            'idusuario' => 'Usuario',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'dni_frente' => 'Dni Frente',
            'dni_dorso' => 'Dni Dorso',
            'idsolicitud' => 'Solicitud',
            'emisor' => 'Emisor',
            'receptor' => 'Receptor',
            'idpersona' => 'Persona',
            'numero' => 'Número',
            'oc' => 'N° Orden Compra',
            'proveedor' => 'Proveedor',
            'idsolicitudintermedia' => 'Solicitud Intermedia',
            'acta' => 'Archivo de Acta (imagen o PDF)',
            'persona_retira' => 'Retira',
            'usuario_entrega' => 'Usuario Entrega',
            'numero_desde' => 'Numero Desde',
            'numero_hasta' => 'Numero Hasta',
            'fecha_cierre' => 'Fecha Cierre',
        ];
    }

    public static function getEntregasHijas($identrega, $idtipo, $externo)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $sql_externo = '';
        if ($externo > 0) {
            $sql_externo =
                ' and idusuario in (select idusuario from mds_seg_usuario where externo>0)';
        }

        return Sds_ent_entrega::findBySql(
            "SELECT identrega,receptor,fecha_hora,cantidad,emisor,entrega.idtipo,
                    tipo.descripcion detalle_tipo,conf.descripcion nombre_receptor, 
                    numero_desde, numero_hasta,oc
                    FROM sds_ent_entrega entrega 
                    left join sds_ent_tipo tipo on tipo.idtipo=entrega.idtipo
                    left join sds_com_configuracion conf on conf.idconfiguracion=entrega.receptor
                    where (emisor=$identrega or ($identrega=-1 and emisor is null)) and dni is null
                    and (
                        ($idtipo=-1 and entrega.idtipo in 
                            (select idtipo from mds_seg_usuario_entrega_tipo ut where ut.idusuario=" . $idusuario . ')
                         ) or entrega.idtipo=' . $idtipo . '
                     ) ' . $sql_externo .
                ' order by numero_desde,numero_hasta,fecha_hora asc '
        )->all();
    }

    public static function getArbolIds($identregas, $idtipo, $externo)
    {
        $identregas_arbol = '';
        $identregas = str_contains($identregas, ',')
            ? explode(',', $identregas)
            : [$identregas];
        foreach ($identregas as $identrega) {
            $identregas_arbol = empty($identregas_arbol)
                ? $identrega
                : $identregas_arbol . ',' . $identrega;
            $entregas_hijas = Sds_ent_entrega::getEntregasHijas(
                $identrega,
                $idtipo,
                -1,
                -1,
                $externo,                
            );
            $tiene_hijas = !empty($entregas_hijas);
            $id_hijas = '';
            if ($tiene_hijas) {
                foreach ($entregas_hijas as $entrega) {
                    $ids = Sds_ent_entrega::getArbolIds(
                        $entrega->identrega,
                        $idtipo,
                        $externo
                    );
                    if ($ids != '') {
                        $id_hijas =
                            $id_hijas . ($id_hijas != '' ? ',' : '') . $ids;
                    }
                }
            }
            if ($id_hijas != '') {
                $identregas_arbol = $identregas_arbol . ',' . $id_hijas;
            }
        }
        return $identregas_arbol;
    }

    /** Acá paso como parámetro codigos de entregas separados por ','. Ej: 1,23,4321,...,n */
    public static function getEntregasFinales($codEntregas)
    {
        return Sds_ent_entrega::findBySql(
            "SELECT identrega,entrega.numero,
                    (select descripcion from sds_ent_tipo tipo where tipo.idtipo=entrega.idtipo) detalle_tipo,
                    (select descripcion from sds_com_configuracion conf,sds_ent_entrega emisor 
                    where emisor.identrega=entrega.emisor and conf.idconfiguracion=emisor.receptor) nombre_emisor,
                    fecha_hora,pers.nombre,pers.apellido,dni,cantidad,observaciones
            FROM sds_ent_entrega entrega
            left join sds_com_persona pers on pers.documento = entrega.dni
            where dni is not null and emisor in ($codEntregas)
            order by numero,fecha_hora"
        )->all();
    }

    public static function getEntregasFinalesTotal($codEntregas)
    {
        $entrega = Sds_ent_entrega::findBySql(
            "SELECT ifnull(SUM(cantidad),0) cantidad
            FROM sds_ent_entrega entrega
            where dni is not null and emisor in ($codEntregas)"
        )->one();
        return $entrega != null ? $entrega->cantidad : 0;
    }

    public static function getSaldo($identrega)
    {
        $saldo = Sds_ent_entrega::findBySql(
            "SELECT ifnull(SUM(cantidad),0) cantidad
            FROM sds_ent_entrega entrega
            where emisor=$identrega"
        )->one();
        return $saldo != null ? $saldo->cantidad : 0;
    }

    public static function getDevueltas($identrega)
    {
        $devueltas = Sds_ent_entrega::findBySql(
            "select ifnull(sum(cantidad),0) cantidad from sds_ent_cierre
        where identrega=$identrega and motivo in
        (select idconfiguracion from sds_com_configuracion where idconfiguracion=2327)"
        )->one();
        return $devueltas != null ? $devueltas->cantidad : 0;
    }

    public static function getCerradas($identrega)
    {
        $cerradas = Sds_ent_entrega::findBySql(
            "select ifnull(sum(cantidad),0) cantidad 
            from sds_ent_cierre
            where identrega=$identrega"
        )->one();
        return $cerradas != null ? $cerradas->cantidad : 0;
    }

    public static function getPrimerAnio()
    {
        //Aca para no complicar el asunto, le mando el año al campo numero para hacer la consulta mas facil con yii (que trucazo no?)
        $entrega = Sds_ent_entrega::findBySql(
            "SELECT YEAR(fecha_hora) numero
            FROM sds_ent_entrega entrega
            order by fecha_hora limit 1"
        )->one();
        return $entrega != null ? $entrega->numero : date('Y');
    }

    public static function getRendicionesPendientes(
        $idresponsable,
        $idtipo,
        $fecha_hora
    ) {
        //Las rendiciones pendientes deben calcularse y guardarse del mismo tipo que la solicitud de entrega.
        //Ej: si la solicitud de entrega es de Bonos de Gas, sólo se deben traer lo que resta rendir de bonos de gas.
        return Sds_ent_entrega::findBySql(
            "SELECT fecha_hora,tipo.descripcion detalle_tipo,cantidad, cantidad - IFNULL(rendidas,0) saldo
        FROM sds_ent_entrega ent
        JOIN sds_ent_tipo tipo ON tipo.idtipo= ent.idtipo
        LEFT JOIN (SELECT emisor,sum(cantidad) rendidas
        FROM sds_ent_entrega entfinal
        GROUP BY emisor) tempRend ON tempRend.emisor=ent.identrega
        WHERE receptor=" .
                $idresponsable .
                " and ent.fecha_hora < '" .
                $fecha_hora .
                "'
        and YEAR(ent.fecha_hora) >=2021 and (ent.idtipo=$idtipo or $idtipo=-1)
        having saldo>0
        ORDER BY detalle_tipo,fecha_hora"
        )->all();
    }

    public static function getTotal($tipo, $responsable, $anio)
    {
        $total = Sds_ent_entrega::findBySql(
            "select count(*) cantidad from sds_ent_entrega entrega
        where (entrega.receptor=$responsable or $responsable=-1) and $anio=YEAR(entrega.fecha_hora) 
        and (entrega.idtipo=$tipo or $tipo=-1)"
        )->one();
        return $total != null ? $total->cantidad : 0;
    }


    public function toString()
    {
        $fc = date_create($this->fecha_hora);
        $fc = date_format($fc, 'd/m/Y H:i');
        $receptor =
            $this->receptor != null
            ? Sds_com_configuracion::findOne($this->receptor)->descripcion
            : $this->dni;
        $oc = $this->oc;
        return $this->identrega .
            ' - ' .
            $fc .
            ' - ' .
            ($oc != null && $oc != 0 ? "OC: " . $oc . ' - ' : "") .
            $receptor .
            ' - Cant.: ' .
            $this->cantidad;
    }
}
