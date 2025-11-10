--
-- Usuario debug
--
DROP USER IF EXISTS `dani`@`localhost`;
CREATE USER IF NOT EXISTS `dani`@`localhost` IDENTIFIED BY 'Dani123!';
GRANT ALL PRIVILEGES ON `Prueba_Tecnica`.* TO `dani`@`localhost` WITH GRANT OPTION;

-- Base de datos: `Prueba_Tecnica`
--
DROP DATABASE IF EXISTS `Prueba_Tecnica`;
CREATE DATABASE IF NOT EXISTS `Prueba_Tecnica` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `Prueba_Tecnica`;

--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE `usuario` (
    `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
    `correo_usuario` varchar(100) NOT NULL UNIQUE,
    `contrasena_usuario` varchar(255) NOT NULL,
    `nombre_usuario` varchar(100) NOT NULL,
    `apellidos_usuario` varchar(100) NOT NULL,
    PRIMARY KEY (`id_usuario`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `usuario` ( `correo_usuario`, `contrasena_usuario`, `nombre_usuario`, `apellidos_usuario`) VALUES
('dani@test.com', 'dani', 'dani', 'Martnez'),
('maria@test.com', 'maria', 'maria', 'López');

--
-- Estructura de tabla para la tabla `tareas`
--
CREATE TABLE `tarea` (
    `id_tarea` int(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` int(11) NOT NULL,
    `nombre_tarea` varchar(200) NOT NULL,
    `descripcion_tarea` varchar(200) NOT NULL,
    `fecha_inicio_tarea` datetime NOT NULL,
    `fecha_fin_tarea` datetime NOT NULL,
    `completada_tarea` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id_tarea`),
    FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `tarea` (`id_usuario`, `nombre_tarea`, `descripcion_tarea`, `fecha_inicio_tarea`, `fecha_fin_tarea`, `completada_tarea`) values
(1, 'Tarea 1', 'Descripción de la tarea 1', '2024-06-01 10:00:00', '2024-06-05 18:00:00', 0),
(1, 'Tarea 2', 'Descripción de la tarea 2', '2024-06-02 09:00:00', '2024-06-06 17:00:00', 1),
(2, 'Tarea 3', 'Descripción de la tarea 3', '2024-06-03 11:00:00', '2024-06-07 16:00:00', 0);