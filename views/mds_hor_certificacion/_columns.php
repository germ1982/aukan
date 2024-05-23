<?php
use app\models\Mds_hor_certificacion;
use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\TimePicker;
use kartik\helpers\Html;
// ESTO ES MOSTRAR LOS AÑOS EXISTENTES EN LAS CERTIFICACIONES Y PONERLOS EN EL FILTRO DEL LOS AÑOS
$mysql_anios = 'SELECT * FROM mds_hor_certificacion Where periodo_anio >=1  group by periodo_anio order by periodo_anio';

$anios = array();

$certificaciones = Mds_hor_certificacion::findBySql($mysql_anios)->all();
foreach($certificaciones as $certificacion)
    {
        $anios[$certificacion->periodo_anio]=$certificacion->periodo_anio;
    }

// ESTO ES PARA EL FILTRO DE LOS MESES
function get_mes($mes)
    {
    switch ($mes)
    {
        case "1":
            $mes = "Enero";
            break;

        case "2":
            $mes =  "Febrero";
            break;

        case "3":
            $mes =  "Marzo";
            break;

        case "4":
            $mes =  "Abril";
            break;
        case "5":
            $mes = "Mayo";
            break;

        case "6":
            $mes =  "Junio";
            break;

        case "7":
            $mes =  "Julio";
            break;

        case "8":
            $mes =  "Agosto";
            break;
        case "9":
            $mes = "Septiembre";
            break;

        case "10":
            $mes =  "Octubre";
            break;

        case "11":
            $mes =  "Noviembre";
            break;

        case "12":
            $mes =  "Diciembre";
            break;
    } 
    return $mes;
    }
$meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

$columna0 = '15%';
$columna1 = '15%';
$columna2 = '10%';
$columna3 = '8%';
$columna4 = '6%';
$columna5 = '6%';
$columna6 = '20%';
$columna7 = '10%';
$columna8 = '10%';

//Verifico que el usuario tenga idusuario asignado, caso contrario redirecciono a Login
$idusuario=Yii::$app->user->identity->idusuario;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'certificado',
        'label'=>'Empleado',
        'value' => function ($model) {
            if ($model->certificado != null) {
                $contacto = Mds_org_contacto::findOne($model->certificado);
                if ($contacto->idpersona != null) {
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    if($persona!=null){
                        return $contacto->legajo . " - " . $persona->nombre . " " . $persona->apellido;
                    }
                    return '-Error-';
                }
            }
            return "- SIN DATOS -";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_contacto::findBySql(
                "SELECT c.*, p.*
                FROM mds_org_contacto c
                INNER JOIN sds_com_persona p ON c.idpersona=p.idpersona
                INNER JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                WHERE 
                d.idcapaitem IN
                (SELECT ic.idcapaitem FROM mds_seg_usuario_capa_item ic WHERE idusuario=".$idusuario.") 
                OR IFNULL((SELECT COUNT(ic.idusuario) FROM mds_seg_usuario_capa_item ic WHERE ic.idusuario=".$idusuario."), 0) = 0
                ORDER BY p.apellido, p.nombre")->all(),
            'idcontacto',
            function ($model) {
                return $model->legajo . " - " . $model->nombre . " " . $model->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empleado...'],
        'format' => 'raw',
        'width' => $columna0,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'certificante',
        'label'=>'Certificante',
        'value' => function ($model) {
            if ($model->certificante != null) {
                $contacto = Mds_org_contacto::findOne($model->certificante);
                if ($contacto->idpersona != null) {
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    if($persona!=null){
                        return $contacto->legajo . " - " . $persona->nombre . " " . $persona->apellido;
                    }
                    return '-Error-';
                }
            }
            return "- SIN DATOS -";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_contacto::findBySql("SELECT c.*, p.* FROM mds_org_contacto c 
                LEFT JOIN mds_hor_certificacion ct ON c.idcontacto = ct.certificante
                LEFT JOIN sds_com_persona p ON p.idpersona=c.idpersona
                ORDER BY trim(p.nombre), trim(p.apellido)")->all(),
            'idcontacto',
            function ($model) {
                return $model->legajo . " - " .$model->nombre . " " . $model->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empleado...'],
        'format' => 'raw',
        'width' => $columna1,
    ],
    [//Combo Comun para datos a mano
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo_mes',
        'value' => function ($model) {
            $periodo_mes = $model->periodo_mes;
            if ($periodo_mes != null) {
                return get_mes($periodo_mes);
            }
            return "";
        },
        //'width' => $columna2,
        'filter' => $meses,//uso un array para el filter
        'width' => $columna2,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'periodo_anio',
        'filter' => $anios,
        'label' => 'Periodo Año',
        'width' => $columna3,
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'desde',
        'value' => function ($model) {
            $hora_desde = $model->desde;
            if ($hora_desde != null) {
                $hora_desde = date("H:i",strtotime($hora_desde));
                return $hora_desde;
            }
            return "";
        },
        'width' => $columna4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'hasta',
        'value' => function ($model) {
            $hora_hasta = $model->hasta;
            if ($hora_hasta != null) {
                $hora_hasta = date("H:i",strtotime($hora_hasta));
                return $hora_hasta;
            }
            return "";
        },
        'width' => $columna5,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle',
        'width' => $columna6,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'value' => function ($model) {
            
            $estado = $model->estado;
            switch($estado)
                {
                    case 0:
                        return 'Pendiente';
                    case 1:
                        return 'Generado';
                }
            return "";
        },
        'filter' => array(0=>'Pendiente',1=>'Generado'),
        'width' => $columna7,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update} {generar} {delete}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar Certificación',
                          'data-confirm-message'=>'Si la certifiacción fue generada se eliminarán los registros horarios asociados.<br>
                            ¿Desea continuar?'], 
        'buttons' => [
            'generar' => function ($url, $model) {
                $url =  Url::to(['/mds_hor_certificacion/generar', 'id' => $model->idcertificacion]);
                return $model->estado == 1 ? '<span class= "glyphicon glyphicon-calendar"></span>' : 
                    Html::a('<span class= "glyphicon glyphicon-calendar"></span>', $url, [
                        'title'=>'Generar',
                        'role'=>'modal-remote',
                        'data-toggle' => 'tooltip',
                    ]);
            },
            'delete' =>function($url, $model){
                $url =  Url::to(['/mds_hor_certificacion/delete', 'id' => $model->idcertificacion]);
                return Html::a('<span class= "glyphicon glyphicon-trash text-danger"></span>', $url, [
                    'title' => "Borrar Certificación",
                    'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-toggle'=>'tooltip',
                    'data-confirm-title'=>'Eliminar Certificación',
                    'data-confirm-message'=>'
                        <span class="text-danger">Si la certifiacción fue generada se eliminarán los registros horarios asociados.<br>
                        ¿Desea continuar?</span>',
                ]);
            },
        ],
        'width' => $columna8,
    ],

];   