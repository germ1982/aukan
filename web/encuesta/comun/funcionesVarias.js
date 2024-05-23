function desplegar(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan)
{//,tabla,idTabla,descripcionTabla,orden,listaDesplegable,input

    var bufferTime = 500;
	var bufferText = false;//'+tabla+','+idTabla+','+descripcionTabla+','+orden+','+listaDesplegable+','+input+',
    setTimeout('compareBuffer("'+strId+'","'+xajax.$(strId).value+'","'+bufferText+'","'+funcion_xajax+'","'+iddiv+'","'+id+'","'+tabla+'","'+idtabla+'","'+descripcion+'","'+where+'","'+orden+'","'+seleccion+'","'+deboPegar+'","'+queDeboPegar+'","'+lugarAPegar+'","'+widthSpan+'");', this.bufferTime);
}
function compareBuffer(strId,strText,bufferText,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan)
{//,tabla,idTabla,descripcionTabla,orden,listaDesplegable,input
    if (strText == xajax.$(strId).value && strText != bufferText)
    {
        bufferText = strText;
        makeRequest(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan);
    }//idTabla,descripcionTabla,orden,listaDesplegable,input,
}
function makeRequest(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan)
{//,idTabla,descripcionTabla,orden,listaDesplegable,input
    var string = funcion_xajax+"'"+xajax.$(strId).value+"','"+iddiv+"','"+id+"','"+strId+"','"+tabla+"','"+idtabla+"','"+descripcion+"','"+where+"','"+orden+"','"+seleccion+"','"+deboPegar+"','"+queDeboPegar+"','"+lugarAPegar+"','"+widthSpan+"');";
    //alert(string);
	eval(string);	
}
function desplegarConFuncion(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan,debeColocarFuncion,nombreFuncion,idtablaReal,dondePegoDato,dondePegoDetalle,ordenSiguienteAnterior,widthSpanDatos,widthSpanDetalle)
{//,tabla,idTabla,descripcionTabla,orden,listaDesplegable,input
    var bufferTime = 500;
	var bufferText = false;//'+tabla+','+idTabla+','+descripcionTabla+','+orden+','+listaDesplegable+','+input+',
    setTimeout('compareBufferConFuncion("'+strId+'","'+xajax.$(strId).value+'","'+bufferText+'","'+funcion_xajax+'","'+iddiv+'","'+id+'","'+tabla+'","'+idtabla+'","'+descripcion+'","'+where+'","'+orden+'","'+seleccion+'","'+deboPegar+'","'+queDeboPegar+'","'+lugarAPegar+'","'+widthSpan+'","'+debeColocarFuncion+'","'+nombreFuncion+'","'+idtablaReal+'","'+dondePegoDato+'","'+dondePegoDetalle+'","'+ordenSiguienteAnterior+'","'+widthSpanDatos+'","'+widthSpanDetalle+'");', this.bufferTime);
}
function compareBufferConFuncion(strId,strText,bufferText,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan,debeColocarFuncion,nombreFuncion,idtablaReal,dondePegoDato,dondePegoDetalle,ordenSiguienteAnterior,widthSpanDatos,widthSpanDetalle)
{//,tabla,idTabla,descripcionTabla,orden,listaDesplegable,input
    if (strText == xajax.$(strId).value && strText != bufferText)
    {
        bufferText = strText;
        makeRequestConFuncion(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan,debeColocarFuncion,nombreFuncion,idtablaReal,dondePegoDato,dondePegoDetalle,ordenSiguienteAnterior,widthSpanDatos,widthSpanDetalle);
    }//idTabla,descripcionTabla,orden,listaDesplegable,input,
}
function makeRequestConFuncion(strId,funcion_xajax,iddiv,id,tabla,idtabla,descripcion,where,orden,seleccion,deboPegar,queDeboPegar,lugarAPegar,widthSpan,debeColocarFuncion,nombreFuncion,idtablaReal,dondePegoDato,dondePegoDetalle,ordenSiguienteAnterior,widthSpanDatos,widthSpanDetalle)
{//,idTabla,descripcionTabla,orden,listaDesplegable,input
    var string = funcion_xajax+"'"+xajax.$(strId).value+"','"+iddiv+"','"+id+"','"+strId+"','"+tabla+"','"+idtabla+"','"+descripcion+"','"+where+"','"+orden+"','"+seleccion+"','"+deboPegar+"','"+queDeboPegar+"','"+lugarAPegar+"','"+widthSpan+"','"+debeColocarFuncion+"','"+nombreFuncion+"','"+idtablaReal+"','"+dondePegoDato+"','"+dondePegoDetalle+"','"+ordenSiguienteAnterior+"','"+widthSpanDatos+"','"+widthSpanDetalle+"');";
    //alert(string);
	eval(string);	
}
//esta funcion se deberia llevar cuando me llevo los estudios pendientes tambien.
function reclamarEstudio(fecha,hora,idpedido_estudio,idprofesional,que_es)
{    
    ajax_alerta=nuevoAjax(); 
    ajax_alerta.open("POST",root+"estudios/solicitud_estudio/realizarAlerta.php",true);      		 
    ajax_alerta.onreadystatechange=function() 
	{ 
	    if (ajax_alerta.readyState==4) 
		{        		    
		} 
	} 
	ajax_alerta.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax_alerta.send("idpedido_estudio="+idpedido_estudio+"&fecha="+fecha+"&hora="+hora+"&idprofesional="+idprofesional+"&que_es="+que_es);
}
//fin de funcion que hace el aotucompletar
function mataAspectos()
{
	document.getElementById('otrasDivs').style.display = "none";
//	document.getElementById('aspectos_positivos').innerHTML = "";
}
function verAspectos()
{
	document.getElementById('otrasDivs').style.display = "inline";
}

