<?php if (count($model) > 0) { ?>
  <TABLE BORDER style="width:100% ;text-align: center">
    <TR>
      <TD><b>Intervención</b></TD>
      <TD><b>Fecha</b></TD>
    </TR>
    <?php foreach ($model as $intervencion) {  ?>
      <TR>
        <TD><?= $intervencion['idintervencion']; ?></TD>
        <TD><?= $intervencion['fecha']; ?></TD>
      <?php } ?>
      </TR>
  </TABLE>
<?php } else { ?>
  No existen registros de intervenciones asociadas al agresor.
<?php } ?>