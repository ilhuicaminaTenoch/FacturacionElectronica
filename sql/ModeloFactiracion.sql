CREATE TABLE `perfiles` (
`idPerfil` int(11) NOT NULL,
`nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
PRIMARY KEY (`idPerfil`) 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `usuarios` (
`idUsuario` int(11) NOT NULL,
`email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`contrasena` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`idPerfil` int(11) NULL DEFAULT NULL,
`idRol` int NULL,
PRIMARY KEY (`idUsuario`) ,
INDEX `FKidPerfil` (`idPerfil`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `roles` (
`idRol` int NOT NULL,
`rol` varchar(60) NULL,
PRIMARY KEY (`idRol`) 
);


ALTER TABLE `usuarios` ADD CONSTRAINT `FKidPerfil` FOREIGN KEY (`idPerfil`) REFERENCES `perfiles` (`idPerfil`);
ALTER TABLE `roles` ADD CONSTRAINT `fk_roles_usuarios_1` FOREIGN KEY (`idRol`) REFERENCES `usuarios` (`idRol`);

