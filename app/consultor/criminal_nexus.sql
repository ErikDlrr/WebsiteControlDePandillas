-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 02:54:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `criminal_nexus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aliados`
--

CREATE TABLE `aliados` (
  `id_Pandilla` int(11) NOT NULL,
  `id_Aliado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `antecedentes`
--

CREATE TABLE `antecedentes` (
  `id_antecedente` int(11) NOT NULL,
  `id_Pandilla` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `evento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `antecedentes`
--

INSERT INTO `antecedentes` (`id_antecedente`, `id_Pandilla`, `fecha`, `hora`, `evento`) VALUES
(1, 1, '2024-11-04', '22:52:07', 'Robo de vehiculo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delitos`
--

CREATE TABLE `delitos` (
  `id_Delito` int(11) NOT NULL,
  `nombre_delito` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `delitos`
--

INSERT INTO `delitos` (`id_Delito`, `nombre_delito`, `descripcion`) VALUES
(1, 'Robo de vehículo', 'El delito doloso, sustracción de un vehículo sin el consentimiento del propietario, con la intención de apropiarse de él o de venderlo a un tercero.'),
(2, 'Robo a persona', 'Apropiación ilegal de bienes pertenecientes a otra persona sin su consentimiento, utilizando la fuerza, violencia, intimidación o amenaza para obtener un beneficio personal'),
(3, 'Robo a comercio', 'Apoderamiento de una cosa ajena mueble, sin consentimiento de quien de facto puede darlo en el establecimiento comercial o de servicios.'),
(4, 'Homicidio', 'Privación de la vida a un ser humano sin aplicar ningún tipo de distinción.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faltas`
--

CREATE TABLE `faltas` (
  `id_Falta` int(11) NOT NULL,
  `nombre_Falta` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `faltas`
--

INSERT INTO `faltas` (`id_Falta`, `nombre_Falta`, `descripcion`) VALUES
(1, 'Daños', 'Detrimento, perjuicio, menoscabo, dolor o molestia causado a otro en su patrimonio o en su persona.'),
(2, 'Consumo de drogas', 'Uso de sustancias adictivas que afectan el cerebro y el comportamiento de una persona.'),
(3, 'Riñas', 'Contienda que incluye violencia física para dirimir un conflicto.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `integrante`
--

CREATE TABLE `integrante` (
  `id_Integrante` int(11) NOT NULL,
  `id_Pandilla` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `alias` varchar(50) DEFAULT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `dirección` varchar(100) DEFAULT NULL,
  `perfil_red_social` varchar(100) DEFAULT NULL,
  `foto` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `integrante`
--

INSERT INTO `integrante` (`id_Integrante`, `id_Pandilla`, `nombre`, `apellido_paterno`, `apellido_materno`, `alias`, `fecha_de_nacimiento`, `dirección`, `perfil_red_social`, `foto`) VALUES
(1, 1, 'Jonathan', 'Uresti', 'Sanchez', 'Jona', '2000-10-14', 'Condor 330, Pavon 3ra Secc, 78434 San Luis Potosí, S.L.P.', '@JonUrest_', NULL),
(2, 1, 'Raúl', 'Vargas', 'Montalvo', 'El lobo', '1996-11-06', 'Albatros 131, Hogares Populares Pavon, 78434 Soledad de Graciano Sánchez, S.L.P.', '@Lobo_Vargas', NULL),
(5, 10, 'Luis Alfonso', 'Rocha', 'Medina', 'El pocho', '2000-05-10', 'Allende 183-105, Aguilares, 78397 San Luis Potosí, S.L.P.', '@Pocho24', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `integrante_delitos`
--

CREATE TABLE `integrante_delitos` (
  `fecha` date DEFAULT NULL,
  `id_Integrante` int(11) NOT NULL,
  `id_Delitos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `integrante_delitos`
--

INSERT INTO `integrante_delitos` (`fecha`, `id_Integrante`, `id_Delitos`) VALUES
('2024-11-05', 1, 1),
('2022-08-20', 2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `integrante_faltas`
--

CREATE TABLE `integrante_faltas` (
  `fecha` date DEFAULT NULL,
  `id_Integrante` int(11) NOT NULL,
  `id_Faltas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `integrante_faltas`
--

INSERT INTO `integrante_faltas` (`fecha`, `id_Integrante`, `id_Faltas`) VALUES
('2024-11-05', 1, 1),
('2024-03-13', 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pandillas`
--

CREATE TABLE `pandillas` (
  `id_Pandilla` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `lider` varchar(100) DEFAULT NULL,
  `numero_aproximado_de_integrantes` int(11) DEFAULT NULL,
  `edades_aproximadas` varchar(50) DEFAULT NULL,
  `perfil_Red_Social` varchar(100) DEFAULT NULL,
  `edades` varchar(50) DEFAULT NULL,
  `Horario_de_reunion` varchar(50) DEFAULT NULL,
  `peligrosidad` enum('Baja','Media','Alta','') DEFAULT NULL,
  `direccion` int(11) DEFAULT NULL,
  `fecha_de_aniversario` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pandillas`
--

INSERT INTO `pandillas` (`id_Pandilla`, `nombre`, `descripcion`, `lider`, `numero_aproximado_de_integrantes`, `edades_aproximadas`, `perfil_Red_Social`, `edades`, `Horario_de_reunion`, `peligrosidad`, `direccion`, `fecha_de_aniversario`) VALUES
(1, 'Los Lobos Nocturno', 'Los Lobos Nocturnos son una pandilla urbana que opera en los barrios del centro, conocidos por sus actividades en la noche y sus misteriosas reuniones en lugares abandonados. Se destacan por su estilo enigmático, vistiendo siempre en tonos oscuros. Su símbolo es un lobo negro con ojos rojos.', 'Raúl Vargas', 35, '17 a 30', '@Lobos_Noc', '17, 20, 25, 26, 27, 30', '10:00 p.m.', 'Media', 1, '2018-05-16'),
(2, 'Los Hijos de la calle', 'Los Hijos de la calle son conocidos por imponer su presencia a través de carreras clandestinas, intimidación y robos. Sus miembros consideran las calles como su territorio, y no dudan en usar la violencia para mantener el control. Su símbolo es un rayo atravesando una calavera.', 'Juan Carlos Morales', 50, '18 a 35', '@HijosdC', '18, 21, 24, 26, 27, 30, 33, 35', '11:00 p.m.', 'Alta', 2, '2014-09-25'),
(3, 'Los dragones', 'operan como una familia cerrada y feroz. Se dedican a extorsionar a pequeños negocios y talleres mecánicos, exigiendo “cuotas de seguridad” a cambio de protección, son conocidos por su dureza y por su habilidad para intimidar y castigar a quienes no cooperan. Su símbolo es un dragón negro envuelto en cadenas.', 'Ernesto Bautista', 40, '20 a 40', '@DragonesCentro', '20, 21, 24, 27, 33, 35, 38, 40', '9:00 p.m.', 'Media', 3, '2015-04-10'),
(4, 'Los de la 18', 'Son conocidos por su violencia extrema. Se dedican al tráfico de drogas, robos y ataques a pandillas rivales. Los grafitis de la pandilla están en casi todas las paredes de la zona.', 'Carlos Martín Pérez', 80, '18 a 40', '@La18_', '18, 20, 22, 23, 25, 28, 31, 33, 34, 35, 37, 39, 40', '11:00 p.m.', 'Alta', 4, '2014-06-10'),
(5, 'La Mafia del Cerro', 'Sus actividades incluyen venta de drogas, tráfico de armas, extorsión y robo de vehículos. Son famosos por ser implacables con aquellos que intentan oponerse a ellos, y tienen alianzas con grupos de otras zonas para expandir sus operaciones. Su símbolo es un cuervo negro, que suelen pintar como advertencia en propiedades de quienes no pagan “cuotas de protección”.', 'Pedro Ortega', 70, '25 a 50', '@LaMafia', '25, 26, 27, 30, 34, 35, 40, 41, 43, 44, 46, 50', '12:00 p.m.', 'Alta', 5, '2012-09-11'),
(6, 'Las ratas', 'Esta pandilla es conocida por su violencia y crueldad. Se especializan en el contrabando de armas y personas.', 'Mauricio Mejía Chávez', 60, '22 a 47', '@LasRats', '22, 24, 25, 34, 36, 37, 41, 42, 45, 47', '6:00 p.m.', 'Alta', 6, '2019-12-06'),
(7, 'Los Malnacidos', 'Esta pandilla domina los barrios bajos, imponiendo su ley mediante robos, asaltos y peleas territoriales. Conocidos por ser extremadamente vengativos', 'Miguel Azúa', 65, '18 a 30', '@Malnacidosss', '18, 19, 20, 24, 26, 28, 30', '7:00 p.m.', 'Baja', 7, '2020-07-28'),
(8, 'La cuadra', 'Se dedican principalmente al control de puntos de venta de drogas y al cobro de \"protección\".', 'Edwin Rangel', 90, '21 a 50', '@LaCuadra_esc', '21, 24, 26, 27, 31, 33, 41, 46, 50', '9:00 p.m.', 'Baja', 8, '2014-05-30'),
(9, 'Los Salazar', 'Esta pandilla se dedica al contrabando de mercancías ilícitas y tráfico de sustancias. Son temidos por sus métodos violentos.', 'Emmanuel Rocha', 75, '23 a 45', '@LosSalazar_', '23, 25, 26, 29, 35, 36, 38, 41, 45', '5:00 p.m.', 'Media', 9, '2019-04-28'),
(10, 'La 21', 'Pandilla experta en robos y ataques nocturnos en zonas residenciales. Suelen ocultarse en los techos y azoteas de los edificios.', 'Adrián Martínez', 38, '25 a 35', '@La21', '25, 26, 28, 32, 33, 35', '1:00 a.m.', 'Media', 10, '2022-10-08'),
(11, 'Carroñeros', 'Esta pandilla se dedica al robo y reventa de autopartes en la región norte, controlando talleres clandestinos y organizando robos de autos en áreas específicas.', 'Víctor Sánchez', 30, '22 a 48', '@Carroñer0s_', '22, 23, 25, 27, 28, 30, 35, 40, 42, 48', '8:00 p.m.', 'Baja', 11, '2023-09-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pandillas_delitos`
--

CREATE TABLE `pandillas_delitos` (
  `fecha` date DEFAULT NULL,
  `id_Pandilla` int(11) NOT NULL,
  `id_Delitos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pandillas_delitos`
--

INSERT INTO `pandillas_delitos` (`fecha`, `id_Pandilla`, `id_Delitos`) VALUES
('2024-11-02', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pandillas_faltas`
--

CREATE TABLE `pandillas_faltas` (
  `fecha` date DEFAULT NULL,
  `id_Pandilla` int(11) NOT NULL,
  `id_Faltas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pandillas_faltas`
--

INSERT INTO `pandillas_faltas` (`fecha`, `id_Pandilla`, `id_Faltas`) VALUES
('2024-11-01', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_Reporte` int(11) NOT NULL,
  `id_Pandilla` int(11) DEFAULT NULL,
  `id_Integrante` int(11) DEFAULT NULL,
  `tipo_Reporte` varchar(50) DEFAULT NULL,
  `fecha_Generacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rivales`
--

CREATE TABLE `rivales` (
  `id_Pandilla` int(11) NOT NULL,
  `id_Rival` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_direccion` int(11) NOT NULL,
  `id_Pandilla` int(11) DEFAULT NULL,
  `latitud` decimal(65,28) DEFAULT NULL,
  `longitud` decimal(65,28) DEFAULT NULL,
  `punto_reunion` varchar(100) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero_de_calle` int(11) DEFAULT NULL,
  `entre_calles` varchar(100) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `zona` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_direccion`, `id_Pandilla`, `latitud`, `longitud`, `punto_reunion`, `calle`, `numero_de_calle`, `entre_calles`, `colonia`, `localidad`, `zona`, `municipio`) VALUES
(1, 1, 22.1510000000000000000000000000, 100.9850000000000000000000000000, 'Casa abandonada detrás de Walmart', 'Calle de Zaragoza', 540, 'Entre las calles de Independencia y Morelos', 'Centro Histórico', 'San Luis Potosí', 'Centro', 'San Luis Potosí'),
(2, 2, 22.1428380800000000000000000000, -100.9755269500000000000000000000, 'Entrada de la colonia', 'Prol Pedro Vallejo', 795, 'Entre las calles de Allende y Aldama', 'Barrio de San Miguelito\r\n', 'San Luis Potosí', 'Centro', 'San Luis Potosí'),
(3, 3, 22.1567610000000000000000000000, -100.9772390000000000000000000000, 'Callejón principal\r\n', 'Antonio Pl', 106, 'San Luis y Miguel Hidalgo', 'Centro Histórico', 'San Luis Potosí', 'Centro', 'San Luis Potosí'),
(4, 4, 22.1562459700000000000000000000, -100.8807899900000000000000000000, 'Cercanías del centro deportivo los Gómez', 'Hidalgo ', 125, 'Nuevo Laredo y Tenorio', 'Los Gómez Lado Oriente', 'Los Gómez', 'Este', 'San Luis Potosí'),
(5, 5, 22.1280206500000000000000000000, -100.8353457700000000000000000000, 'Tienda', 'Lázaro Cárdenas', 120, 'Magdalena Cedillo y Jassos Arbolitos', 'La cantera', 'San Nicolás de los Jassos', 'Este', 'San Luis Potosí'),
(6, 6, 22.1097839800000000000000000000, -100.9659163400000000000000000000, 'Almacén sin identificar', 'Montecarlo', 465, 'Monte alto y Real del Monte', 'Satelite Francisco I. Madero', 'San Luis Potosí', 'Sur', 'San Luis Potosí'),
(7, 7, 22.1021564800000000000000000000, -100.9626234100000000000000000000, 'Terreno baldío', 'C. Cerro Verde', 230, 'Sierra de San Miguelito y Tercera de Cerro Verde', 'San Juan de Guadalupe', 'San Luis Potosí', 'Sur', 'San Luis Potosí'),
(8, 8, 22.1116907300000000000000000000, -101.0738086000000000000000000000, 'Bar clandestino', 'Manuel Ávila Camacho', 35, 'C. Sebastián Bravo y C. Juárez', 'Escalerillas', 'Escalerillas', 'Oeste', 'San Luis Potosí'),
(9, 9, 22.1590816900000000000000000000, -101.0521141200000000000000000000, 'Posible casa del líder', 'Agamenon', 265, 'Arcadia y Calisto', 'Villa Magna', 'San Luis Potosí', 'Oeste', 'San Luis Potosí'),
(10, 10, 22.2041986500000000000000000000, -101.0039377800000000000000000000, 'Detrás de la calle principal', 'Cerro del Obispo', 104, 'Camino antiguo a Maravillas y Cerro Blanco', 'Fraccionamiento el Rosario', 'San Luis Potosí', 'Norte', 'San Luis Potosí'),
(11, 11, 22.2139471300000000000000000000, -100.9766018900000000000000000000, 'Taller mecánico', 'Afrodita', 17, 'Hércules y Minerva', 'Los magueyes', 'San Luis Potosí', 'Norte', 'San Luis Potosí');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_Usuario` int(11) NOT NULL,
  `nombre_Usuario` varchar(50) DEFAULT NULL,
  `tipo_Usuario` varchar(50) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Puesto` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aliados`
--
ALTER TABLE `aliados`
  ADD PRIMARY KEY (`id_Pandilla`,`id_Aliado`),
  ADD KEY `id_Aliado` (`id_Aliado`);

--
-- Indices de la tabla `antecedentes`
--
ALTER TABLE `antecedentes`
  ADD PRIMARY KEY (`id_antecedente`),
  ADD KEY `id_Pandilla` (`id_Pandilla`);

--
-- Indices de la tabla `delitos`
--
ALTER TABLE `delitos`
  ADD PRIMARY KEY (`id_Delito`);

--
-- Indices de la tabla `faltas`
--
ALTER TABLE `faltas`
  ADD PRIMARY KEY (`id_Falta`);

--
-- Indices de la tabla `integrante`
--
ALTER TABLE `integrante`
  ADD PRIMARY KEY (`id_Integrante`),
  ADD KEY `id_Pandilla` (`id_Pandilla`);

--
-- Indices de la tabla `integrante_delitos`
--
ALTER TABLE `integrante_delitos`
  ADD PRIMARY KEY (`id_Integrante`,`id_Delitos`),
  ADD KEY `id_Delitos` (`id_Delitos`);

--
-- Indices de la tabla `integrante_faltas`
--
ALTER TABLE `integrante_faltas`
  ADD PRIMARY KEY (`id_Integrante`,`id_Faltas`),
  ADD KEY `id_Faltas` (`id_Faltas`);

--
-- Indices de la tabla `pandillas`
--
ALTER TABLE `pandillas`
  ADD PRIMARY KEY (`id_Pandilla`),
  ADD KEY `dirección` (`direccion`);

--
-- Indices de la tabla `pandillas_delitos`
--
ALTER TABLE `pandillas_delitos`
  ADD PRIMARY KEY (`id_Pandilla`,`id_Delitos`),
  ADD KEY `id_Delitos` (`id_Delitos`);

--
-- Indices de la tabla `pandillas_faltas`
--
ALTER TABLE `pandillas_faltas`
  ADD PRIMARY KEY (`id_Pandilla`,`id_Faltas`),
  ADD KEY `id_Faltas` (`id_Faltas`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_Reporte`),
  ADD KEY `id_Pandilla` (`id_Pandilla`),
  ADD KEY `id_Integrante` (`id_Integrante`);

--
-- Indices de la tabla `rivales`
--
ALTER TABLE `rivales`
  ADD PRIMARY KEY (`id_Pandilla`,`id_Rival`),
  ADD KEY `id_Rival` (`id_Rival`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_Pandilla` (`id_Pandilla`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_Usuario`),
  ADD UNIQUE KEY `nombre_Usuario` (`nombre_Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `delitos`
--
ALTER TABLE `delitos`
  MODIFY `id_Delito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `faltas`
--
ALTER TABLE `faltas`
  MODIFY `id_Falta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `integrante`
--
ALTER TABLE `integrante`
  MODIFY `id_Integrante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pandillas`
--
ALTER TABLE `pandillas`
  MODIFY `id_Pandilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aliados`
--
ALTER TABLE `aliados`
  ADD CONSTRAINT `aliados_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`),
  ADD CONSTRAINT `aliados_ibfk_2` FOREIGN KEY (`id_Aliado`) REFERENCES `pandillas` (`id_Pandilla`);

--
-- Filtros para la tabla `antecedentes`
--
ALTER TABLE `antecedentes`
  ADD CONSTRAINT `antecedentes_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`);

--
-- Filtros para la tabla `integrante`
--
ALTER TABLE `integrante`
  ADD CONSTRAINT `integrante_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`);

--
-- Filtros para la tabla `integrante_delitos`
--
ALTER TABLE `integrante_delitos`
  ADD CONSTRAINT `integrante_delitos_ibfk_1` FOREIGN KEY (`id_Integrante`) REFERENCES `integrante` (`id_Integrante`),
  ADD CONSTRAINT `integrante_delitos_ibfk_2` FOREIGN KEY (`id_Delitos`) REFERENCES `delitos` (`id_Delito`);

--
-- Filtros para la tabla `integrante_faltas`
--
ALTER TABLE `integrante_faltas`
  ADD CONSTRAINT `integrante_faltas_ibfk_1` FOREIGN KEY (`id_Integrante`) REFERENCES `integrante` (`id_Integrante`),
  ADD CONSTRAINT `integrante_faltas_ibfk_2` FOREIGN KEY (`id_Faltas`) REFERENCES `faltas` (`id_Falta`);

--
-- Filtros para la tabla `pandillas`
--
ALTER TABLE `pandillas`
  ADD CONSTRAINT `pandillas_ibfk_1` FOREIGN KEY (`direccion`) REFERENCES `ubicacion` (`id_direccion`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pandillas_delitos`
--
ALTER TABLE `pandillas_delitos`
  ADD CONSTRAINT `pandillas_delitos_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`),
  ADD CONSTRAINT `pandillas_delitos_ibfk_2` FOREIGN KEY (`id_Delitos`) REFERENCES `delitos` (`id_Delito`);

--
-- Filtros para la tabla `pandillas_faltas`
--
ALTER TABLE `pandillas_faltas`
  ADD CONSTRAINT `pandillas_faltas_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`),
  ADD CONSTRAINT `pandillas_faltas_ibfk_2` FOREIGN KEY (`id_Faltas`) REFERENCES `faltas` (`id_Falta`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`),
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`id_Integrante`) REFERENCES `integrante` (`id_Integrante`);

--
-- Filtros para la tabla `rivales`
--
ALTER TABLE `rivales`
  ADD CONSTRAINT `rivales_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`),
  ADD CONSTRAINT `rivales_ibfk_2` FOREIGN KEY (`id_Rival`) REFERENCES `pandillas` (`id_Pandilla`);

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`id_Pandilla`) REFERENCES `pandillas` (`id_Pandilla`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