function ajaxGenerico ()
// ajaxGenerico (objeto, url, metodo, param1, param2, ... , paramN)
{
	var i;
	var argsMin = 4;
	args=ajaxGenerico.arguments;
	if (args.length>=argsMin)
	{
		objeto = args[0];
		iraurl = args[1]+'?';
		metodo = args[2];
		for (i=argsMin-1; i<args.length; i++)
		{
			iraurl = iraurl + args[i] + "&";
		}
		iraurl = iraurl + "random=" + Math.random();
		ajaxOb=nuevoAjax(); 
		var aleatorio=Math.random();
		ajaxOb.open(metodo,iraurl); 
		ajaxOb.onreadystatechange=function() 
		{ 
			if (ajaxOb.readyState==4) 
			{ 
				if (objeto!="")
				{
					var contenedor = document.getElementById(objeto);
					contenedor.innerHTML = ajaxOb.responseText;
				}
			} 
		} 
		ajaxOb.send(null);
	}
}

function setearAlto(objeto,alto)
{
	obj = document.getElementById(objeto);
	obj.style.height = alto;
}

function switchCSSProp(obj, prop, val1, val2)
{
	// 1: height
	// 2: width
	// 3: display
	// 4: className
	// 5: 
	// 6: 
	// 7: 
	// 8: 
	// 9: 
	objeto = document.getElementById(obj);
	switch (prop)
	{
		case 1:
			if (objeto.style.height==val1) {objeto.style.height=val2;}
			else {objeto.style.height=val1;}
			break;

		case 2:
			if (objeto.style.width==val1) {objeto.style.width=val2;}
			else {objeto.style.width=val1;}
			break;

		case 3:
			if (objeto.style.display==val1) {objeto.style.display=val2;}
			else {objeto.style.display=val1;}
			break;

		case 4:
			if (objeto.className==val1) {objeto.className=val2;}
			else {objeto.className=val1;}
			break;

		default:
			alert('nada');
			break;
	}
}

function mostrarNomostrar(objeto)
{
	obj = document.getElementById(objeto);
	if (obj.style.display !== 'none') {obj.style.display='none';} else {obj.style.display='inline';}
}
	
var patron = new Array(2,2,4);
var patron2 = new Array(2,2);
var patron1 = new Array(2,8,1);
function mascara(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}

