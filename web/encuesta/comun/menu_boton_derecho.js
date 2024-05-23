function Asigna(id,propiedad,valor){
if(document.layers) eval('document.'+id+'.'+propiedad+'='+valor);
if(document.all) eval(id+'.style.'+propiedad+'='+valor);
if(!document.all&&document.getElementById)
eval('document.getElementById("'+id+'").style.'+propiedad+'='+valor);
}
var poX=0, poY=0, poD=0, poA=0, poaX=0, poaY=0;
function iniciaMenu(){
if(document.layers){
window.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE);
window.onMouseDown=pulsa;
window.onMouseMove=movimiento;
}
else{
document.oncontextmenu=pulsa;
document.onmousemove=movimiento;
document.onclick=ApagaMenu;
}
}
function ApagaMenu(){
Asigna('Menu','visibility','"hidden"');
}
function pulsa(pulsacion){
if((!document.all&&(pulsacion.which!=1))||(document.all&&(event.button!=1))){
posicionDelRaton(pulsacion);
Asigna('Menu','left',poX);
Asigna('Menu','top',poY);
Asigna('Menu','visibility','"visible"');
return false;
}
else{
if(document.layers){
if((poaX<poX||poaX>poX+document.Menu.document.width)||(poaY<poY||poaY>poY+document.Menu.document.height))
ApagaMenu();
}
return true;
}
}
function movimiento(movimiento2){
if(document.all){
poaX=event.x+document.body.scrollLeft;
poaY=event.y+document.body.scrollTop;
}
else{
poaX=movimiento2.pageX;
poaY=movimiento2.pageY;
}
}
function posicionDelRaton(e){
if(document.all){
poX=event.x+document.body.scrollLeft;
poY=event.y+document.body.scrollTop;
poD=document.body.offsetWidth-poX;
poA=document.body.offsetHeight-poY;
if(poD>0&&poD<Menu.offsetWidth)
poX=document.body.scrollLeft+event.x-Menu.offsetWidth;
else
poX=document.body.scrollLeft+event.x;
if(poA>0&&poA<Menu.offsetHeight)
poY=document.body.scrollTop+event.y-Menu.offsetHeight;
else
poY=document.body.scrollTop+event.clientY;
}
else{
poX=e.pageX;
poY=e.pageY;
poD=window.innerWidth-poX;
poA=window.innerHeight-poY;
if(document.getElementById){
if(poD>0&&poD<document.getElementById('Menu').offsetWidth)
poX=e.pageX-document.getElementById('Menu').offsetWidth;
else
poX=e.pageX;
if(poA>0&&poA<document.getElementById('Menu').offsetHeight)
poY=e.pageY-document.getElementById('Menu').offsetHeight;
else
poY=e.pageY;
}
else{
if(poD>0&&poD<document.Menu.document.width)
poX=e.pageX-document.Menu.document.width;
else
poX=e.pageX;
if(poA>0&&poA<document.Menu.document.height)
poY=e.pageY-document.Menu.document.height;
else
poY=e.pageY;
}
}
}
