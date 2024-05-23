var oldLink = null;
function selected(cal, date) 
{
    cal.sel.value = date; // just update the date in the input field.
    if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
        cal.callCloseHandler();
}
function closeHandler(cal) 
{
    cal.hide();                        // hide the calendar
    _dynarch_popupCalendar = null;
}
var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;
function isDisabled(date) 
{
    var today = new Date();
    return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}
function flatSelected(cal, date) {
	
  //llamar a una funcion del xajax
  if (document.getElementById('idprofesional').value != "")
  {
      var idprof = document.getElementById('idprofesional').value;
	  var fecha = date;
	  var idlugar = document.getElementById('lugar_atencion').value;
	  xajax_generarGrilla(idprof,fecha,idlugar,document.getElementById('idprofesional_logueado').value);
  } 	  
}
var cal = new Calendar(0, null, flatSelected);  
function showFlatCalendar() 
{
    var parent = document.getElementById("medioizqarr");
    cal.weekNumbers = false;
    cal.create(parent);
    cal.show();
}
function buscarPacienteTurnos(e,dni,hora_turno,idprofesional)
{
    //llamar a funcion de xajax
    var hora = document.getElementById(hora_turno).value;
    var doc = document.getElementById(dni).value;
	var idprof = document.getElementById(idprofesional).value;
	var fecha = cal.date.print(cal.dateFormat);	
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==13) 
	{
	    if (idprof != "")
		{
		    if (hora != "")
			{
			    var idlugar = document.getElementById('lugar_atencion').value;
			    var sobreturno = document.getElementById('es_sobreturno').value;
			     xajax_darTurno(idprof,fecha,hora,doc,idlugar,sobreturno,document.getElementById('idprofesional_logueado').value);
				 document.getElementById('sobre_turnos').style.display='none';
				 //desde dar turno llamo a generar grilla
				// xajax_generarGrilla(idprof,fecha);
			}
			else
			    alert("Debe seleccionar un horario para el turno");	
		}
		else
		    alert("Debe seleccionar un profesional");	
	}
}
function abrirHistoriaClinca(idpaciente_turno,idprofesional)
{    
    var idpaciente = document.getElementById(idpaciente_turno).value;
	var idprof = document.getElementById(idprofesional).value;

	if (idpaciente != "")
	{	 	
		window.open("historia_clinica/HC_turnos.php?idpaciente="+idpaciente+"&idprofesional="+idprof);
	}
	else
	    alert("Debe seleccionar un paciente");
}
//el parametro sobreturno dice solo si es sobreturno o no
function setearHora(hora,sobreturno)
{
    document.getElementById('hora_turno').value = hora;
    document.getElementById('es_sobreturno').value = sobreturno;
}
function setearPaciente(id)
{
    document.getElementById('idpaciente_turno').value = id;
}
function recuperarGrillaHorarios(idprofesional)
{
    //llamar a una funcion de xajax
	
   var idprof = document.getElementById(idprofesional).value;
   var fecha = cal.date.print(cal.dateFormat);
   var idlugar = document.getElementById('lugar_atencion').value;
   //traigo la grilla
   xajax_generarGrilla(idprof,fecha,idlugar,document.getElementById('idprofesional_logueado').value);
	//traigo las observaciones del profesional
   xajax_observacionesProfesional(idprof);	
}
function cambiarEstado(estado,obra_social,observaciones,codigo,idprofesional,fecha,hora)
{
    //llamar a una funcion xajax
    var e = document.getElementById(estado).value;
	var os = document.getElementById(obra_social).value;
	var obs = document.getElementById(observaciones).value;
	var cod = document.getElementById(codigo).value;
	var idprof = document.getElementById(idprofesional).value;
	var arrayFecha = Array();
	arrayFecha = fecha.split('-');	
	xajax_guardarGrilla(idprof,fecha,hora,"",cod,e,obs,"","","",os,"","","",document.getElementById('lugar_atencion').value,document.getElementById('idprofesional_logueado').value);
    //desde guardar grilla se llama a generar grilla
}
function suspenderTurno(idprofesional,fecha,hora)
{
    //llamar a una funcion xajax
	var idprof = document.getElementById(idprofesional).value;
	xajax_suspenderTurno(idprof,fecha,hora);
	recuperarGrillaHorarios(idprofesional);	
}
