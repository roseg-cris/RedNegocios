CREATE DATABASE IF NOT EXISTS rednegocios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rednegocios;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS comentarios, likes_producto, recuperacion_password_admin,recuperacion_password,mensajes,conversaciones,favoritos,producto_imagenes,productos,subcategorias,categorias,horarios_negocio,negocios,administradores,usuarios;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE usuarios(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,rol ENUM('cliente','negocio') NOT NULL,
 estado ENUM('activo','inactivo','bloqueado') NOT NULL DEFAULT 'activo',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE administradores(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,estado ENUM('activo','inactivo','bloqueado') NOT NULL DEFAULT 'activo',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE categorias(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(100) NOT NULL UNIQUE,descripcion TEXT,
 estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo'
)ENGINE=InnoDB;

CREATE TABLE subcategorias(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,categoria_id INT UNSIGNED NOT NULL,nombre VARCHAR(100) NOT NULL,
 descripcion TEXT,estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
 UNIQUE(categoria_id,nombre),FOREIGN KEY(categoria_id) REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

CREATE TABLE negocios(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,usuario_id INT UNSIGNED NOT NULL UNIQUE,categoria_id INT UNSIGNED NOT NULL,
 nombre VARCHAR(150) NOT NULL,slug VARCHAR(180) NOT NULL UNIQUE,descripcion TEXT,logo VARCHAR(255),portada VARCHAR(255),
 direccion VARCHAR(255),telefono VARCHAR(30),whatsapp VARCHAR(30),email VARCHAR(150),
 estado ENUM('pendiente','activo','rechazado','suspendido') NOT NULL DEFAULT 'pendiente',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 FOREIGN KEY(categoria_id) REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

CREATE TABLE horarios_negocio(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,negocio_id INT UNSIGNED NOT NULL,
 dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
 hora_apertura TIME NULL,hora_cierre TIME NULL,UNIQUE(negocio_id,dia_semana),
 FOREIGN KEY(negocio_id) REFERENCES negocios(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE productos(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,negocio_id INT UNSIGNED NOT NULL,categoria_id INT UNSIGNED NOT NULL,
 subcategoria_id INT UNSIGNED NOT NULL,nombre VARCHAR(150) NOT NULL,descripcion TEXT,precio DECIMAL(10,2) NOT NULL,
 precio_oferta DECIMAL(10,2) NULL,stock INT NOT NULL DEFAULT 0,
 disponibilidad ENUM('disponible','agotado') NOT NULL DEFAULT 'disponible',
 es_oferta BOOLEAN NOT NULL DEFAULT FALSE,es_mas_vendido BOOLEAN NOT NULL DEFAULT FALSE,es_destacado BOOLEAN NOT NULL DEFAULT FALSE,
 total_comentarios INT UNSIGNED NOT NULL DEFAULT 0,
 total_likes INT UNSIGNED NOT NULL DEFAULT 0,
 total_favoritos INT UNSIGNED NOT NULL DEFAULT 0,
 CHECK(precio>=0),CHECK(precio_oferta IS NULL OR(precio_oferta>=0 AND precio_oferta<precio)),CHECK(stock>=0),
 FOREIGN KEY(negocio_id) REFERENCES negocios(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 FOREIGN KEY(categoria_id) REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 FOREIGN KEY(subcategoria_id) REFERENCES subcategorias(id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

CREATE TABLE producto_imagenes(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,producto_id INT UNSIGNED NOT NULL,ruta_imagen VARCHAR(255) NOT NULL,
 es_principal BOOLEAN NOT NULL DEFAULT FALSE,
 FOREIGN KEY(producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE favoritos(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,usuario_id INT UNSIGNED NOT NULL,producto_id INT UNSIGNED NOT NULL,
 UNIQUE(usuario_id,producto_id),
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE likes_producto(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,usuario_id INT UNSIGNED NOT NULL,producto_id INT UNSIGNED NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE(usuario_id,producto_id),
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE conversaciones(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,usuario_id INT UNSIGNED NOT NULL,negocio_id INT UNSIGNED NOT NULL,
 producto_id INT UNSIGNED NULL,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(negocio_id) REFERENCES negocios(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE SET NULL
)ENGINE=InnoDB;

CREATE TABLE mensajes(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,conversacion_id INT UNSIGNED NOT NULL,remitente_id INT UNSIGNED NOT NULL,
 contenido TEXT NOT NULL,leido BOOLEAN NOT NULL DEFAULT FALSE,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(conversacion_id) REFERENCES conversaciones(id) ON UPDATE CASCADE ON DELETE CASCADE,
 FOREIGN KEY(remitente_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE comentarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 producto_id INT UNSIGNED NOT NULL,
 usuario_id INT UNSIGNED NOT NULL,
 contenido TEXT NOT NULL,
 estado ENUM('activo','oculto','eliminado') NOT NULL DEFAULT 'activo',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_comentarios_producto FOREIGN KEY(producto_id) REFERENCES productos(id) ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT fk_comentarios_usuario FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE recuperacion_password(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,usuario_id INT UNSIGNED NOT NULL,token VARCHAR(255) NOT NULL UNIQUE,
 expira_at DATETIME NOT NULL,usado BOOLEAN NOT NULL DEFAULT FALSE,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE recuperacion_password_admin(
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,administrador_id INT UNSIGNED NOT NULL,token VARCHAR(255) NOT NULL UNIQUE,
 expira_at DATETIME NOT NULL,usado BOOLEAN NOT NULL DEFAULT FALSE,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(administrador_id) REFERENCES administradores(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

DELIMITER $$
CREATE TRIGGER trg_productos_categoria_subcategoria_insert BEFORE INSERT ON productos FOR EACH ROW
BEGIN
 IF NOT EXISTS(SELECT 1 FROM subcategorias WHERE id=NEW.subcategoria_id AND categoria_id=NEW.categoria_id) THEN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='La subcategoria no pertenece a la categoria seleccionada';
 END IF;
END$$
CREATE TRIGGER trg_productos_categoria_subcategoria_update BEFORE UPDATE ON productos FOR EACH ROW
BEGIN
 IF NOT EXISTS(SELECT 1 FROM subcategorias WHERE id=NEW.subcategoria_id AND categoria_id=NEW.categoria_id) THEN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='La subcategoria no pertenece a la categoria seleccionada';
 END IF;
END$$

CREATE TRIGGER trg_likes_after_insert AFTER INSERT ON likes_producto FOR EACH ROW
BEGIN
 UPDATE productos SET total_likes = total_likes + 1 WHERE id = NEW.producto_id;
END$$
CREATE TRIGGER trg_likes_after_delete AFTER DELETE ON likes_producto FOR EACH ROW
BEGIN
 UPDATE productos SET total_likes = total_likes - 1 WHERE id = OLD.producto_id;
END$$

CREATE TRIGGER trg_favoritos_after_insert AFTER INSERT ON favoritos FOR EACH ROW
BEGIN
 UPDATE productos SET total_favoritos = total_favoritos + 1 WHERE id = NEW.producto_id;
END$$
CREATE TRIGGER trg_favoritos_after_delete AFTER DELETE ON favoritos FOR EACH ROW
BEGIN
 UPDATE productos SET total_favoritos = total_favoritos - 1 WHERE id = OLD.producto_id;
END$$

CREATE TRIGGER trg_comentarios_after_insert AFTER INSERT ON comentarios FOR EACH ROW
BEGIN
 IF NEW.estado = 'activo' THEN
  UPDATE productos SET total_comentarios = total_comentarios + 1 WHERE id = NEW.producto_id;
 END IF;
END$$
CREATE TRIGGER trg_comentarios_after_update AFTER UPDATE ON comentarios FOR EACH ROW
BEGIN
 IF OLD.estado = 'activo' AND NEW.estado <> 'activo' THEN
  UPDATE productos SET total_comentarios = total_comentarios - 1 WHERE id = NEW.producto_id;
 ELSEIF OLD.estado <> 'activo' AND NEW.estado = 'activo' THEN
  UPDATE productos SET total_comentarios = total_comentarios + 1 WHERE id = NEW.producto_id;
 END IF;
END$$
CREATE TRIGGER trg_comentarios_after_delete AFTER DELETE ON comentarios FOR EACH ROW
BEGIN
 IF OLD.estado = 'activo' THEN
  UPDATE productos SET total_comentarios = total_comentarios - 1 WHERE id = OLD.producto_id;
 END IF;
END$$
DELIMITER ;

INSERT INTO categorias(nombre,descripcion) VALUES
('Casa y jardín','Productos para el hogar, jardín y mobiliario'),
('Joyería','Accesorios y artículos relacionados con joyería'),
('Computación y tecnología','Equipos, accesorios y tecnología'),
('Accesorios','Accesorios de uso personal y comercial'),
('Ferretería','Herramientas y artículos de ferretería'),
('Plomería','Productos y materiales para instalaciones de agua'),
('Electricidad','Materiales y accesorios eléctricos'),
('Herramientas','Herramientas manuales y eléctricas');

INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Jardín','Productos para jardín' FROM categorias WHERE nombre='Casa y jardín';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Muebles','Muebles para el hogar' FROM categorias WHERE nombre='Casa y jardín';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Gorras','Gorras y accesorios' FROM categorias WHERE nombre='Joyería';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Relojes','Relojes' FROM categorias WHERE nombre='Joyería';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Accesorios personales','Accesorios de uso personal' FROM categorias WHERE nombre='Joyería';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Impresoras','Impresoras y equipos relacionados' FROM categorias WHERE nombre='Computación y tecnología';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Seguridad','Productos tecnológicos de seguridad' FROM categorias WHERE nombre='Computación y tecnología';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Videojuegos','Videojuegos y accesorios' FROM categorias WHERE nombre='Computación y tecnología';
INSERT INTO subcategorias(categoria_id,nombre,descripcion) 
SELECT id,'Computadoras','Computadoras y equipos' FROM categorias WHERE nombre='Computación y tecnología';
