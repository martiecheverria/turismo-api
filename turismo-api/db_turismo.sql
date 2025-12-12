
CREATE TABLE `regiones` (
  `id_region` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `regiones` (`id_region`, `nombre`, `descripcion`, `imagen_url`) VALUES
(1, 'Patagonia', 'El extremo sur del mundo. Tierra de glaciares, montañas imponentes, lagos cristalinos y una fauna única.', 'https://images.unsplash.com/photo-1585502019443-4379e378c772?q=80&w=2070'),
(2, 'Noroeste (NOA)', 'Una región de cultura ancestral, paisajes desérticos, montañas multicolores y valles fértiles. El corazón histórico del país.', 'https://images.unsplash.com/photo-1620251141368-23956da70589?q=80&w=2071'),
(3, 'Litoral', 'Zona de grandes ríos, selva exuberante, tierra colorada y las majestuosas Cataratas del Iguazú. Un paraíso de biodiversidad.', 'https://images.unsplash.com/photo-1629814197933-78b1086289b5?q=80&w=2070'),
(4, 'Cuyo', 'La tierra del sol y el buen vino. Hogar de las bodegas más famosas y del imponente Aconcagua, el pico más alto de América.', 'https://images.unsplash.com/photo-1629529983944-a5e29a38f288?q=80&w=2070');


CREATE TABLE `destinos` (
  `id_destino` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen_url` varchar(500) DEFAULT NULL,
  `id_region_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `destinos` (`id_destino`, `nombre`, `descripcion`, `imagen_url`, `id_region_fk`) VALUES
(1, 'San Carlos de Bariloche', 'Famosa por su arquitectura de estilo alpino suizo y su chocolate. Se encuentra junto al lago Nahuel Huapi, en la Patagonia.', 'images/bariloche.png', 1),
(2, 'Glaciar Perito Moreno', 'Una impresionante masa de hielo ubicada en el departamento Lago Argentino de la provincia de Santa Cruz, en la región de la Patagonia.', 'images/GlaciarPeritoMoreno.png', 1),
(3, 'Purmamarca', 'Conocida por el Cerro de los Siete Colores, una colina multicolor que se encuentra en la Quebrada de Humahuaca.', 'images/purmamarca.png', 2),
(4, 'Cataratas del Iguazú', 'Un conjunto de cataratas que se localizan sobre el río Iguazú, en el límite entre la provincia de Misiones y el estado brasileño de Paraná.', 'images/cataratasiguazu.png', 3),
(5, 'Valles Calchaquíes', 'El turismo en los Valles Calchaquíes encuentra en Angastaco un destino singular, donde la naturaleza y las tradiciones se conservan intactas.', 'images/vallescalchaquies.png', 2),
(6, 'Esteros Del Iberá', 'Son uno de los humedales más grandes de América del Sur, ubicados en el centro-norte de la provincia de Corrientes, Argentina. Con una extensión de aproximadamente 12.000 km.', 'images/esterosdelibera.png', 3),
(7, 'Cerro Aconcagua', 'Es la montaña más alta de América, con una altitud de 6962 metros sobre el nivel del mar. Se encuentra ubicado en el departamento Las Heras, en la provincia de Mendoza.', 'images/aconcagua.png', 4),
(8, 'Valle de la Luna', 'Declarado Patrimonio de la Humanidad por la Unesco en 2000, es el único lugar del mundo donde se puede observar de forma completa y ordenada todo el período Triásico.', 'images/valledelaluna.png', 4);


CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `usuarios` (`id_usuario`, `email`, `password`, `rol`) VALUES
(1, 'webadmin', '$2y$10$E9.pD3bLg2oV/Oa.Z.fHn.wE.n2n.kR6i.c.p/Oa.Z.fHn.wE.kR6', 'admin');


ALTER TABLE `regiones` ADD PRIMARY KEY (`id_region`);
ALTER TABLE `destinos` ADD PRIMARY KEY (`id_destino`), ADD KEY `fk_destinos_regiones` (`id_region_fk`);
ALTER TABLE `usuarios` ADD PRIMARY KEY (`id_usuario`), ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `regiones` MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `destinos` MODIFY `id_destino` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `usuarios` MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `destinos` ADD CONSTRAINT `fk_destinos_regiones` FOREIGN KEY (`id_region_fk`) REFERENCES `regiones` (`id_region`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;