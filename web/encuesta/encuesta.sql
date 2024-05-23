-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.28-0ubuntu0.18.04.4 - (Ubuntu)
-- SO del servidor:              Linux
-- HeidiSQL Versión:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para mdsyt
CREATE DATABASE IF NOT EXISTS `mdsyt` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mdsyt`;

-- Volcando estructura para tabla mdsyt.mds_encuesta
CREATE TABLE IF NOT EXISTS `mds_encuesta` (
  `id_encuesta` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_tipo_encuesta` int(10) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `completa` int(1) DEFAULT NULL,
  `baja_fecha` datetime DEFAULT NULL,
  `fecha_vinculacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_encuesta`),
  KEY `FK_encuesta_encuesta_tipo` (`id_tipo_encuesta`),
  CONSTRAINT `FK_encuesta_encuesta_tipo` FOREIGN KEY (`id_tipo_encuesta`) REFERENCES `mds_encuesta_tipo` (`id_tipo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla mdsyt.mds_encuesta: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `mds_encuesta` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_pregunta
CREATE TABLE IF NOT EXISTS `mds_encuesta_pregunta` (
  `id_pregunta` int(10) NOT NULL AUTO_INCREMENT,
  `id_seccion` int(10) DEFAULT '0',
  `pregunta` longtext,
  `name` longtext,
  `requerida` int(1) DEFAULT '0',
  `dependiente` int(10) DEFAULT '0',
  `orden` int(10) DEFAULT NULL,
  `baja_fecha` datetime DEFAULT NULL,
  `id_tipo_encuesta` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `FK__encuesta_secciones` (`id_seccion`),
  KEY `FK_encuesta_preguntas_encuesta_tipo` (`id_tipo_encuesta`),
  CONSTRAINT `FK__encuesta_secciones` FOREIGN KEY (`id_seccion`) REFERENCES `mds_encuesta_seccion` (`id_seccion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_encuesta_preguntas_encuesta_tipo` FOREIGN KEY (`id_tipo_encuesta`) REFERENCES `mds_encuesta_tipo` (`id_tipo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla mdsyt.mds_encuesta_pregunta: ~66 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_pregunta` DISABLE KEYS */;
INSERT INTO `mds_encuesta_pregunta` (`id_pregunta`, `id_seccion`, `pregunta`, `name`, `requerida`, `dependiente`, `orden`, `baja_fecha`, `id_tipo_encuesta`) VALUES
	(1, 1, 'SELECCIONE EL HOGAR QUE ESTÁ EVALUANDO', NULL, 0, 0, 1, NULL, 1),
	(2, 2, 'VALORACIÓN DE ELECTRICIDAD', NULL, 0, 0, 1, NULL, 1),
	(3, 2, 'CERTIF. DE SEGURIDAD E HIGIENE', NULL, 0, 0, 2, NULL, 1),
	(4, 2, 'INFRAESTRUCTURA', NULL, 0, 0, 3, NULL, 1),
	(5, 2, 'CLIMATIZACIÓN', NULL, 0, 0, 4, NULL, 1),
	(6, 2, 'M2 ESPACIOS MÍNIMOS', NULL, 0, 0, 5, NULL, 1),
	(7, 2, 'DORMITORIOS (SEGÚN SEXO Y CANTIDAD', NULL, 0, 0, 6, NULL, 1),
	(8, 2, 'DEPOSITO PARA COMESTIBLES', NULL, 0, 0, 7, NULL, 1),
	(9, 2, 'SANITARIOS PARA EL PERSONAL', NULL, 0, 0, 8, NULL, 1),
	(10, 2, 'DEPOSITO PARA ROPAS', NULL, 0, 0, 9, NULL, 1),
	(11, 2, 'ACCESO PARA NIÑOS/NIÑAS CON DISCAPACIDADES', NULL, 0, 0, 10, NULL, 1),
	(12, 2, 'ESPACIO PARA ACTIVIDADES FISICAS/PATIO', NULL, 0, 0, 11, NULL, 1),
	(13, 2, 'SANITARIOS PARA NIÑOS/AS', NULL, 0, 0, 12, NULL, 1),
	(14, 2, 'AREA DE COCINA', NULL, 0, 0, 13, NULL, 1),
	(15, 2, 'AREA COMUN PARA ACTIVADES RECREATIVAS', NULL, 0, 0, 14, NULL, 1),
	(16, 2, 'VEHICULO - ASIGNADO', NULL, 0, 0, 15, NULL, 1),
	(17, 3, 'MATAFUEGOS', NULL, 0, 0, 1, NULL, 1),
	(18, 3, 'SEÑALIZACION Y AVISOS DE PROTECCION', NULL, 0, 0, 2, NULL, 1),
	(19, 3, 'RUTAS DE EVACUACION SEÑALIZADAS', NULL, 0, 0, 3, NULL, 1),
	(20, 3, 'DETECTORES DE HUMO', NULL, 0, 0, 4, NULL, 1),
	(21, 3, 'OTROS', NULL, 0, 0, 5, NULL, 1),
	(22, 4, 'PLAN NUTRICIONAL ', NULL, 0, 0, 1, NULL, 1),
	(23, 4, 'CALIDAD - CONSERVACIÓN DE LOS ALIMENTOS', NULL, 0, 0, 2, NULL, 1),
	(24, 4, 'HIGIENE GENERAL DE LA COCINA', NULL, 0, 0, 3, NULL, 1),
	(25, 4, 'PARTICIPAN EN LA DECISIÓN DE COMPRAS DE INSUMOS', NULL, 0, 0, 4, NULL, 1),
	(26, 5, 'ESCOLARIZACION', NULL, 0, 0, 1, NULL, 1),
	(27, 5, 'PC C/ACCESO A INTERNET', NULL, 0, 0, 2, NULL, 1),
	(28, 5, 'APOYO EDUCATIVO', NULL, 0, 0, 3, NULL, 1),
	(29, 6, 'DIRECTOR', NULL, 0, 0, 1, NULL, 1),
	(30, 6, 'SUBDIRECTOR', NULL, 0, 0, 2, NULL, 1),
	(31, 6, 'PSICOLOGO', NULL, 0, 0, 3, NULL, 1),
	(32, 6, 'ASISTENTE SOCIAL', NULL, 0, 0, 4, NULL, 1),
	(33, 6, 'PERSONAL ADMINISTRATIVO', NULL, 0, 0, 5, NULL, 1),
	(34, 6, 'OPERADORES (EDUCADOR)', NULL, 0, 0, 6, NULL, 1),
	(35, 6, 'PROFESOR DE ENSEÑANZA ESCOLAR', NULL, 0, 0, 7, NULL, 1),
	(36, 6, 'MAESTRANZA', NULL, 0, 0, 8, NULL, 1),
	(37, 6, 'PERSONAL DE COCINA', NULL, 0, 0, 9, NULL, 1),
	(38, 6, 'REFERENTE DE SALUD', NULL, 0, 0, 10, NULL, 1),
	(39, 6, 'PERSONAL DE MANTENIMIENTO', NULL, 0, 0, 11, NULL, 1),
	(40, 7, 'CAPACITACIONES AL PERSONAL', NULL, 0, 0, 3, NULL, 1),
	(41, 7, 'SUPERVISION ANUAL DEL PERSONAL', NULL, 0, 0, 1, NULL, 1),
	(42, 6, 'TALLERISTAS DE DESARROLLO INTEGRAL', NULL, 0, 0, 12, NULL, 1),
	(43, 8, 'CAPACITACION', NULL, 0, 0, 1, NULL, 1),
	(44, 8, 'DESEMPEÑO', NULL, 0, 0, 2, NULL, 1),
	(45, 8, 'ANTECEDENTES JUDICIALES ( si son requeridos )', NULL, 0, 0, 3, NULL, 1),
	(46, 9, 'ATENCIÓN MÉDICA', NULL, 0, 0, 1, NULL, 1),
	(47, 9, 'MEDICACION', NULL, 0, 0, 2, NULL, 1),
	(48, 9, 'LIBRETA DE SALUD', NULL, 0, 0, 3, NULL, 1),
	(49, 9, 'TRATAMIENTO PSICOLOGICO', NULL, 0, 0, 4, NULL, 1),
	(50, 10, 'ACTIVIDADES / ARTISTICAS / DEPORTIVAS / CULTURALES', NULL, 0, 0, 1, NULL, 1),
	(51, 11, 'VESTIMENTA ESTACIONAL (INCLUYE CALZADO)', NULL, 0, 0, 1, NULL, 1),
	(52, 11, 'ROPA ESCOLAR', NULL, 0, 0, 2, NULL, 1),
	(53, 11, 'OBJETOS PERSONALES (ROPA/JUGUETES/MATERIAL DE ESTUDIO)', NULL, 0, 0, 3, NULL, 1),
	(54, 11, 'ESPACIO PROPIO', NULL, 0, 0, 4, NULL, 1),
	(55, 12, 'AGREGUE AQUÍ CONSIDERACIONES GENERALES', NULL, 0, 0, 1, NULL, 1),
	(56, 7, 'SUPERVISIÓN TÉCNICA', NULL, 0, 0, 2, NULL, 1),
	(57, 13, 'Lugar', NULL, 0, 0, 1, NULL, 2),
	(58, 13, 'CDI Alternativo', NULL, 0, 0, 2, NULL, 2),
	(59, 13, 'Fecha', NULL, 0, 0, 2, '2020-12-16 13:49:34', 2),
	(60, 13, 'Profesionales Intervinientes', NULL, 0, 0, 3, NULL, 2),
	(61, 13, 'Quién asiste a la entrevista', NULL, 0, 0, 4, NULL, 2),
	(62, 13, 'Expectativas de la Familia (sobre el niño y la institución):', NULL, 0, 0, 5, NULL, 2),
	(63, 14, 'Apellido', NULL, 0, 0, 1, NULL, 2),
	(64, 14, 'Nombre', NULL, 0, 0, 2, NULL, 2),
	(65, 14, 'Dni', NULL, 0, 0, 3, NULL, 2),
	(66, 14, 'Fecha de Nacimiento', NULL, 0, 0, 4, NULL, 2);
/*!40000 ALTER TABLE `mds_encuesta_pregunta` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_respuesta
CREATE TABLE IF NOT EXISTS `mds_encuesta_respuesta` (
  `id_respuesta` int(10) NOT NULL AUTO_INCREMENT,
  `id_pregunta` int(10) NOT NULL DEFAULT '0',
  `respuesta` varchar(255) NOT NULL DEFAULT '0',
  `tipo` varchar(50) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '0',
  `otro_campo` int(10) NOT NULL DEFAULT '0',
  `texto_previo_desde` varchar(255) NOT NULL DEFAULT '0',
  `radio_desde` int(1) NOT NULL DEFAULT '0',
  `radio_hasta` int(1) NOT NULL DEFAULT '0',
  `texto_posterior_hasta` varchar(255) NOT NULL DEFAULT '0',
  `imagen` varchar(255) NOT NULL DEFAULT '0',
  `orden` int(1) NOT NULL,
  `baja_fecha` datetime DEFAULT NULL,
  `valoracion` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_respuesta`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla mdsyt.mds_encuesta_respuesta: ~75 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_respuesta` DISABLE KEYS */;
INSERT INTO `mds_encuesta_respuesta` (`id_respuesta`, `id_pregunta`, `respuesta`, `tipo`, `name`, `value`, `otro_campo`, `texto_previo_desde`, `radio_desde`, `radio_hasta`, `texto_posterior_hasta`, `imagen`, `orden`, `baja_fecha`, `valoracion`) VALUES
	(1, 2, '0', 'radio_varios', 'pregunta_2', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(2, 3, '0', 'radio_varios', 'pregunta_3', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(3, 4, '0', 'radio_varios', 'pregunta_4', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(4, 1, 'HOGAR YAMPAI', 'radio', 'pregunta_4', 'hogar yampai', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(5, 1, 'HOGAR CASA DE ADMISIÓN', 'radio', 'pregunta_4', 'hogar casa de admisiÓn', 0, '0', 0, 0, '0', '0', 2, NULL, NULL),
	(6, 1, 'HOGAR AYENHUE', 'radio', 'pregunta_4', 'hogar ayenhue', 0, '0', 0, 0, '0', '0', 3, NULL, NULL),
	(7, 1, 'HOGAR DE ZAPALA', 'radio', 'pregunta_4', 'hogar de zapala', 0, '0', 0, 0, '0', '0', 4, NULL, NULL),
	(8, 1, 'HOGAR LOS BAJITOS', 'radio', 'pregunta_4', 'hogar los bajitos', 0, '0', 0, 0, '0', '0', 5, NULL, NULL),
	(9, 1, 'HOGAR AMANCAY', 'radio', 'pregunta_4', 'hogar amancay', 0, '0', 0, 0, '0', '0', 6, NULL, NULL),
	(10, 1, 'HOGAR MALEN', 'radio', 'pregunta_4', 'hogar malen', 0, '0', 0, 0, '0', '0', 7, NULL, NULL),
	(11, 1, 'HOGAR PROYECTO CONVIVIENDO', 'radio', 'pregunta_4', 'hogar proyecto conviviendo', 0, '0', 0, 0, '0', '0', 8, NULL, NULL),
	(12, 1, 'HOGAR CONVIVENCIA', 'radio', 'pregunta_4', 'hogar convivencia', 0, '0', 0, 0, '0', '0', 9, NULL, NULL),
	(13, 5, '0', 'radio_varios', 'pregunta_5', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(14, 6, '0', 'radio_varios', 'pregunta_6', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(15, 7, '0', 'radio_varios', 'pregunta_7', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(16, 8, '0', 'radio_varios', 'pregunta_8', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(17, 9, '0', 'radio_varios', 'pregunta_9', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(18, 10, '0', 'radio_varios', 'pregunta_10', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(19, 11, '0', 'radio_varios', 'pregunta_11', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(20, 12, '0', 'radio_varios', 'pregunta_12', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(21, 13, '0', 'radio_varios', 'pregunta_13', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(22, 14, '0', 'radio_varios', 'pregunta_14', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(23, 15, '0', 'radio_varios', 'pregunta_15', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(24, 16, '0', 'radio_varios', 'pregunta_16', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(25, 17, '0', 'radio_varios', 'pregunta_17', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(26, 18, '0', 'radio_varios', 'pregunta_18', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(27, 19, '0', 'radio_varios', 'pregunta_19', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(28, 20, '0', 'radio_varios', 'pregunta_20', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(29, 21, '0', 'radio_varios', 'pregunta_21', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(30, 22, '0', 'radio_varios', 'pregunta_22', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(31, 23, '0', 'radio_varios', 'pregunta_23', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(32, 24, '0', 'radio_varios', 'pregunta_24', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(33, 25, '0', 'radio_varios', 'pregunta_25', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(34, 26, '0', 'radio_varios', 'pregunta_26', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(35, 27, '0', 'radio_varios', 'pregunta_27', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(36, 28, '0', 'radio_varios', 'pregunta_28', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(37, 29, '0', 'radio_varios', 'pregunta_29', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(38, 30, '0', 'radio_varios', 'pregunta_30', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(39, 31, '0', 'radio_varios', 'pregunta_31', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(40, 32, '0', 'radio_varios', 'pregunta_32', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(41, 33, '0', 'radio_varios', 'pregunta_33', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(42, 34, '0', 'radio_varios', 'pregunta_34', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(43, 35, '0', 'radio_varios', 'pregunta_35', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(44, 36, '0', 'radio_varios', 'pregunta_36', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(45, 37, '0', 'radio_varios', 'pregunta_37', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(46, 38, '0', 'radio_varios', 'pregunta_38', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(47, 39, '0', 'radio_varios', 'pregunta_39', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(48, 40, '0', 'radio_varios', 'pregunta_40', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(49, 41, '0', 'radio_varios', 'pregunta_41', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(50, 42, '0', 'radio_varios', 'pregunta_42', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(51, 43, '0', 'radio_varios', 'pregunta_43', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(52, 44, '0', 'radio_varios', 'pregunta_44', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(53, 45, '0', 'radio_varios', 'pregunta_45', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(54, 46, '0', 'radio_varios', 'pregunta_46', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(55, 47, '0', 'radio_varios', 'pregunta_47', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(56, 48, '0', 'radio_varios', 'pregunta_48', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(57, 49, '0', 'radio_varios', 'pregunta_49', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(58, 50, '0', 'radio_varios', 'pregunta_50', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(59, 51, '0', 'radio_varios', 'pregunta_51', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(60, 52, '0', 'radio_varios', 'pregunta_52', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(61, 53, '0', 'radio_varios', 'pregunta_53', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(62, 54, '0', 'radio_varios', 'pregunta_54', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(63, 55, '', 'textarea', 'pregunta_55', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(64, 56, '0', 'radio_varios', 'pregunta_56', '0', 0, '-1 VALOR SI NO ESTÁ IMPLEMENTADO', -1, 10, 'VALOR MÁXIMO', '0', 1, NULL, NULL),
	(65, 57, '', 'input', 'pregunta_57', '0', 0, '0', 0, 0, '0', '0', 1, '2020-12-16 14:13:42', NULL),
	(66, 58, '', 'input', 'pregunta_58', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(67, 60, '', 'textarea', 'pregunta_60', '0', 0, '0', 0, 0, '0', '0', 0, NULL, NULL),
	(68, 61, '', 'textarea', 'pregunta_61', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(69, 62, '', 'textarea', 'pregunta_62', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(70, 63, '', 'input', 'pregunta_63', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(71, 64, '', 'input', 'pregunta_64', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(72, 65, '', 'input', 'pregunta_65', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(73, 66, '0', 'input', 'pregunta_66', '0', 0, '0', 0, 0, '0', '0', 1, NULL, NULL),
	(74, 57, 'Neuquen', 'checkbox', 'pregunta_57', 'neuquen', 0, '0', 0, 0, '0', '0', 0, NULL, NULL),
	(75, 57, 'Plottier', 'checkbox', 'pregunta_57', 'plottier', 0, '0', 0, 0, '0', '0', 2, NULL, NULL);
/*!40000 ALTER TABLE `mds_encuesta_respuesta` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_resultado
CREATE TABLE IF NOT EXISTS `mds_encuesta_resultado` (
  `id_resultado` int(10) NOT NULL AUTO_INCREMENT,
  `id_encuesta` int(10) DEFAULT '0',
  `id_seccion` int(10) DEFAULT '0',
  `id_pregunta` int(10) DEFAULT '0',
  `id_respuesta` int(10) DEFAULT '0',
  `valor` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id_resultado`),
  KEY `FK__encuesta` (`id_encuesta`),
  KEY `FK__encuesta_preguntas` (`id_pregunta`),
  CONSTRAINT `FK__encuesta` FOREIGN KEY (`id_encuesta`) REFERENCES `mds_encuesta` (`id_encuesta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__encuesta_preguntas` FOREIGN KEY (`id_pregunta`) REFERENCES `mds_encuesta_pregunta` (`id_pregunta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla mdsyt.mds_encuesta_resultado: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_resultado` DISABLE KEYS */;
/*!40000 ALTER TABLE `mds_encuesta_resultado` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_seccion
CREATE TABLE IF NOT EXISTS `mds_encuesta_seccion` (
  `id_seccion` int(10) NOT NULL AUTO_INCREMENT,
  `seccion` varchar(255) DEFAULT NULL,
  `explicacion` varchar(255) DEFAULT NULL,
  `orden` int(1) DEFAULT NULL,
  `baja_fecha` datetime DEFAULT NULL,
  `id_tipo_encuesta` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_seccion`),
  KEY `FK_encuesta_secciones_encuesta_tipo` (`id_tipo_encuesta`),
  CONSTRAINT `FK_encuesta_secciones_encuesta_tipo` FOREIGN KEY (`id_tipo_encuesta`) REFERENCES `mds_encuesta_tipo` (`id_tipo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla mdsyt.mds_encuesta_seccion: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_seccion` DISABLE KEYS */;
INSERT INTO `mds_encuesta_seccion` (`id_seccion`, `seccion`, `explicacion`, `orden`, `baja_fecha`, `id_tipo_encuesta`) VALUES
	(1, 'INFORMACIÓN GENERAL', '', 1, NULL, 1),
	(2, 'ALOJAMIENTO', '', 2, NULL, 1),
	(3, 'SEGURIDAD', '', 3, NULL, 1),
	(4, 'ALIMENTACIÓN', '', 4, NULL, 1),
	(5, 'EDUCACIÓN', '', 5, NULL, 1),
	(6, 'PERSONAL', '', 6, NULL, 1),
	(7, 'SOBRE EL PERSONAL', '', 7, NULL, 1),
	(8, 'EDUCADORES', '', 8, NULL, 1),
	(9, 'SALUD', '', 9, NULL, 1),
	(10, 'RECREACIÓN', '', 10, NULL, 1),
	(11, 'PERTENENCIAS', '', 11, NULL, 1),
	(12, 'CONSIDERACIONES GENERALES', '', 12, NULL, 1),
	(13, 'DATOS ENTREVISTA', '', 1, NULL, 2),
	(14, 'INFANTIL', ' Entrevista Psicosocial', 2, NULL, 2);
/*!40000 ALTER TABLE `mds_encuesta_seccion` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_tipo
CREATE TABLE IF NOT EXISTS `mds_encuesta_tipo` (
  `id_tipo` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `activo` int(1) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `baja_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla mdsyt.mds_encuesta_tipo: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_tipo` DISABLE KEYS */;
INSERT INTO `mds_encuesta_tipo` (`id_tipo`, `nombre`, `descripcion`, `activo`, `id_user`, `baja_fecha`) VALUES
	(1, 'Matriz Ley 2955', 'Evaluación de Hogares', 1, 10340, NULL),
	(2, 'Admisión CDI', 'Ficha de Admisión', 1, 3, NULL);
/*!40000 ALTER TABLE `mds_encuesta_tipo` ENABLE KEYS */;

-- Volcando estructura para tabla mdsyt.mds_encuesta_usuario_tipo
CREATE TABLE IF NOT EXISTS `mds_encuesta_usuario_tipo` (
  `id_usuario_tipo` int(10) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(10) NOT NULL DEFAULT '0',
  `id_usuario` int(10) DEFAULT NULL,
  `respuesta_multiple` int(1) DEFAULT NULL COMMENT '0-respuesta unica, 1-respuesta multiple. Para saber si puede responder mas de una encuesta del mismo tipo',
  `baja_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla mdsyt.mds_encuesta_usuario_tipo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `mds_encuesta_usuario_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `mds_encuesta_usuario_tipo` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
