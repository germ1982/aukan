<style>
    .lista-cumples {
        background-image: url("<?php echo $dirBase?>imagenes/cumples-back.png");
        background-repeat: no-repeat;
        width:280px; 
        float:left;
        height:20px;
        z-index:0;
        overflow:hidden;
        padding: 0.5em;
        position:relative;
    }

    .lista-cumples:hover {
        overflow:visible;
        z-index:100;
    }

    .lista-cumples:hover .inner { 
        z-index: 100;
    }

    .texto-cumples {
        color:#063;
        letter-spacing:1px;
        font-weight:bold;
        text-align:center;
        text-shadow:
           -1px -1px 0 #fff,  
            1px -1px 0 #fff,
            -1px 1px 0 #fff,
             1px 1px 0 #fff;

    }

    .inner { 
        position: absolute;
        background-color: rgba(255,255,255,0.75);
        box-shadow: 4px 4px 8px rgba(0,0,0,0.25);
        margin-top: 8px;
        width: 100%;
    }

    ul#lista-cumples {
        list-style: none;
        margin: 1em;
        padding: 0;
    }
</style>
<table width="100%" style='background-color:#F0F0F0; padding:2px; border:1px #A0A0A0 solid '>
<tr style="width:100%;height:23px">
    <td width='41' height="15"> <img src='<?php echo $dirBase?>imagenes/login_user.gif' alt='usuario'> 
    </td>
<td width='312'>
	Conectado como: <b><?php echo userName ?></b><br />
	IP Guardada: <b><?php echo userIP ?></b>
</td>

<td width='600'>
	[ <a target="_blank" href="<?php echo $dirBase."admin/";?>cambiarContrasena.php?userName=<?php echo userName; ?>" title="cambiar password para [<?php echo userName ?>]" >cambiar mi contrase&ntilde;a</a> ]
	<br />
	<?php
		if (userAdmin)
		{
			if ( $_SERVER['SCRIPT_NAME'] == "/avicenna/admin.php" )
			{
	?>
	[ <a href="episodio/basePorcentaje.php" title="salir de admin">volver</a> ]
	<?php
			}
			else
			{
	?>
<!--	[ <a href="<?php echo $dirBase?>admin.php" title="administraci&oacute;n" >admin</a> ]-->
	<?php } }?>
	[ <a href="<?php echo $dirBase?>login.php?act=logout" title="cerrar sesi&oacute;n [<?php echo userName ?>]" >cerrar sesi&oacute;n</a> ]
	[ <a onclick="window.open('<?php echo $dirBase;?>datos/seguridad_hc/justificar_entrada.php?idprofesional=<?php echo $idprofesional;?>&idepisodio=<?php echo $idepisodio; ?>&idpaciente=<?php echo $idpaciente;?>','','width=400,height=100,status=0,toolbar=0')" 
	title="Justificar Entrada" target='_blank' style="cursor:pointer;"><font color=red>Justificar Entrada</font></a> ]
</td>
<td><!-- <div class="lista-cumples">
    <div class="texto-cumples"><img src="<?php echo $dirBase?>imagenes/cake.gif" alt="Cumpleaños" /> Cumpleaños de Abril</div>
    <div class="inner">
        <ul id="lista-cumples">
            <li><strong>04/06</strong> GONZALEZ, MABEL RITA</li>
            <li><strong>06/06</strong> CASTAÑEDA, PAMELA ELIZABET</li>
            <li><strong>07/06</strong> PEÑA MEZA, BLANCA BEATRIZ</li>            
            <li><strong>10/06</strong> HINOJOSA, MARIA DEL CARMEN</li>
            <li><strong>16/06</strong> VILLEGAS CRISTINA DEL C.</li>
            <li><strong>16/06</strong> HERRERA, AMELIA BEATRIZ</li>
	    <li><strong>16/06</strong> FARIAS SALINAS, DEBORA CELESTE</li>
            <li><strong>16/06</strong> JELDREZ PAMELA</li>	    
            <li><strong>17/06</strong> HUAIQUIL PAILLALEF, DANIELA DEL ROSARIO</li>
            <li><strong>17/06</strong> CASTRO, ELIZABETH RAMONA</li>
            <li><strong>20/06</strong> FLORES ANZE DELINA</li>            
            <li><strong>21/06</strong> SAIBAL, KARINA ELIZABETH</li>
            <li><strong>25/06</strong> ROMERO, MARCELA ADRIANA</li>
	    <li><strong>27/06</strong> MORA, ELISA YANET</li>
	    <li><strong>28/06</strong> RODRÍGUEZ, DANIEL OSVALDO</li>
	    <li><strong>30/06</strong> GONZALEZ EGLISERIA</li>	
        </ul>
    </div>
</div> --></td>
</tr>
</table>
