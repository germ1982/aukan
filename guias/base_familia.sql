CREATE DATABASE  IF NOT EXISTS `familia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `familia`;



DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo` (
  `idarticulo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text DEFAULT NULL,
  `idtipo` int(11) NOT NULL,
  `idmarca` int(11) DEFAULT NULL,
  `modelo` varchar(30) DEFAULT NULL,
  `idrubro` int(11) NOT NULL,
  `id_unidad_medida` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idarticulo`),
  UNIQUE KEY `idx_articulo_combinacion_unica` (`idtipo`,`idmarca`,`modelo`,`idrubro`,`id_unidad_medida`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `capacitaciones`
--

DROP TABLE IF EXISTS `capacitaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `capacitaciones` (
  `idcapacitacion` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `referente` int(11) NOT NULL,
  `capacitadores` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `modalidad` int(11) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `cupo_maximo` int(11) NOT NULL,
  PRIMARY KEY (`idcapacitacion`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion` (
  `id_configuracion` int(11) NOT NULL AUTO_INCREMENT,
  `id_configuracion_tipo` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_configuracion`),
  UNIQUE KEY `UQ_id_tipo_descripcion` (`id_configuracion_tipo`,`descripcion`),
  KEY `idx_configuracion_id_configuracion_tipo` (`id_configuracion_tipo`),
  KEY `idx_configuracion_descripcion` (`descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=453 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuracion_tipo`
--

DROP TABLE IF EXISTS `configuracion_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_tipo` (
  `id_configuracion_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_configuracion_tipo`),
  UNIQUE KEY `UQ_descripcion_tipo` (`descripcion`),
  KEY `idx_configuracion_tipo_descripcion` (`descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edificio`
--

DROP TABLE IF EXISTS `edificio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edificio` (
  `idedificio` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_fija` varchar(100) NOT NULL,
  `descripcion_gestion` varchar(100) NOT NULL,
  `idlocalidad` int(11) NOT NULL,
  `direccion_calle` varchar(100) DEFAULT NULL,
  `direccion_altura` int(11) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `geolocalizacion` text DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`idedificio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edificio_acceso`
--

DROP TABLE IF EXISTS `edificio_acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edificio_acceso` (
  `id_edificio_acceso` int(11) NOT NULL,
  `idedificio` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id_edificio_acceso`),
  KEY `idedificio` (`idedificio`),
  CONSTRAINT `edificio_acceso_ibfk_1` FOREIGN KEY (`idedificio`) REFERENCES `edificio` (`idedificio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edificio_oficina`
--

DROP TABLE IF EXISTS `edificio_oficina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edificio_oficina` (
  `idoficina` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `idedificio` int(11) NOT NULL,
  `plano_ubicacion` varchar(45) DEFAULT NULL,
  `activo` int(11) DEFAULT 1,
  PRIMARY KEY (`idoficina`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `idempleado` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `iddispositivo` int(11) NOT NULL,
  `legajo` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `activo` tinyint(4) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `antiguedad_legal` int(11) DEFAULT NULL,
  `antiguedad_total` int(11) DEFAULT NULL,
  `ingreso_real` date DEFAULT NULL,
  `ingreso_administrativo` date DEFAULT NULL,
  `contratacion` int(11) DEFAULT NULL,
  `cuil` bigint(20) DEFAULT NULL,
  `funcion` int(11) DEFAULT NULL,
  `fichado` tinyint(4) DEFAULT NULL,
  `afiliacion` int(11) DEFAULT NULL,
  PRIMARY KEY (`idempleado`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_capacitacion`
--

DROP TABLE IF EXISTS `empleado_capacitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado_capacitacion` (
  `idcapacitacion` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL,
  `tipo_capacitacion` int(11) NOT NULL,
  `titulo_capacitacion` int(11) NOT NULL,
  `documentacion_capacitacion` varchar(100) NOT NULL,
  `idusuariocarga` int(11) NOT NULL,
  PRIMARY KEY (`idcapacitacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_documentacion`
--

DROP TABLE IF EXISTS `empleado_documentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado_documentacion` (
  `iddocumentacion` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL,
  `tipo_documentacion` int(11) NOT NULL,
  `documento` varchar(100) NOT NULL,
  PRIMARY KEY (`iddocumentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado_observacion`
--

DROP TABLE IF EXISTS `empleado_observacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado_observacion` (
  `idobservacion` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `observacion` text NOT NULL,
  `tipo_observacion` int(11) NOT NULL,
  `fecha_observacion` date NOT NULL,
  `id_usuario_registra` int(11) NOT NULL,
  PRIMARY KEY (`idobservacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inf_ips`
--

DROP TABLE IF EXISTS `inf_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inf_ips` (
  `idip` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `idoficina` int(11) DEFAULT NULL,
  `observacion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idip`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=513 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `informatica_web_empleados`
--

DROP TABLE IF EXISTS `informatica_web_empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informatica_web_empleados` (
  `idwebempleado` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`idwebempleado`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `informatica_web_eventos`
--

DROP TABLE IF EXISTS `informatica_web_eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informatica_web_eventos` (
  `idevento` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `titulo` varchar(1000) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fotos` varchar(1000) DEFAULT NULL,
  `iddispositivo` int(11) DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `tipo_evento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idevento`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `informatica_web_sectores`
--

DROP TABLE IF EXISTS `informatica_web_sectores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informatica_web_sectores` (
  `idsector` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fotos` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `orden` int(11) DEFAULT NULL,
  `alto_foto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idsector`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `idInventario` int(11) NOT NULL AUTO_INCREMENT,
  `idarticulo` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `iddispositivo` int(11) DEFAULT NULL,
  `idempleado` int(11) DEFAULT NULL,
  `idestado` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idInventario`),
  UNIQUE KEY `matricula_UNIQUE` (`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `localidades`
--

DROP TABLE IF EXISTS `localidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `localidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_provincia` int(11) DEFAULT NULL,
  `localidad` varchar(100) NOT NULL,
  `codigo_postal` varchar(45) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3630 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_plataforma`
--

DROP TABLE IF EXISTS `log_plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_plataforma` (
  `idlog` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `modulo` int(11) DEFAULT NULL,
  `accion` int(11) DEFAULT NULL,
  `idregistro` int(11) DEFAULT NULL,
  PRIMARY KEY (`idlog`)
) ENGINE=InnoDB AUTO_INCREMENT=624 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `type` varchar(10) NOT NULL,
  `icon` varchar(60) NOT NULL,
  `link` varchar(30) NOT NULL,
  `padre` int(11) NOT NULL DEFAULT 0,
  `activo` int(11) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL,
  `link_yii` varchar(100) NOT NULL DEFAULT '',
  `icon_yii` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organismo`
--

DROP TABLE IF EXISTS `organismo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organismo` (
  `idorganismo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200) NOT NULL,
  `padre` int(11) DEFAULT NULL,
  `nivel` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `abreviatura` varchar(100) NOT NULL,
  PRIMARY KEY (`idorganismo`),
  KEY `FK_organismo_padre` (`padre`),
  CONSTRAINT `FK_organismo_padre` FOREIGN KEY (`padre`) REFERENCES `organismo` (`idorganismo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=363 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organismo_dispositivo`
--

DROP TABLE IF EXISTS `organismo_dispositivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organismo_dispositivo` (
  `iddispositivo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `idorganismo` int(11) NOT NULL,
  `es_oficial` tinyint(4) NOT NULL DEFAULT 0,
  `es_organismo` tinyint(4) NOT NULL DEFAULT 0,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `direccion` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `idcapaitem` int(10) unsigned NOT NULL DEFAULT 1,
  `telefono` varchar(100) DEFAULT NULL,
  `idoficina` int(11) NOT NULL,
  PRIMARY KEY (`iddispositivo`),
  KEY `fk_dispositivo_organismo` (`idorganismo`),
  CONSTRAINT `fk_dispositivo_organismo` FOREIGN KEY (`idorganismo`) REFERENCES `organismo` (`idorganismo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE `organismo_decreto` (
  `iddecreto` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL, -- Ej: "Decreto 123/26 - Estructura Ministerio de Salud"
  `periodo_inicio` date NOT NULL,
  `periodo_final` date DEFAULT NULL,
  `periodo_prorroga` date DEFAULT NULL,
  PRIMARY KEY (`iddecreto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `organismo_org-dec` (
  `idorganismo` int(11) NOT NULL,
  `iddecreto` int(11) NOT NULL,
  PRIMARY KEY (`idorganismo`, `iddecreto`),
  CONSTRAINT `fk_org_dec_organismo` FOREIGN KEY (`idorganismo`) REFERENCES `organismo` (`idorganismo`) ON DELETE CASCADE,
  CONSTRAINT `fk_org_dec_decreto` FOREIGN KEY (`iddecreto`) REFERENCES `organismo_decreto` (`iddecreto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas` (
  `idpersona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `documento` int(10) unsigned NOT NULL,
  `documento_tipo` int(10) unsigned NOT NULL,
  `nacionalidad` int(10) unsigned NOT NULL,
  `genero` int(10) unsigned NOT NULL,
  `fecha_nacimiento` date NOT NULL DEFAULT '1900-01-01',
  `fecha_fallecimiento` date DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `padre` int(10) unsigned DEFAULT NULL,
  `madre` int(11) DEFAULT NULL,
  `conviviente` tinyint(1) NOT NULL DEFAULT 0,
  `domicilio` varchar(100) DEFAULT NULL,
  `domicilio_calle` varchar(255) DEFAULT NULL,
  `domicilio_numero` varchar(45) DEFAULT NULL,
  `idlocalidad` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpersona`),
  UNIQUE KEY `Unique_documento` (`documento`),
  KEY `FK_sds_com_persona_documento_tipo` (`documento_tipo`),
  KEY `FK_sds_com_persona_nacionalidad` (`nacionalidad`),
  KEY `FK_sds_com_persona_genero` (`genero`),
  KEY `FK_sds_com_persona_persona` (`padre`),
  KEY `FK_sds_com_persona_localidad_idx` (`idlocalidad`),
  KEY `Index_dni` (`documento`),
  KEY `Index_nombre` (`nombre`),
  KEY `Index_apellido` (`apellido`)
) ENGINE=InnoDB AUTO_INCREMENT=81759 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personas_no_homologadas`
--

DROP TABLE IF EXISTS `personas_no_homologadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas_no_homologadas` (
  `idpersona_no_homologada` int(11) NOT NULL AUTO_INCREMENT,
  `documento` varchar(45) DEFAULT NULL,
  `documento_tipo` int(11) DEFAULT NULL,
  `nacionalidad` int(11) DEFAULT NULL,
  `genero` int(11) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idpersona_no_homologada`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_familia_legajo`
--

DROP TABLE IF EXISTS `registro_familia_legajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_familia_legajo` (
  `num_legajo` varchar(25) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `archivo_adjunto` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `tipo_legajo` int(11) DEFAULT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_familia_legajo_archivo`
--

DROP TABLE IF EXISTS `registro_familia_legajo_archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_familia_legajo_archivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_legajo` int(11) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_legajo` (`id_legajo`),
  CONSTRAINT `registro_familia_legajo_archivo_ibfk_1` FOREIGN KEY (`id_legajo`) REFERENCES `registro_familia_legajo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_recepcion`
--

DROP TABLE IF EXISTS `registro_recepcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_recepcion` (
  `id_registro_recepcion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `dni` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `acceso` int(11) DEFAULT NULL COMMENT 'Lugar por donde se realiza la recepcion',
  `id_dispositivo_derivacion` int(11) DEFAULT NULL,
  `id_responsable_derivacion` int(11) DEFAULT NULL,
  `id_tipo_recepcion` int(11) DEFAULT NULL COMMENT 'telefonica o precencial',
  `observacion` text DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_registro_recepcion`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sds_com_localidad`
--

DROP TABLE IF EXISTS `sds_com_localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sds_com_localidad` (
  `idlocalidad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `codigo_postal` varchar(8) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `idprovincia` int(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`idlocalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=94015124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_deposito_egreso`
--

DROP TABLE IF EXISTS `stock_deposito_egreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_deposito_egreso` (
  `idegreso` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `idpersona_solicitante` int(11) NOT NULL,
  `idempleado_autorizacion` int(11) NOT NULL,
  `idempleado_despacha` int(11) NOT NULL,
  `idpersona_recibe` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `idusuario_carga` int(11) DEFAULT NULL,
  `idusuario_edicion` int(11) DEFAULT NULL,
  `id_dispositivo_destino` int(11) DEFAULT NULL,
  PRIMARY KEY (`idegreso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_deposito_egreso_detalle`
--

DROP TABLE IF EXISTS `stock_deposito_egreso_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_deposito_egreso_detalle` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idegreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_deposito_ingreso`
--

DROP TABLE IF EXISTS `stock_deposito_ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_deposito_ingreso` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `idorigen` int(11) NOT NULL,
  `origen_referencia` varchar(100) DEFAULT NULL,
  `idempleado_recepcion` int(11) NOT NULL,
  `idusuario_carga` int(11) NOT NULL,
  `observacion` varchar(45) DEFAULT NULL,
  `idusuario_edicion` int(11) DEFAULT NULL,
  PRIMARY KEY (`idingreso`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_deposito_ingreso_detalle`
--

DROP TABLE IF EXISTS `stock_deposito_ingreso_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_deposito_ingreso_detalle` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_informatica_egreso`
--

DROP TABLE IF EXISTS `stock_informatica_egreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_informatica_egreso` (
  `idegreso` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `idpersona_solicitante` int(11) NOT NULL,
  `idempleado_autorizacion` int(11) NOT NULL,
  `idempleado_despacha` int(11) NOT NULL,
  `idpersona_recibe` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `idusuario_carga` int(11) DEFAULT NULL,
  `idusuario_edicion` int(11) DEFAULT NULL,
  `id_dispositivo_destino` int(11) DEFAULT NULL,
  PRIMARY KEY (`idegreso`)
) ENGINE=InnoDB AUTO_INCREMENT=223 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_informatica_egreso_detalle`
--

DROP TABLE IF EXISTS `stock_informatica_egreso_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_informatica_egreso_detalle` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idegreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_informatica_ingreso`
--

DROP TABLE IF EXISTS `stock_informatica_ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_informatica_ingreso` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `idorigen` int(11) NOT NULL,
  `origen_referencia` varchar(100) DEFAULT NULL,
  `idempleado_recepcion` int(11) NOT NULL,
  `idusuario_carga` int(11) NOT NULL,
  `observacion` varchar(45) DEFAULT NULL,
  `idusuario_edicion` int(11) DEFAULT NULL,
  PRIMARY KEY (`idingreso`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_informatica_ingreso_detalle`
--

DROP TABLE IF EXISTS `stock_informatica_ingreso_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_informatica_ingreso_detalle` (
  `iddetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_chat`
--

DROP TABLE IF EXISTS `user_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk-user_chat-sender_id` (`sender_id`),
  KEY `fk-user_chat-receiver_id` (`receiver_id`),
  CONSTRAINT `fk-user_chat-receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-user_chat-sender_id` FOREIGN KEY (`sender_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_asignacion_perfil`
--

DROP TABLE IF EXISTS `usuario_asignacion_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_asignacion_perfil` (
  `idusuario` int(11) NOT NULL,
  `idperfil` int(11) NOT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idusuario`,`idperfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_perfil_permiso`
--

DROP TABLE IF EXISTS `usuario_perfil_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_perfil_permiso` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil` int(11) NOT NULL,
  `idtipopermiso` int(11) NOT NULL,
  `modulo` varchar(45) DEFAULT NULL,
  `item` varchar(45) DEFAULT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `password` varchar(100) NOT NULL,
  `activo` int(11) NOT NULL DEFAULT 1,
  `idpersona` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehiculo_oficial`
--

DROP TABLE IF EXISTS `vehiculo_oficial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculo_oficial` (
  `idvehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `dominio` varchar(20) NOT NULL,
  `poliza` varchar(50) DEFAULT NULL,
  `VTO` text DEFAULT NULL,
  `idmarca` int(11) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvehiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehiculo_oficial_movimiento`
--

DROP TABLE IF EXISTS `vehiculo_oficial_movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculo_oficial_movimiento` (
  `idmovimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idvehiculo` int(11) NOT NULL,
  `chofer` int(11) NOT NULL,
  `lugar_salida` varchar(255) DEFAULT NULL,
  `lugar_destino` varchar(255) DEFAULT NULL,
  `finalidad_viaje` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `kilometraje` int(11) NOT NULL,
  PRIMARY KEY (`idmovimiento`),
  KEY `chofer` (`chofer`),
  KEY `movim_vehi_oficial_ibfk_1` (`idvehiculo`),
  CONSTRAINT `vehiculo_oficial_movimiento_ibfk_1` FOREIGN KEY (`idvehiculo`) REFERENCES `vehiculo_oficial` (`idvehiculo`),
  CONSTRAINT `vehiculo_oficial_movimiento_ibfk_2` FOREIGN KEY (`chofer`) REFERENCES `empleado` (`idempleado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculos` (
  `idvehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) DEFAULT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `dominio` varchar(20) NOT NULL,
  `idmarca` int(11) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `vehiculo_oficial` tinyint(1) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvehiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `view_empleado`
--

DROP TABLE IF EXISTS `view_empleado`;
/*!50001 DROP VIEW IF EXISTS `view_empleado`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_empleado` AS SELECT 
 1 AS `idempleado`,
 1 AS `documento_identidad`,
 1 AS `legajo`,
 1 AS `apellido_nombre`,
 1 AS `foto`,
 1 AS `sector`,
 1 AS `iddispositivo`,
 1 AS `dispositivo`,
 1 AS `organismo`,
 1 AS `dispositivo_organismo`,
 1 AS `email`,
 1 AS `telefono`,
 1 AS `activo`,
 1 AS `categoria`,
 1 AS `antiguedad_legal`,
 1 AS `antiguedad_total`,
 1 AS `ingreso_real`,
 1 AS `ingreso_administrativo`,
 1 AS `contratacion`,
 1 AS `cuil`,
 1 AS `funcion`,
 1 AS `fichado`,
 1 AS `afiliacion`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_menus`
--

DROP TABLE IF EXISTS `view_menus`;
/*!50001 DROP VIEW IF EXISTS `view_menus`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_menus` AS SELECT 
 1 AS `id`,
 1 AS `padre`,
 1 AS `title`,
 1 AS `orden`,
 1 AS `activo`,
 1 AS `link`,
 1 AS `icon`,
 1 AS `idpadre`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_organismo_dispositivos`
--

DROP TABLE IF EXISTS `view_organismo_dispositivos`;
/*!50001 DROP VIEW IF EXISTS `view_organismo_dispositivos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_organismo_dispositivos` AS SELECT 
 1 AS `iddispositivo`,
 1 AS `descripcion`,
 1 AS `alias`,
 1 AS `idorganismo`,
 1 AS `es_oficial`,
 1 AS `es_organismo`,
 1 AS `activo`,
 1 AS `organismo`,
 1 AS `organismo_abreviatura`,
 1 AS `direccion`,
 1 AS `telefono`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_organismos`
--

DROP TABLE IF EXISTS `view_organismos`;
/*!50001 DROP VIEW IF EXISTS `view_organismos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_organismos` AS SELECT 
 1 AS `idorganismo`,
 1 AS `descripcion`,
 1 AS `abreviatura`,
 1 AS `idpadre`,
 1 AS `activo`,
 1 AS `padre_descripcion`,
 1 AS `padre_abreviatura`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_personas`
--

DROP TABLE IF EXISTS `view_personas`;
/*!50001 DROP VIEW IF EXISTS `view_personas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_personas` AS SELECT 
 1 AS `idpersona`,
 1 AS `documento_identidad`,
 1 AS `apellido_nombre`,
 1 AS `fecha_nacimiento`,
 1 AS `nacionalidad`,
 1 AS `genero`,
 1 AS `domicilio`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_stock_deposito_articulos_cantidades`
--

DROP TABLE IF EXISTS `view_stock_deposito_articulos_cantidades`;
/*!50001 DROP VIEW IF EXISTS `view_stock_deposito_articulos_cantidades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_stock_deposito_articulos_cantidades` AS SELECT 
 1 AS `idarticulo`,
 1 AS `rubro`,
 1 AS `descripcion`,
 1 AS `ingresado`,
 1 AS `entregado`,
 1 AS `disponible`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_stock_informatica_articulos_cantidades`
--

DROP TABLE IF EXISTS `view_stock_informatica_articulos_cantidades`;
/*!50001 DROP VIEW IF EXISTS `view_stock_informatica_articulos_cantidades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_stock_informatica_articulos_cantidades` AS SELECT 
 1 AS `idarticulo`,
 1 AS `rubro`,
 1 AS `descripcion`,
 1 AS `ingresado`,
 1 AS `entregado`,
 1 AS `disponible`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_usuarios`
--

DROP TABLE IF EXISTS `view_usuarios`;
/*!50001 DROP VIEW IF EXISTS `view_usuarios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_usuarios` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `documento`,
 1 AS `email`,
 1 AS `avatar`,
 1 AS `status`,
 1 AS `activo`,
 1 AS `idpersona`,
 1 AS `password`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_empleado`
--

/*!50001 DROP VIEW IF EXISTS `view_empleado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_empleado` AS (select `e`.`idempleado` AS `idempleado`,concat(`ctd`.`descripcion`,' ',`p`.`documento`) AS `documento_identidad`,`e`.`legajo` AS `legajo`,concat(`p`.`apellido`,' ',`p`.`nombre`) AS `apellido_nombre`,`e`.`foto` AS `foto`,`d`.`alias` AS `sector`,`e`.`iddispositivo` AS `iddispositivo`,`d`.`descripcion` AS `dispositivo`,`o`.`descripcion` AS `organismo`,concat(`d`.`descripcion`,' - ',`o`.`abreviatura`) AS `dispositivo_organismo`,`e`.`email` AS `email`,`e`.`telefono` AS `telefono`,if(`e`.`activo` = 1,'Si','No') AS `activo`,`ccat`.`descripcion` AS `categoria`,`e`.`antiguedad_legal` AS `antiguedad_legal`,`e`.`antiguedad_total` AS `antiguedad_total`,date_format(`e`.`ingreso_real`,'%d/%m/%Y') AS `ingreso_real`,date_format(`e`.`ingreso_administrativo`,'%d/%m/%Y') AS `ingreso_administrativo`,`ccontr`.`descripcion` AS `contratacion`,`e`.`cuil` AS `cuil`,`cfunc`.`descripcion` AS `funcion`,if(`e`.`fichado` = 1,'Si','No') AS `fichado`,`cafi`.`descripcion` AS `afiliacion` from ((((((((`empleado` `e` join `personas` `p` on(`e`.`idpersona` = `p`.`idpersona`)) join `configuracion` `ctd` on(`p`.`documento_tipo` = `ctd`.`id_configuracion`)) join `organismo_dispositivo` `d` on(`d`.`iddispositivo` = `e`.`iddispositivo`)) join `organismo` `o` on(`o`.`idorganismo` = `d`.`idorganismo`)) join `configuracion` `ccat` on(`ccat`.`id_configuracion` = `e`.`categoria`)) join `configuracion` `ccontr` on(`ccontr`.`id_configuracion` = `e`.`contratacion`)) join `configuracion` `cfunc` on(`cfunc`.`id_configuracion` = `e`.`funcion`)) join `configuracion` `cafi` on(`cafi`.`id_configuracion` = `e`.`afiliacion`)) order by `p`.`apellido`,`p`.`nombre`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_menus`
--

/*!50001 DROP VIEW IF EXISTS `view_menus`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_menus` AS (select `m`.`id` AS `id`,if(`m`.`padre` > 0,`p`.`title`,'No tiene, es padre') AS `padre`,`m`.`title` AS `title`,`m`.`orden` AS `orden`,if(`m`.`activo` = 1,'Si','No') AS `activo`,`m`.`link` AS `link`,`m`.`icon` AS `icon`,`m`.`padre` AS `idpadre` from (`menu` `m` left join `menu` `p` on(`m`.`padre` = `p`.`id`)) order by `p`.`title`,`m`.`title`,`m`.`orden`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_organismo_dispositivos`
--

/*!50001 DROP VIEW IF EXISTS `view_organismo_dispositivos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_organismo_dispositivos` AS (select `d`.`iddispositivo` AS `iddispositivo`,`d`.`descripcion` AS `descripcion`,`d`.`alias` AS `alias`,if(`d`.`idorganismo` > 0,`d`.`idorganismo`,0) AS `idorganismo`,if(`d`.`es_oficial` = 1,'Si','No') AS `es_oficial`,if(`d`.`es_organismo` = 1,'Si','No') AS `es_organismo`,if(`d`.`activo` = 1,'Si','No') AS `activo`,if(`d`.`idorganismo` > 0,`o`.`descripcion`,'0 no tiene') AS `organismo`,if(`d`.`idorganismo` > 0,`o`.`abreviatura`,'0 no tiene') AS `organismo_abreviatura`,`d`.`direccion` AS `direccion`,`d`.`telefono` AS `telefono` from (`organismo_dispositivo` `d` join `organismo` `o` on(`d`.`idorganismo` = `o`.`idorganismo`)) order by `o`.`descripcion`,`d`.`descripcion`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_organismos`
--

/*!50001 DROP VIEW IF EXISTS `view_organismos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_organismos` AS (select `o`.`idorganismo` AS `idorganismo`,`o`.`descripcion` AS `descripcion`,`o`.`abreviatura` AS `abreviatura`,if(`o`.`padre` > 0,`o`.`padre`,0) AS `idpadre`,if(`o`.`activo` = 1,'Si','No') AS `activo`,if(`o`.`padre` > 0,`p`.`descripcion`,'0 Raiz') AS `padre_descripcion`,if(`o`.`padre` > 0,`p`.`abreviatura`,'0 Raiz') AS `padre_abreviatura` from (`organismo` `o` left join `organismo` `p` on(`o`.`padre` = `p`.`idorganismo`)) order by `p`.`descripcion`,`o`.`descripcion`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_personas`
--

/*!50001 DROP VIEW IF EXISTS `view_personas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_personas` AS select `p`.`idpersona` AS `idpersona`,concat(`ctd`.`descripcion`,' ',`p`.`documento`) AS `documento_identidad`,concat(`p`.`apellido`,' ',`p`.`nombre`) AS `apellido_nombre`,date_format(`p`.`fecha_nacimiento`,'%d/%m/%Y') AS `fecha_nacimiento`,`cn`.`descripcion` AS `nacionalidad`,`cg`.`descripcion` AS `genero`,concat(`p`.`domicilio_calle`,' ',`p`.`domicilio_numero`) AS `domicilio` from (((`personas` `p` join `configuracion` `ctd` on(`p`.`documento_tipo` = `ctd`.`id_configuracion`)) join `configuracion` `cn` on(`p`.`nacionalidad` = `cn`.`id_configuracion`)) join `configuracion` `cg` on(`p`.`genero` = `cg`.`id_configuracion`)) order by `p`.`apellido`,`p`.`nombre` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_stock_deposito_articulos_cantidades`
--

/*!50001 DROP VIEW IF EXISTS `view_stock_deposito_articulos_cantidades`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`10.1.73.89` SQL SECURITY DEFINER */
/*!50001 VIEW `view_stock_deposito_articulos_cantidades` AS select `a`.`idarticulo` AS `idarticulo`,`cr`.`descripcion` AS `rubro`,concat(`ct`.`descripcion`,' ',`cm`.`descripcion`,' ',`a`.`modelo`,' ',`cum`.`descripcion`,' ',`a`.`descripcion`) AS `descripcion`,coalesce((select sum(`sdi`.`cantidad`) from `stock_deposito_ingreso_detalle` `sdi` where `sdi`.`idarticulo` = `a`.`idarticulo`),0) AS `ingresado`,coalesce((select sum(`sde`.`cantidad`) from `stock_deposito_egreso_detalle` `sde` where `sde`.`idarticulo` = `a`.`idarticulo`),0) AS `entregado`,coalesce((select sum(`sdi`.`cantidad`) from `stock_deposito_ingreso_detalle` `sdi` where `sdi`.`idarticulo` = `a`.`idarticulo`),0) - coalesce((select sum(`sde`.`cantidad`) from `stock_deposito_egreso_detalle` `sde` where `sde`.`idarticulo` = `a`.`idarticulo`),0) AS `disponible` from ((((`articulo` `a` join `configuracion` `ct` on(`ct`.`id_configuracion` = `a`.`idtipo`)) join `configuracion` `cm` on(`cm`.`id_configuracion` = `a`.`idmarca`)) join `configuracion` `cum` on(`cum`.`id_configuracion` = `a`.`id_unidad_medida`)) join `configuracion` `cr` on(`cr`.`id_configuracion` = `a`.`idrubro`)) where `a`.`activo` = 1 and `a`.`idrubro` = 116 order by `ct`.`descripcion`,`cm`.`descripcion`,`a`.`modelo`,`cum`.`descripcion`,`a`.`descripcion` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_stock_informatica_articulos_cantidades`
--

/*!50001 DROP VIEW IF EXISTS `view_stock_informatica_articulos_cantidades`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`10.1.73.89` SQL SECURITY DEFINER */
/*!50001 VIEW `view_stock_informatica_articulos_cantidades` AS select `a`.`idarticulo` AS `idarticulo`,`cr`.`descripcion` AS `rubro`,concat(`ct`.`descripcion`,' ',`cm`.`descripcion`,' ',`a`.`modelo`,' ',`cum`.`descripcion`,' ',`a`.`descripcion`) AS `descripcion`,coalesce((select sum(`sii`.`cantidad`) from `stock_informatica_ingreso_detalle` `sii` where `sii`.`idarticulo` = `a`.`idarticulo`),0) AS `ingresado`,coalesce((select sum(`sie`.`cantidad`) from `stock_informatica_egreso_detalle` `sie` where `sie`.`idarticulo` = `a`.`idarticulo`),0) AS `entregado`,coalesce((select sum(`sii`.`cantidad`) from `stock_informatica_ingreso_detalle` `sii` where `sii`.`idarticulo` = `a`.`idarticulo`),0) - coalesce((select sum(`sie`.`cantidad`) from `stock_informatica_egreso_detalle` `sie` where `sie`.`idarticulo` = `a`.`idarticulo`),0) AS `disponible` from ((((`articulo` `a` join `configuracion` `ct` on(`ct`.`id_configuracion` = `a`.`idtipo`)) join `configuracion` `cm` on(`cm`.`id_configuracion` = `a`.`idmarca`)) join `configuracion` `cum` on(`cum`.`id_configuracion` = `a`.`id_unidad_medida`)) join `configuracion` `cr` on(`cr`.`id_configuracion` = `a`.`idrubro`)) where `a`.`activo` = 1 and `a`.`idrubro` = 115 order by `ct`.`descripcion`,`cm`.`descripcion`,`a`.`modelo`,`cum`.`descripcion`,`a`.`descripcion` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_usuarios`
--

/*!50001 DROP VIEW IF EXISTS `view_usuarios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_usuarios` AS (select `u`.`id` AS `id`,concat(`p`.`nombre`,' ',`p`.`apellido`) AS `name`,`p`.`documento` AS `documento`,`u`.`email` AS `email`,`u`.`avatar` AS `avatar`,`u`.`status` AS `status`,if(`u`.`activo` = 1,'Si','No') AS `activo`,`u`.`idpersona` AS `idpersona`,`u`.`password` AS `password` from (`usuarios` `u` join `personas` `p` on(`u`.`idpersona` = `p`.`idpersona`)) order by `p`.`nombre`,`p`.`apellido`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-16 13:29:35
