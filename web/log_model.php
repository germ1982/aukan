<?php


Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_docente', $model->idpersona, $model_com_persona->getAttributes());

Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_docente', $model->idpersona, $model_com_persona->getAttributes());

$model = $this->findModel($id);
if ($model->delete() > 0) {
    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_inscripcion', $id, $model->getAttributes());
}
