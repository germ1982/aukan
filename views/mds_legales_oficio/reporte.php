<html>

<body>
	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
		<div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
			<h4 style="margin: 0; font-weight: bold;">REPORTE REQUERIMIENTO</h4>
			<p><span> </span></p>
			<hr style="margin: 0 0 10px 0">
		</div>

		<table>
			<tr style="background-color: #dddddd;">
				<th class="titulo" valign="top" colspan="4">
					<h5>DATOS DE REQUERIMIENTO #<?= $oficio->idlegalesoficio ?>: </h5>
				</th>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Emisor órgano superior: </b><span><?= $oficio->emisor->descripcion ?></span></td>
				<td valign="top" colspan="2"><b>Localidad: </b><span><?= $oficio->donde_tramita ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Entidad requirente: </b><span><?= $oficio->lugar_libramiento ?></span></td>
				<td valign="top" colspan="2"><b>Responsable de la entidad requirente: </b><span><?= $oficio->doctor_a_cargo ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Fecha recepción: </b><span><?= $oficio->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_recepcion))) :  null ?></span></td>
				<td valign="top" colspan="2"><b>Fecha requerimiento: </b><span><?= $oficio->fecha_oficio ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_oficio))) :  null ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Carátula: </b><span><?= $oficio->caratulaModel ? $oficio->caratulaModel->caratula : ''  ?></span></td>
				<td valign="top" colspan="2"><b>DNI de personas vinculadas: </b><span><?= $oficio->dni_legajo_vinculado ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Plazo (días): </b><span><?= $oficio->tiempo_respuesta ?></span></td>
				<td valign="top" colspan="2"><b>Fecha vencimiento: </b><span><?= $oficio->fecha_plazo ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_plazo))) :  null  ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Número expediente: </b><span><?= $oficio->caratulaModel ? $oficio->caratulaModel->numero_expediente : '' ?></span></td>
				<td valign="top" colspan="2"><b>Caso: </b><span><?= $oficio->caratulaModel ? $oficio->caratulaModel->caso : '' ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Año: </b><span><?= $oficio->caratulaModel ? $oficio->caratulaModel->anio_expediente : '' ?></span></td>
				<td valign="top" colspan="2"><b>Número trámite / cédula / oficio: </b><span><?= $oficio->tramite_simple ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Motivo de solicitud: </b><span><?= $oficio->motivo_solicitud ?></span></td>
				<td valign="top" colspan="2"><b>Providencia: </b><span><?= $oficio->providencia ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="2"><b>Es primer requerimiento: </b><span><?= $oficio->primer_oficio == 1 ? 'Si' : 'No' ?></span></td>
				<td valign="top" colspan="2"><b>Tipo de requerimiento: </b><span><?= $oficio->tipoOficio->descripcion ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="4"><b>Derivación a: </b><span><?= ($oficio->areaOficio) ? $oficio->areaOficio->descripcion : '' ?></span></td>
			</tr>
		</table>
		<p class="parrafo"><b>Observaciones: </b><span><?= $oficio->observaciones; ?></span></p>
		<table>
			<tr>
				<td valign="top" colspan="4"><b>Requerimiento creado por: </b><span><?= $listUsuarioCarga ?></span></td>
			</tr>
			<tr>
				<td valign="top" colspan="4"><b>Encargados/as de supervisar respuestas: </b>
					<span>
						<ul><?= $listSupervisores ?></ul>
					</span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="4"><b>Encargados/as de generar respuestas: </b>
					<span>
						<ul><?= $listReceptores ?></ul>
					</span>
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					<b>Posee adjunto requerimiento: </b><span><?= $poseeAdjuntosOficio ?></span>
				</td>
				<td valign="top" colspan="2">
					<b>Posee otros documentos: </b><span><?= $poseeAdjuntosOtros ?></span>
				</td>
			</tr>
		</table>
		<hr style="margin: 10px 0 10px 0">
	</div>
</body>

</html>