//obtiene la hora del sistema
function obtenerHora()
{
    var Digital=new Date();
    var hours=Digital.getHours();
	var minutes=Digital.getMinutes();
	var seconds=Digital.getSeconds();
	var dn="AM";
	
	if (hours==0)
		hours=00;
	if (minutes<=9)
		minutes="0"+minutes;
	if (seconds<=9)
		seconds="0"+seconds;
	return hours+":"+minutes+":"+seconds;
}
//funcion que calcula cuanto dias horas semans falta entre 2 fechas
function faltan(fecha1,hora1,fecha2,hora2)
{   //alert("fecha1="+fecha1+" hora1="+hora1+" fecha2="+fecha2+" hora2="+hora2);
    f1 = fecha1.split("/");   
    f2 = fecha2.split("/");
    h1 = hora1.split(":");
    h2 = hora2.split(":");
//	mesFinal = f[2]-1;
    fechaActual = new Date(f2[2],f2[1],f2[0],h2[0],h2[1],h2[2]);
//	alert(fechaActual);
    fechaAnterior = new Date(f1[2],f1[1],f1[0],h1[0],h1[1],h1[2]);
//	alert(f1[2]+"/"+f1[0]+"/"+f1[1]);
//	alert(fechaAnterior); 
//	alert(f1[2]+" "+f1[1]+" "+f1[0]+" "+h2[0]+" "+h2[1]+" "+h2[2]);
    diferencia = fechaActual - fechaAnterior
    diferenciaSegundos = diferencia /1000
    diferenciaMinutos = diferenciaSegundos/60
    diferenciaHoras = diferenciaMinutos/60
    diferenciaDias = diferenciaHoras/24
    diferenciaHoras2 = parseInt(diferenciaHoras) - (parseInt(diferenciaDias) *24)
    diferenciaMinutos2 = parseInt(diferenciaMinutos) - (parseInt(diferenciaHoras) * 60)
    diferenciaSegundos2 = parseInt(diferenciaSegundos) - (parseInt(diferenciaMinutos) * 60)
    diferenciaDias = parseInt(diferenciaDias)	
 //   if (diferenciaDias < 10 && diferenciaDias > -1){diferenciaDias = "0" + diferenciaDias}
  //  if(diferenciaHoras2 < 10 && diferenciaHoras2 > -1){diferenciaHoras2 = "0" + diferenciaHoras2}
   // if(diferenciaMinutos2 < 10 && diferenciaMinutos2 > -1){diferenciaMinutos2 = "0" + diferenciaMinutos2}
    //if(diferenciaSegundos2 < 10 && diferenciaSegundos2 > -1){diferenciaSegundos2 = "0" + diferenciaSegundos2}
	if (diferenciaDias > 0)
	    return 999999;
	else
	{
	    if (diferenciaHoras2 > 7)
		    return 999999;
		else
		    return 0;	
	}   	
 /*   if(diferenciaDias <= 0 && diferenciaHoras2<= 0 && diferenciaMinutos2 <= 0 && diferenciaSegundos2 <= 0)
	{
	    diferenciaDias = 0
//	    diferenciaHoras2 = 0
	    diferenciaMinutos2 = 0
	    diferenciaSegundos2 = 0
	    return diferenciaHoras2
	}
    else	
	    return diferenciaHoras2*/	
}
//////////////////////////////////////////////////////////////////
///////////suma n dias a la fecha que se de como parametros
function sumaFecha(d, fecha)
{
 var Fecha = new Date();
 var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
 var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
 var aFecha = sFecha.split(sep);
 var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
 fecha= new Date(fecha);
 fecha.setDate(fecha.getDate()+parseInt(d));
 var anno=fecha.getFullYear();
 var mes= fecha.getMonth()+1;
 var dia= fecha.getDate();
 mes = (mes < 10) ? ("0" + mes) : mes;
 dia = (dia < 10) ? ("0" + dia) : dia;
 var fechaFinal = dia+sep+mes+sep+anno;
 return (fechaFinal);
 }
