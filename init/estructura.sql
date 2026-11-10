-- @ CONFIG ENVIRONMENT VARIABLES
-- // Default user password
SET @ENV_ROOT_PASSWORD = '!DefaultRootPassword1234';
SET @ENV_ADMN_PASSWORD = '!DefaultAdminPassword1234';
SET @ENV_DATA_PASSWORD = '!DefaultAnalystPassword1234';

-- // Admin permissions
-- Permission hierarchy goes left (most) right (least)
-- DELETE MODIFY CREATE VIEW NONE
-- Boolean properties take either ALL or NONE
SET @ENV_ADMN_USER_PERMISSION		  = "NONE";
SET @ENV_ADMN_FILE_PERMISSION		  = "CREATE";
SET @ENV_ADMN_CONTACT_PERMISSION	  = "CREATE";
SET @ENV_ADMN_CLIENT_PERMISSION		  = "CREATE";
SET @ENV_ADMN_LINK_CONTACT_PERMISSION = "ALL";
SET @ENV_ADMN_PRODUCT_PERMISSION	  = "DELETE";
SET @ENV_ADMN_ORDER_PERMISSION		  = "DELETE";
SET @ENV_ADMN_EVENT_PERMISSION		  = "VIEW";

-- @ CREATE DATABASE
CREATE DATABASE empresa;
USE empresa;

-- @ CREATE USERS
SET @QUERY = CONCAT(CONCAT("CREATE USER 'empresa_root'     IDENTIFIED BY '", @ENV_ROOT_PASSWORD), "'");
PREPARE STATEMENT FROM @QUERY;
EXECUTE STATEMENT;
DEALLOCATE PREPARE STATEMENT;

SET @QUERY = CONCAT(CONCAT("CREATE USER 'empresa_admin'    IDENTIFIED BY '", @ENV_ADMN_PASSWORD), "'");
PREPARE STATEMENT FROM @QUERY;
EXECUTE STATEMENT;
DEALLOCATE PREPARE STATEMENT;

SET @QUERY = CONCAT(CONCAT("CREATE USER 'empresa_analyst' IDENTIFIED BY '", @ENV_DATA_PASSWORD), "'");
PREPARE STATEMENT FROM @QUERY;
EXECUTE STATEMENT;
DEALLOCATE PREPARE STATEMENT;

-- @ CREATE TABLES
CREATE TABLE Usuario (
	cedula BIGINT(255) PRIMARY KEY,
	nombre VARCHAR(100),
	correo VARCHAR(100) UNIQUE CHECK (correo RLIKE '^[a-zA-Z0-9][a-zA-Z0-9.!#$%&\'*+-/=?^_`{|}~]*?[a-zA-Z0-9._-]?@[a-zA-Z0-9][a-zA-Z0-9._-]*?[a-zA-Z0-9]?\\.[a-zA-Z]{2,63}$') DEFAULT NULL,
	telefono VARCHAR(100) UNIQUE DEFAULT NULL,
	celular VARCHAR(100) UNIQUE DEFAULT NULL,
	clave VARCHAR(100) NOT NULL,
	habilitidado BOOLEAN,
	fechaPago TIMESTAMP
);

CREATE TABLE Director (
	cedula BIGINT(255) PRIMARY KEY,
	FOREIGN KEY (cedula) REFERENCES Usuario(cedula)
);

CREATE TABLE Administrador (
	cedula BIGINT(255) PRIMARY KEY,
	FOREIGN KEY (cedula) REFERENCES Usuario(cedula)
);

CREATE TABLE Empleado (
	cedula BIGINT(255) PRIMARY KEY,
	FOREIGN KEY (cedula) REFERENCES Usuario(cedula)
);

CREATE TABLE Rol (
	nombre VARCHAR(50) PRIMARY KEY
);

CREATE TABLE Permiso (
	nombre VARCHAR(50) PRIMARY KEY
);

CREATE TABLE PropiedadRol (
	nombreRol VARCHAR(50),
	nombrePermiso VARCHAR(50),
	valor ENUM("DELETE", "MODIFY", "CREATE", "VIEW", "NONE"),
	PRIMARY KEY (nombreRol, nombrePermiso),
	FOREIGN KEY (nombreRol) 	REFERENCES Rol(nombre),
	FOREIGN KEY (nombrePermiso) REFERENCES Permiso(nombre)
);

CREATE TABLE Archivo (
	filePath VARCHAR(100) PRIMARY KEY,
	creador BIGINT(255) NOT NULL,
	nombre VARCHAR(100) UNIQUE,
	FOREIGN KEY (creador) REFERENCES Usuario(cedula)
);

CREATE TABLE Compra (
	filePath VARCHAR(100) PRIMARY KEY,
	compradoPor BIGINT(255) NOT NULL,
	FOREIGN KEY (compradoPor) REFERENCES Usuario(cedula),
	FOREIGN KEY (filePath)    REFERENCES Archivo(filePath)
);

CREATE TABLE Venta (
	filePath VARCHAR(100) PRIMARY KEY,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath)
);

CREATE TABLE Documento (
	filePath VARCHAR(100) PRIMARY KEY,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath)
);

CREATE TABLE Sueldo (
	filePath VARCHAR(100) PRIMARY KEY,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath)
);

CREATE TABLE Producto (
	id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID())),
	nombre VARCHAR(100) NOT NULL,
	descripcion TINYTEXT NOT NULL,
	stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
	precio FLOAT(2)
);

CREATE TABLE HistorialPrecio (
	id BINARY(16) PRIMARY KEY,
	precio FLOAT(2) NOT NULL DEFAULT 0 CHECK (precio >= 0),
	fechaCambio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	FOREIGN KEY (id) REFERENCES Producto(id)
);

