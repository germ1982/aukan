<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
		<div class="row" style="padding-top: 1%;">
			<div class="col-xs-offset-6 col-xs-6" style="text-align: right;">Neuquén, <?= $fechaHeader; ?></div>
		</div>
		<div class="row" style="text-align:center;padding-top: 2%;padding-bottom: 2%;font-size: 12pt;">
			<div class="col-xs-12">
				<b>Informe N° <?=$informe['idinforme']?></b><br>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Fecha: </b><span><?= date("d/m/Y", strtotime($informe['fecha'])) ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Creado por: </b><span><?= mb_strtoupper($informe->usuario0['apellido']); ?>, <?= mb_strtoupper($informe->usuario0['nombre']); ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Tipo de Informe: </b><span><?= $informe->tipo0['descripcion']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Organismo: </b><span><?= $informe->iddispositivo0->organismo['descripcion']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Dispositivo: </b><span><?= $informe->iddispositivo0['descripcion']; ?></span>
			</div>
		</div>
		<?php if ($compartidoCon) : ?>
			<div class="row">
				<div class="col-xs-12" style="padding-top: 2%">
					<b>Compartido con: </b><span><?= $compartidoCon; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($vistoPor) : ?>
			<div class="row">
				<div class="col-xs-12" style="padding-top: 2%">
					<b>Visto por: </b><span><?= $vistoPor; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Asunto: </b><span><?= $informe['asunto']; ?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="padding-top: 2%; text-align: justify;">
				<p><b>Detalle:</b></p>
				<div>
					<?= $informe['detalle']; ?>
				</div>
			</div>
		</div>
		<div class="row" style="display:<?= $informe->adjunto0 ? "block" : "none" ?>">
			<div class="col-xs-12" style="padding-top: 2%">
				<b>Adjunto: </b><span>El informe posee archivos adjuntos</span></span>
			</div>
		</div>
	</div>
</body>

</html>