//////////////////////////////////////////////////////////////////////
///////////////////////comapra si dos fechas son iguales, mayores o menores///////////////////
//Esta funcion te devuelve 0 en caso de que sean iguales 1 en caso de que fecha sea mayor que fecha1 y -1 cuando fecha sea menor que fecha1.
function comparaFecha(fecha,fecha1)
{
	fec=fecha.split("/");
	fec1=fecha1.split("/");
	if(fec[0]>fec1[0])
	{
		return 1;
	}
	else 
		if(fec[0]<fec1[0])
		{
			return -1;
		}
		else
		{
			if(fec[1]>fec1[1])
		{	
			return 1;
		}
		else 
		if(fec[1]<fec1[1])
		{
			return -1;
		}
		else
		{
			if(fec[2]>fec1[2])
			{
				return 1;
			}
			else 
				if(fec[2]<fec1[2])
				{
					return -1;
				}
				else
				{
					return 0;
				}
			}
		}
	} 
//////////////////////////////////////////////////////////////////////////////////////////////
//todo esto calcula la resta entre 2 horas
function padNmb(nStr, nLen){
    var sRes = String(nStr);
    var sCeros = "0000000000";
    return sCeros.substr(0, nLen - sRes.length) + sRes;
   }

   function stringToSeconds(tiempo){
    var sep1 = tiempo.indexOf(":");
    var sep2 = tiempo.lastIndexOf(":");
    var hor = tiempo.substr(0, sep1);
    var min = tiempo.substr(sep1 + 1, sep2 - sep1 - 1);
    var sec = tiempo.substr(sep2 + 1);
    return (Number(sec) + (Number(min) * 60) + (Number(hor) * 3600));
   }

   function secondsToTime(secs){
    var hor = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hor * 3600)) / 60);
    var sec = secs - (hor * 3600) - (min * 60);
    return padNmb(hor, 2) + ":" + padNmb(min, 2) + ":" + padNmb(sec, 2);
   }

   function substractTimes(t1, t2){
    var secs1 = stringToSeconds(t1);
    var secs2 = stringToSeconds(t2);
    var secsDif = secs1 - secs2;
    return secondsToTime(secsDif);
   }

   function restarHoras(){
    with (document.frm)
     t3.value = substractTimes(t1.value, t2.value);
   } 
//fin de calculo de resto de horas   
function pasarValor(objeto,valor)
{
	document.getElementById(objeto).value=valor;
}

function presionaEnter()
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	
	if (event.keyCode==13)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function updateRowNumber (objeto)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	
	if (event.keyCode==13)
	{
		textbox = document.getElementById(objeto);
		textbox.rows = textbox.rows+1;
	}
	else
	{
		return false;
	}
}

function escondeLayer(layer)
{
	document.getElementById(layer).style.display='none';
}

function muestraLayer(layer)
{
	document.getElementById(layer).style.display='inline';
}

function contraeCamas(idecamas, idedata, idanchor)
{
	document.getElementById(idecamas).style.width='250px';
	document.getElementById(idedata).style.display='inline';
	document.location=idanchor;
}

function expandeCamas(idecamas, idedata, idanchor)
{
	document.getElementById(idecamas).style.width='850px';
	document.getElementById(idedata).style.display='none';
	document.location=idanchor;
}

function ajaxCambia(pagina,ide)
{
	ajax=nuevoAjax(); 

	ajax.open("POST",root+pagina,true); 
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4) 
		{ 
			miContenedor = document.getElementById(ide); 
			miContenedor.innerHTML = ajax.responseText; 
		//	alert(ajax.responseText);
		} 
	} 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send(null)
}

function ocultarDiv(miDiv)
{
	tabCerrar = document.getElementById(miDiv);
	tabCerrar.style.display="none";
}

function mostrarDiv(miDiv)
{
	tabCerrar = document.getElementById(miDiv);
	tabCerrar.style.display="inline";
}

function pegarClass(objeto,clase)
{
	document.getElementById(objeto).className=clase;
}

function activarTab(objeto)
{
	pegarClass('s1','solapaLink');
	pegarClass('s2','solapaLink');
	pegarClass('s3','solapaLink');
	pegarClass('s4','solapaLink');
	pegarClass('s5','solapaLink');
	pegarClass('s6','solapaLink');
	pegarClass('s7','solapaLink');
	
	document.getElementById(objeto).className='solapaActiva';
}

