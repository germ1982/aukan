

<?php
use app\models\Mds_hor_franco;
use app\models\Mds_org_contacto;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_francoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Francos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

/* $JSEventClick = 'function(calEvent, jsEvent, view) {
    var url = "/mds/web/index.php?r=mds_hor_franco/update_ext&id="+calEvent.id;
    $("#modal_abm").modal("show").find("#content_abm").load(url);
    $("#header_abm").html("Editar Franco");
}'; */

$JSEventRender = 'function(date, element, view) {
    element.hide();
}';

$JSAfterloading = "function(view) {
    //loop through each non-disabled day cell
    $('.fc-day').each(function(index, element) {        
        var fecha = new Date(Date.parse($(this).data('date')+'T00:00:00'));
        var eventoFecha = dateGetEvent(fecha);
        if (eventoFecha!=null){
            console.log(eventoFecha, 'EVTFECHA');
            $(this).css('background-color', '#D5EEFF');
            $(this).css('color', '#666');
            $(this).html('<h5 style=\"margin:1px;color:#444;\"><b>'+eventoFecha.title['tipo']+'</b></h5>'+eventoFecha.title['descripcion']);
        }
        else {
            $(this).css('background-color', '');
            $(this).html('');
        }
    });
}";
$JSDayRender = 'function(date, element, view) {
    element.dblclick(function() {
        var fechaAux = new Date(Date.parse(date.toString().substring(0,15)));        
        accionFranco(fechaAux);
    });
}';

$model = new Mds_hor_franco();
if ($idcontacto > 0) {
    $model->idcontacto = $idcontacto;
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
?>

<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li><a href="index.html"><i class="fa fa-home"></i></a></li>
            <li><span><?= $this->title ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                ]);?>
                <div class="row">
                   <div class="col-md-4">
                        <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                            'data' => ArrayHelper::map(
                                Mds_org_contacto::findBySql(
                                    "SELECT c.*, p.*
                                    FROM mds_org_contacto c
                                        INNER JOIN sds_com_persona p ON c.idpersona=p.idpersona
                                        INNER JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                                    WHERE
                                        legajo IS NOT NULL AND c.activo AND
                                        d.idcapaitem IN (SELECT ic.idcapaitem FROM mds_seg_usuario_capa_item ic WHERE idusuario=".$idusuario.")
                                        OR IFNULL((SELECT COUNT(idusuario) FROM mds_seg_usuario_capa_item WHERE idusuario=".$idusuario."), 0) = 0
                                    ORDER BY apellido,nombre")->all(),
                                'idcontacto',
                                function ($model) {
                                    return ($model->legajo != null ? $model->legajo : "00000") . " - " . $model->nombre . " " . $model->apellido;
                                }
                            ),
                            'options' => ['placeholder' => 'Seleccione Empleado ...'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ])->label("Empleado"); ?>
                    </div>
                    <div class="col-md-offset-6 col-md-2" style="text-align:right; padding-top: 27px">
                        <a id="btn_clonar" class="btn btn-primary" style="margin-left: 1%;" title="Clonar Francos de Mes Actual"> Clonar Francos</a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                
                <div class="mds-hor-franco-index">
                    <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
                        'id' => 'francalendar',
                        'events' => array(),
                        'ajaxEvents' => new \yii\web\JsExpression("\"index.php?r=mds_hor_franco/refresh_index&idcontacto=\" + ($(\"#mds_hor_franco-idcontacto\").val() ? $(\"#mds_hor_franco-idcontacto\").val():-1)"),
                        'clientOptions' => [
                            'height' => new \yii\web\JsExpression("document.documentElement.clientHeight-document.documentElement.clientHeight*0.03"),
                            // 'language' => 'fa',
                            //'eventLimit' => TRUE,
                            'timeZone' => 'local',
                            'selectable' => false,
                            'selectHelper' => false,
                            'droppable' => true,
                            'editable' => false,
                            'fixedWeekCount' => false,
                            'defaultDate' => date('Y-m-d'),
                            'eventAfterAllRender' => new \yii\web\JsExpression($JSAfterloading),
                            //'dayClick' => new \yii\web\JsExpression($JSEventClick),
                            'dayRender' => new \yii\web\JsExpression($JSDayRender),
                            'eventRender' => new \yii\web\JsExpression($JSEventRender),
                            //'eventClick' => new \yii\web\JsExpression($JSEventClick),
                            'timeFormat' => 'HH:mm'
                            //'select'=>new JsExpression($JSCode)
                        ],
                    )); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
