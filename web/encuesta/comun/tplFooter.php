<?php
	if ($menuFlotante == true) {
?>
<script>
if (!document.layers)
document.write('<div id="divStayTopLeft" style="position:absolute;">')
</script>

<layer id="divStayTopLeft">

<!--EDIT BELOW CODE TO YOUR OWN MENU-->
<div id="accesoRapido" class="divsTexto" style="cursor:pointer; width:95px; font-size:10px; padding:2px; height:19px; overflow:hidden; padding-left:4px;" onClick="soloCerrar('accesoRapido','19px')">
	<span title="header=[Ayuda] body=[haga click aqu&iacute; para ampliar o contraer] delay=[1600] fade=[off]" onMouseOver="soloAbrir('accesoRapido','115px')">
	<b>Acceso R&aacute;pido</b></span><br />
	<a href="#top" class="menuRapidoLink">&raquo; Principio</a><br />
	<a href="#resumen" class="menuRapidoLink">&raquo; Resumen</a><br />
	<a href="#evolucion" class="menuRapidoLink">&raquo; Evoluci&oacute;n</a><br />
	<a href="#indicaciones" class="menuRapidoLink">&raquo; Indicaciones</a><br />	
	<a href="#controles" class="menuRapidoLink">&raquo; Controles</a><br /><br />
	<div class="menuRapidoLink" onclick="
		document.getElementById('mostrarEsteDiv1').style.display = 'inline';
		document.getElementById('mostrarEsteDiv2').style.display = 'inline';
		document.getElementById('mostrarEsteDiv3').style.display = 'inline';
		document.getElementById('mostrarEsteDiv4').style.display = 'inline';
		">Abrir Todos</div>
	<div class="menuRapidoLink" onclick="
		document.getElementById('mostrarEsteDiv1').style.display = 'none';
		document.getElementById('mostrarEsteDiv2').style.display = 'none';
		document.getElementById('mostrarEsteDiv3').style.display = 'none';
		document.getElementById('mostrarEsteDiv4').style.display = 'none';
		">Cerrar Todos</div>
</div>
<!--END OF EDIT-->

</layer>

<script type="text/javascript">

/*
Floating Menu script-  Roy Whittle (http://www.javascript-fx.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/

//Enter "frombottom" or "fromtop"
var verticalpos="fromtop"

if (!document.layers)
document.write('</div>')

function JSFX_FloatTopDiv()
{
	var startX = 0;
	startY = 5;
	var ns = (navigator.appName.indexOf("Netscape") != -1);
	var d = document;
	function ml(id)
	{
		var el=d.getElementById?d.getElementById(id):d.all?d.all[id]:d.layers[id];
		if(d.layers)el.style=el;
		el.sP=function(x,y){this.style.left=x;this.style.top=y;};
		el.x = startX;
		if (verticalpos=="fromtop")
		el.y = startY;
		else{
		el.y = ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight;
		el.y -= startY;
		}
		return el;
	}
	window.stayTopLeft=function()
	{
		if (verticalpos=="fromtop"){
		var pY = ns ? pageYOffset : document.body.scrollTop;
		ftlObj.y += (pY + startY - ftlObj.y)/8;
		}
		else{
		var pY = ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight;
		ftlObj.y += (pY - startY - ftlObj.y)/8;
		}
		ftlObj.sP(ftlObj.x, ftlObj.y);
		setTimeout("stayTopLeft()", 10);
	}
	ftlObj = ml("divStayTopLeft");
	stayTopLeft();
}
JSFX_FloatTopDiv();
</script>
<?php
	}
?>
</body>
</html>