function activarTabEnfe(objeto)
{
	pegarClass('s1','solapaLink');
	pegarClass('s2','solapaLink');
	document.getElementById(objeto).className='solapaActiva';
}

function activarTabEstudio(objeto)
{
	pegarClass('s1','solapaLink');
	
	pegarClass('s2','solapaLink');
	
	document.getElementById(objeto).className='solapaActiva';
}
function activarTabIndicaciones(objeto)
{
	pegarClass('tabMas1','miniTabs');
	pegarClass('tabMas2','miniTabs');
	pegarClass('tabMas3','miniTabs');
	pegarClass('tabMas4','miniTabs');
	pegarClass('tabMas5','miniTabs');
	pegarClass('tabMas6','miniTabs');
	pegarClass('tabMas7','miniTabs');
	
	document.getElementById(objeto).className='miniTabsActiva';
}

function activarTabIndicac(ind,num)
{
	pegarClass('tabMasA'+num,'miniTabs');
	pegarClass('tabMasB'+num,'miniTabs');
	pegarClass('tabMasC'+num,'miniTabs');
	pegarClass('tabMasD'+num,'miniTabs');
	pegarClass('tabMasE'+num,'miniTabs');
	pegarClass('tabMasF'+num,'miniTabs');
	pegarClass('tabMasG'+num,'miniTabs');
	pegarClass('tabMasH'+num,'miniTabs');
	objeto = 'tabMas'+ind+num;
	document.getElementById(objeto).className='miniTabsActiva';
}

function intercambiaClass(objeto,class1,class2) {
	classActual = document.getElementById(objeto).className;
	classa1 = class1 + " " + class1 + "onhover";
	classa2 = class2 + " " + class2 + "onhover";
	switch (classActual) {
		case classa1:
			document.getElementById(objeto).className=class2;
			break;
		case classa2:
			document.getElementById(objeto).className=class1;
			break;
	}
}

function move_up(objeto) {
	document.getElementById(objeto).scrollTop = 0;
}

function abrirCerrar(objeto,inicio,final) {
	var tamano = document.getElementById(objeto).style.height;
	
	switch (tamano) {
		case inicio:
			document.getElementById(objeto).style.height=final;
			document.getElementById(objeto).style.overflow="auto";
			break;
		case final:
			document.getElementById(objeto).style.height=inicio;
			document.getElementById(objeto).style.overflow="hidden";
			break;
	}
}

function soloAbrir(objeto,final) {
	document.getElementById(objeto).style.height=final;
	document.getElementById(objeto).style.overflow="auto";
}

function soloCerrar(objeto,inicio) {
	document.getElementById(objeto).style.height=inicio;
	document.getElementById(objeto).style.overflow="hidden";
}

function checkBox(objeto) {
	valor = document.getElementById(objeto).checked;
	if(valor != true)
        {
        document.getElementById(objeto+'Img').src = "imagenes/true.png";
		document.getElementById(objeto).checked = true;
        }
    else
        {
        document.getElementById(objeto+'Img').src = "imagenes/false.png";

		document.getElementById(objeto).checked = false;
        }
}

function radioButton(objeto) {
	valor = document.getElementById(objeto).checked;
	if(valor != true)
        {
        document.getElementById(objeto+'Img').src = "imagenes/true.png";
		document.getElementById(objeto).checked = true;
        }
    else
        {
        document.getElementById(objeto+'Img').src = "imagenes/false.png";
		document.getElementById(objeto).checked = false;
        }
}
/////////////////////////////funciones de admision//////////////////////////////
var menuskin=0
var display_url=0

function showmenuie5()
{
var ie5menu = document.getElementById('ie5menu');
var rightedge=document.body.clientWidth-event.clientX;
	var bottomedge=document.body.clientHeight-event.clientY;
	if (document.getElementById('disparo_menu').value=='si')
	{
		if (rightedge<ie5menu.offsetWidth)
			ie5menu.style.left=document.body.scrollLeft+event.clientX-ie5menu.offsetWidth
		else
			ie5menu.style.left=document.body.scrollLeft+event.clientX
		if (bottomedge<ie5menu.offsetHeight)
			ie5menu.style.top=document.body.scrollTop+event.clientY-ie5menu.offsetHeight
		else
			ie5menu.style.top=document.body.scrollTop+event.clientY
			ie5menu.style.visibility="visible"
	}
	return false
}

