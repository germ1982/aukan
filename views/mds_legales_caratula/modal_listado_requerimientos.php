<?php

use yii\helpers\Url;

?>

<input type="hidden" name="idlegalesoficio" id="idlegalesoficio" value="<?= $idlegalescaratula ?>">
<div class="row">
    <div class="col-md-12">
        <ul>
            <?php if (!empty($listadoRequerimientos)) :
                foreach ($listadoRequerimientos as $requerimiento) : ?>
                    <li>
                        <a href="<?= Url::base(); ?>/index.php?r=mds_legales_oficio%2Fview&idlegalesoficio=<?= $requerimiento['idlegalesoficio'] ?>" target="_blank" title="Ver" class="btn btn-link" style="padding: 0 5px 0 0;"><i class="fas fa-eye"></i></a>
                        <b>Requerimiento #<?= $requerimiento['idlegalesoficio'] ?></b>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <b>No existen requerimientos asociados a esta carátula.</b>
            <?php endif; ?>
        </ul>
    </div>
</div>