$this->registerJs("$('#modal_abm').on('hidden.bs.modal', function() {
    $('#francalendar').fullCalendar('refetchEvents');
})");
$this->registerJs("$('#mds_hor_franco-idcontacto').change(function(){        
    refreshCalendar();
});");
$this->registerJs("$('.fc-day-top').dblclick(function(element){
    var fechaBase = new Date(Date.parse(element.target.attributes['data-date'].value+'T00:00:00'));
    accionFranco(fechaBase);
});");
$this->registerJs("$('#btn_clonar').click(function(element){    
    clonarFrancos();
});");
$script = <<<  JS

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

function refreshCalendar() {
    console.log("idcontacto: "+$("#mds_hor_franco-idcontacto").val());
    $('#francalendar').fullCalendar('removeEventSources');
    $('#francalendar').fullCalendar('removeEvents');
    var events = "index.php?r=mds_hor_franco/refresh_index&idcontacto=" + ($("#mds_hor_franco-idcontacto").val() ? $("#mds_hor_franco-idcontacto").val():-1);    
    $('#francalendar').fullCalendar('addEventSource', events);
    $('.fc-day').each(function(index, element) {        
        var fecha = new Date(Date.parse($(this).data('date')+'T00:00:00'));
        var eventoFecha = dateGetEvent(fecha);
        if (eventoFecha!=null){
            $(this).css('background-color', '#D5EEFF');
            $(this).html('<h5 style=\"margin-top:1px;\">Franco</h5>'+eventoFecha.title);
        }
        else {
            $(this).css('background-color', '');
            $(this).html('');
        }
    });
}

/* ------------------------------------------------------------------------------------- */
function accionFranco(fecha){
    var eventoFecha = dateGetEvent(fecha);
    var idcontacto = $('#mds_hor_franco-idcontacto').val();
    var texto = '<div style="text-align:center"><h2>Atencion!!!</h2></div><br><div style="text-align:center"><br><h4>Debe seleccionar un Empleado!!!</h4></div>';

    if ($idusuario==1){
        texto = '<div style="text-align:center"><h2>AH!!! AH!!! AH!!!</h2></div><br><div style="text-align:center"><img src="https://c.tenor.com/0bn7ZRzdNpkAAAAd/nope-not-a-chance.gif" alt="gif de algo"><br><h4>Debe seleccionar un Empleado!!!</h4></div>';
    }

    if(idcontacto==0){
        $.alert({
            title: '',
            //content: 'Debe seleccionar un contacto',
            content: texto,
            type: 'orange',
        });
        //alert('Debe seleccionar un contacto','hola');
        return;
    }        
/* ------------------------------------------------------------------------------------- */
    if (eventoFecha==null){
        nuevoFranco(fecha);
    }
    else {
        var url = "index.php?r=mds_hor_franco/update_ext&id="+eventoFecha.id;
        $("#modal_abm").modal("show").find("#content_abm").load(url);
        $("#header_abm").html("Editar Franco");
    }
}

function nuevoFranco(fecha){
    fecha = formatearFecha(fecha);
    var url = "index.php?r=mds_hor_franco/create_ext&idcontacto="+$('#mds_hor_franco-idcontacto').val()+"&fecha="+fecha;
    $("#modal_abm").modal("show").find("#content_abm").load(url);
    $("#header_abm").html("Agregar Franco");
}

function clonarFrancos(){
    var date = $("#francalendar").fullCalendar('getDate');
    console.log(date);
    var month = date.month()+1;
    var year = date.year();
    var url = "index.php?r=mds_hor_franco/clonar_francos&idcontacto="+$('#mds_hor_franco-idcontacto').val()+"&mes="+month+"&anio="+year;
    $("#modal_abm").modal("show").find("#content_abm").load(url);
    $("#header_abm").html("Clonar Francos de contacto");
}

function dateGetEvent(date) {
    var allEvents = [];
    allEvents = $('#francalendar').fullCalendar('clientEvents');
    var event = $.grep(allEvents, function (v) {
        return formatearFecha(new Date(Date.parse(v.start.toString().substring(0,15)))) === formatearFecha(date);
    });
    return event.length > 0 ? event[0] : null;
}

function formatearFecha(fecha) {     
    var day = fecha.getDate();
    var month = fecha.getMonth()+1;
    var year = fecha.getFullYear();
    fecha = day + "/" + month + "/" + year;
    return fecha;
}

JS;

$this->registerJs($script);

Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    "id" => "modal_abm",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => 'modal-md',
    "footer" => "", // always need it for jquery plugin
]);
echo "<div id='content_abm'></div>";
Modal::end();

?>