function hidemenuie5(){
	var ie5menu = document.getElementById('ie5menu');
ie5menu.style.visibility="hidden"
}
function highlightie5(){
	var ie5menu = document.getElementById('ie5menu');
if (event.srcElement.className=="menuitems"){
event.srcElement.style.backgroundColor="highlight"
event.srcElement.style.color="white"
//if (display_url==1)
//window.status=event.srcElement.url
}
}
function lowlightie5(){
	var ie5menu = document.getElementById('ie5menu');
if (event.srcElement.className=="menuitems"){
event.srcElement.style.backgroundColor=""
event.srcElement.style.color="black"
window.status=''
}
}
function jumptoie5(){
	var ie5menu = document.getElementById('ie5menu');
if (event.srcElement.className=="menuitems"){
//if (event.srcElement.getAttribute("target")!=null)
//window.open(event.srcElement.url,event.srcElement.getAttribute("target"))
//else
//window.location=event.srcElement.url
}
}
////////////////////////////funcion que valida una fecha en javascript
function esDigito(sChr)
{
	var sCod = sChr.charCodeAt(0);
	return ((sCod > 47) && (sCod < 58));
}
function valSep(oTxt)
{
	var bOk = false;
	bOk = bOk || ((oTxt.charAt(2) == "-") && (oTxt.charAt(5) == "-"));
	bOk = bOk || ((oTxt.charAt(2) == "/") && (oTxt.charAt(5) == "/"));
	return bOk;
}
function finMes(oTxt)
{
	var nMes = parseInt(oTxt.substr(3, 2), 10);
	var nRes = 0;
	switch (nMes)
	{
		case 1: nRes = 31; break;
		case 2: nRes = 29; break;
		case 3: nRes = 31; break;
		case 4: nRes = 30; break;
		case 5: nRes = 31; break;
		case 6: nRes = 30; break;
		case 7: nRes = 31; break;
		case 8: nRes = 31; break;
		case 9: nRes = 30; break;
		case 10: nRes = 31; break;
		case 11: nRes = 30; break;
		case 12: nRes = 31; break;
	}
	return nRes;
}
function valDia(oTxt)
{
	var bOk = false;
	var nDia = parseInt(oTxt.substr(0, 2), 10);
	bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
	return bOk;
}
function valMes(oTxt)
{
	var bOk = false;
	var nMes = parseInt(oTxt.substr(3, 2), 10);
	bOk = bOk || ((nMes >= 1) && (nMes <= 12));
	return bOk;
}
function valAno(oTxt)
{
	var bOk = true;
	var nAno = oTxt.substr(6);
	bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
	if (bOk)
	{
		for (var i = 0; i < nAno.length; i++)
		{
			bOk = bOk && esDigito(nAno.charAt(i));
		}
	}
	return bOk;
}
function valFecha(oTxt)
{
	var bOk = true;
	if (oTxt.value != ""){	
	bOk = bOk && (valAno(oTxt));
	bOk = bOk && (valMes(oTxt));
	bOk = bOk && (valDia(oTxt));
	bOk = bOk && (valSep(oTxt));
	/*if (!bOk)
	{
		alert("Fecha inv�lida");
	//	oTxt.value = "";
	//	oTxt.focus();
	}*/
	return bOk;	
	}
}
//compara si fec0 es > a fec1, los parametros son objetos
function fechaMayorOIgualQue(fec0, fec1)
{ 
    var bRes = false; 
    var sDia0 = fec0.value.substr(0, 2); 
    var sMes0 = fec0.value.substr(3, 2); 
    var sAno0 = fec0.value.substr(6, 4); 
    var sDia1 = fec1.value.substr(0, 2); 
    var sMes1 = fec1.value.substr(3, 2); 
    var sAno1 = fec1.value.substr(6, 4); 
    if (sAno0 > sAno1) bRes = true; 
    else { 
     if (sAno0 == sAno1){ 
      if (sMes0 > sMes1) bRes = true; 
      else { 
       if (sMes0 == sMes1) 
        if (sDia0 >= sDia1) bRes = true; 
      } 
     } 
    } 
    return bRes; 
} 
//compara si fec0 > fec1, los parametros on los valores de la fecha
function fechaMayorOIgualQueValor(fec0, fec1)
{ 
    var bRes = false; 
    var sDia0 = fec0.substr(0, 2); 
    var sMes0 = fec0.substr(3, 2); 
    var sAno0 = fec0.substr(6, 4); 
    var sDia1 = fec1.substr(0, 2); 
    var sMes1 = fec1.substr(3, 2); 
    var sAno1 = fec1.substr(6, 4); 
    if (sAno0 > sAno1) bRes = true; 
    else { 
     if (sAno0 == sAno1){ 
      if (sMes0 > sMes1) bRes = true; 
      else { 
       if (sMes0 == sMes1) 
        if (sDia0 > sDia1) bRes = true; 
      } 
     } 
    } 
    return bRes; 
} 
function obtenerFechaActual()
{
    var mydate=new Date();
	var year=mydate.getYear();
	if (year < 1000)
		year+=1900;
	var day=mydate.getDay();
	var month=mydate.getMonth()+1;
	if (month<10)
		month="0"+month;
	var daym=mydate.getDate();
	if (daym<10)
		daym="0"+daym;
    return daym+"/"+month+"/"+year;
}
//////////////////////////funcion que controla que no se coloque un a�o mayor al actual
function controlarFechas(fecha)
{
    var today = new Date();
    //var day   = today.getDate();
    //var month = today.getMonth();
    var year  = today.getYear();
	if (year < 2000) year = year + 1900;
	if (year == fecha.value.substr(6, 4))
	    return true;
    else
	    return false;		
}
//////////////////////funcion para redondear
function redondea(sVal, nDec)
{
/*    var n = parseFloat(sVal);
    var s;
    n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
    s = String(n) + "." + String(Math.pow(10, nDec)).substr(1);
    s = s.substr(0, s.indexOf(".") + nDec + 1);
	
    return s;
	*/
	var original=parseFloat(sVal);
	var result=Math.round(sVal*100)/100 ;
	return result;
} 
//funcioens para pernder y apagar 
function prende(contenedor,colorPrende,colorApaga,tiempo1,tiempo2)
{
	document.getElementById(contenedor).style.color=colorPrende;
	setTimeout("apaga('"+contenedor+"','"+colorApaga+"','"+colorPrende+"',"+tiempo1+","+tiempo2+");",tiempo1*tiempo2);
}
function apaga(contenedor,colorApaga,colorPrende,tiempo1,tiempo2)
{
	document.getElementById(contenedor).style.color=colorApaga;
	setTimeout("prende('"+contenedor+"','"+colorPrende+"','"+colorApaga+"',"+tiempo1+","+tiempo2+");",tiempo1*tiempo2);
}
//para pasar archivos de javascript a php
function arreglojavascriptAPhp(Codigos)
{
	var selected = new Array(); 
	var cont = 0;
	for (var i = 0; i < Codigos.options.length; i++) 
	{
	    if (Codigos.options[i].selected) 
		{
			selected += '|' + Codigos.options[i].value;
			cont++;
        }			
	}
	return selected;
}
//para mover el titulo de la brra de herramienta
var txt="      Sistema de Encuestas         -";
var espera=200;
var refresco=null;
function rotulo_title() {
        document.title=txt;
        txt=txt.substring(1,txt.length)+txt.charAt(0);
        refresco=setTimeout("rotulo_title()",espera);}