CREATE TABLE Cliente (
	alias VARCHAR(50) PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	informacion TINYTEXT DEFAULT NULL
);

CREATE TABLE Orden (
	numeroOrden INT PRIMARY KEY AUTO_INCREMENT,
	comprador VARCHAR(50) NOT NULL,
	recibo VARCHAR(100) NOT NULL,
	FOREIGN KEY (comprador) REFERENCES Cliente(alias),
	FOREIGN KEY (recibo) 	REFERENCES Venta(filePath)
);

CREATE TABLE LineaOrden (
	uuidProducto BINARY(16),
	numeroOrden INT,
	cantidad INT NOT NULL DEFAULT 1 CHECK (cantidad > 0),
	PRIMARY KEY (uuidProducto, numeroOrden)
);

CREATE TABLE Evento (
	identificador INT PRIMARY KEY AUTO_INCREMENT,
	creador BIGINT(255) NOT NULL,
	descripcion TINYTEXT NOT NULL,
	fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	FOREIGN KEY (creador) REFERENCES Usuario(cedula)
);

CREATE TABLE EventoUnico (
	identificadorEvento INT PRIMARY KEY,
	FOREIGN KEY (identificadorEvento) REFERENCES Evento(identificador)
);

CREATE TABLE EventoPeriodico (
	identificadorEvento INT PRIMARY KEY,
	periodo INT NOT NULL DEFAULT 1 CHECK (periodo > 0),
	FOREIGN KEY (identificadorEvento) REFERENCES Evento(identificador)
);

CREATE TABLE Contacto (
	identificador BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID())),
	creador BIGINT(255) NOT NULL,
	cliente VARCHAR(50) NOT NULL,
	FOREIGN KEY (creador) REFERENCES Usuario(cedula),
	FOREIGN KEY (cliente) REFERENCES Cliente(alias)
);

CREATE TABLE AliasContacto (
	identificadorContacto BINARY(16) PRIMARY KEY,
	alias VARCHAR(100) NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE EtiquetaContacto (
	identificadorContacto BINARY(16) PRIMARY KEY,
	etiqueta VARCHAR(100) NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Mail (
	identificadorContacto BINARY(16) PRIMARY KEY,
	direccionEmail VARCHAR(100) UNIQUE NOT NULL CHECK (direccionEmail RLIKE '^[a-zA-Z0-9][a-zA-Z0-9.!#$%&\'*+-/=?^_`{|}~]*?[a-zA-Z0-9._-]?@[a-zA-Z0-9][a-zA-Z0-9._-]*?[a-zA-Z0-9]?\\.[a-zA-Z]{2,63}$'),
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Telefono (
	identificadorContacto BINARY(16) PRIMARY KEY,
	numeroTelefono VARCHAR(100) UNIQUE NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Celular (
	identificadorContacto BINARY(16) PRIMARY KEY,
	numeroCelular VARCHAR(100) UNIQUE NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

-- @ CONFIGURE USER PERMISSIONS
GRANT ALL ON empresa.* TO empresa_root;
GRANT SELECT, INSERT, UPDATE, DELETE ON empresa.* TO empresa_admin;
GRANT SELECT ON empresa.* TO empresa_analyst;

-- // Initialize permission table
INSERT INTO Permiso (nombre) VALUES ("USER_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("FILE_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("CONTACT_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("CLIENT_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("LINK_CONTACT_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("PRODUCT_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("ORDER_PERMISSION");
INSERT INTO Permiso (nombre) VALUES ("EVENT_PERMISSION");

INSERT INTO Rol (nombre) VALUES ("DIRECTOR");
INSERT INTO Rol (nombre) VALUES ("ADMINISTRATOR");
INSERT INTO Rol (nombre) VALUES ("EMPLOYEE");

-- // Config director role permissions
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "USER_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "FILE_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CONTACT_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CLIENT_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "LINK_CONTACT_PERMISSION" LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "PRODUCT_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "ORDER_PERMISSION" 		LIMIT 1), "ALL");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "EVENT_PERMISSION" 		LIMIT 1), "ALL");

-- // Config administrator role permissions
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "USER_PERMISSION" 		 LIMIT 1), @ENV_ADMN_USER_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "FILE_PERMISSION" 		 LIMIT 1), @ENV_ADMN_FILE_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CONTACT_PERMISSION" 	 LIMIT 1), @ENV_ADMN_CONTACT_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CLIENT_PERMISSION" 		 LIMIT 1), @ENV_ADMN_CLIENT_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "LINK_CONTACT_PERMISSION" LIMIT 1), @ENV_ADMN_LINK_CONTACT_PERMISSION );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "PRODUCT_PERMISSION" 	 LIMIT 1), @ENV_ADMN_PRODUCT_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "ORDER_PERMISSION" 		 LIMIT 1), @ENV_ADMN_ORDER_PERMISSION		 );
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "ADMINISTRATOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "EVENT_PERMISSION" 		 LIMIT 1), @ENV_ADMN_EVENT_PERMISSION		 );

-- // Config employee role permissions
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "USER_PERMISSION" 		LIMIT 1), "NONE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "FILE_PERMISSION" 		LIMIT 1), "VIEW");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CONTACT_PERMISSION" 		LIMIT 1), "VIEW");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CLIENT_PERMISSION" 		LIMIT 1), "VIEW");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "LINK_CONTACT_PERMISSION" LIMIT 1), "NONE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "PRODUCT_PERMISSION" 		LIMIT 1), "NONE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "ORDER_PERMISSION" 		LIMIT 1), "NONE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "EMPLOYEE" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "EVENT_PERMISSION" 		LIMIT 1), "VIEW");