rotulo_title();
//////////////////////////////////////////////////funcion que sirve para hacer parpadear un campo////////////////////////////
function parpadear(input,color1,color2)
{//alert(document.getElementById('"+input+"'));
	//document.getElementById(input).style.background = "background-image:url(../imagenes/cama_ocupada.gif);"
	document.getElementById(input).style.background=color1;
	
	setTimeout("parpadearMas('"+input+"','"+color2+"','"+color1+"')",100);
}
function parpadearMas(input,color1,color2)
{
	//document.getElementById(input).style.background = "background-image:url(../imagenes/cama_reservada.gif);"
	document.getElementById(input).style.background=color1;
	
	setTimeout("parpadear('"+input+"','"+color2+"','"+color1+"')",100);
} 
////////////////////////////////////////////funcion que salta de tag a tag//////////////////////////////////////////////////
function saltarTag(e,t)
{
    var k=null;
    (e.keyCode) ? k=e.keyCode : k=e.which;
    if(k==13) 
        t.focus();
}
function saltarTagAux()
{
	document.forms[0].submit();
	return false;
}
///////////////////////////////////marca el siguiente campo cuando salta el tag y le pone color al input actual y vuelve al anterior al estado original
function marcarSiguienteCampo(e,siguiente,anterior,evento1,evento2)
{
    var k=null;
    (e.keyCode) ? k=e.keyCode : k=e.which;
    if(k==13) 
    {	
        siguiente.focus();
	//saltarTag(e,siguiente);
	anterior.style.background=evento2;
	siguiente.style.background=evento1;
    }	
}
////////////////////////////////////////////funcion que valida la hora
//esta funcion chequea en el momento que está introduciendo los datos y advierte si está mal
function CheckTime(str)
{
    hora=str;
    if (hora=='') {return 1;}
   // if (hora.length>5) {alert("Introdujo una cadena mayor a 8 caracteres");}
    if (hora.length!=5) {alert("Introducir HH:MM");} 
    a=hora.charAt(0); //<=2
    b=hora.charAt(1); //<4
    c=hora.charAt(2); //:
    d=hora.charAt(3); //<=5
   //e=hora.charAt(5) //:
   //f=hora.charAt(6) //<=5
   //if (f>5) {alert("El valor que introdujo en los segundos no corresponde");return}
    if ((a==2 && b>3) || (a>2))     
        return 0;            
    else 
    {
        if (d>5)        
            return 0;        
        else        
            if (c!=':') 
                return 0; 
            else 
                return 1;        
    }   
}
function fechasValida(caja)

{ 
   if (caja == '00/00/0000') return 1;
   if (caja)

   {  

      borrar = caja;

      if ((caja.substr(2,1) == "/") && (caja.substr(5,1) == "/"))

      {      

         for (i=0; i<10; i++)

             {  

            if (((caja.substr(i,1)<"0") || (caja.substr(i,1)>"9")) && (i != 2) && (i != 5))

                        {

               borrar = '';

               break;  

                        }  

         }

             if (borrar)

             { 

                a = caja.substr(6,4);

                    m = caja.substr(3,2);

                    d = caja.substr(0,2);

                    if((a < 1900) || (a > 2050) || (m < 1) || (m > 12) || (d < 1) || (d > 31))

                       borrar = '';

                    else

                    {

                       if((a%4 != 0) && (m == 2) && (d > 28))      

                          borrar = ''; // Año no viciesto y es febrero y el dia es mayor a 28

                           else 

                           {

                          if ((((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)))

                                 borrar = '';                                            

                           }  // else

                    } // fin else

         } // if (error)

      } // if ((caja.substr(2,1) == "/") && (caja.substr(5,1) == "/"))                                          

          else

             borrar = '';

          if (borrar == '')

             return 0;
          else
             return 1;

   } // if (caja)   

} // FUNCION
//funcion que chequea el estado del chekbox
function cambiarEstadoCheck(id)
{
    if ( 0 == document.getElementById(id).value )
	{
	    document.getElementById(id).value = 1;
	}
	else
	{
	    document.getElementById(id).value = 0;
	}
}
//saltar tabular campos 
function tabularCampoEnter(e,t)
{
var k=null;
(e.keyCode) ? k=e.keyCode : k=e.which;
if(k==13) (!t) ? tabularCampoEnterFuncion() : t.focus();
}
function tabularCampoEnterFuncion()
{
document.forms[0].submit();
return true;
}

