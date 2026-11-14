-- @ CREATE DATABASE
CREATE DATABASE empresa;
USE empresa;

-- @ CREATE TABLES
CREATE TABLE Usuario (
	cedula BIGINT(255) PRIMARY KEY,
	nombre VARCHAR(100),
	correo VARCHAR(100) UNIQUE CHECK (correo RLIKE '^[a-zA-Z0-9][a-zA-Z0-9.!#$%&\'*+-/=?^_`{|}~]*?[a-zA-Z0-9._-]?@[a-zA-Z0-9][a-zA-Z0-9._-]*?[a-zA-Z0-9]?\\.[a-zA-Z]{2,63}$') DEFAULT NULL,
	telefono VARCHAR(100) UNIQUE DEFAULT NULL,
	celular VARCHAR(100) UNIQUE DEFAULT NULL,
	clave VARCHAR(100) NOT NULL,
	habilitado BOOLEAN,
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
	valor ENUM("DELETE", "MODIFY", "CREATE", "VIEW", "NONE") NOT NULL DEFAULT "NONE",
	PRIMARY KEY (nombreRol, nombrePermiso),
	FOREIGN KEY (nombreRol) 	REFERENCES Rol(nombre),
	FOREIGN KEY (nombrePermiso) REFERENCES Permiso(nombre)
);

CREATE TABLE Archivo (
	filePath VARCHAR(120) PRIMARY KEY,
	creador BIGINT(255) NOT NULL,
	nombre VARCHAR(100) UNIQUE,
	FOREIGN KEY (creador) REFERENCES Usuario(cedula)
);

CREATE TABLE Compra (
	filePath VARCHAR(120) PRIMARY KEY,
	compradoPor BIGINT(255) NOT NULL,
	FOREIGN KEY (compradoPor) REFERENCES Usuario(cedula),
	FOREIGN KEY (filePath)    REFERENCES Archivo(filePath)
);

CREATE TABLE Venta (
	filePath VARCHAR(120) PRIMARY KEY,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath)
);

CREATE TABLE Documento (
	filePath VARCHAR(120) PRIMARY KEY,
	descripcion TINYTEXT NOT NULL DEFAULT "",
	visible BOOLEAN NOT NULL DEFAULT FALSE,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath)
);

CREATE TABLE Sueldo (
	filePath VARCHAR(120) PRIMARY KEY,
	recipiente BIGINT(255) NOT NULL,
	FOREIGN KEY (filePath) REFERENCES Archivo(filePath),
	FOREIGN KEY (recipiente) REFERENCES Usuario(cedula)
);

CREATE TABLE Producto (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(100) NOT NULL,
	descripcion TINYTEXT NOT NULL DEFAULT "",
	stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
	precio FLOAT(2)
);

CREATE TABLE HistorialPrecio (
	id INT NOT NULL,
	precio FLOAT(2) NOT NULL DEFAULT 0 CHECK (precio >= 0),
	fechaCambio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	PRIMARY KEY (id, precio, fechaCambio),
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
	recibo VARCHAR(120) NOT NULL,
	FOREIGN KEY (comprador) REFERENCES Cliente(alias),
	FOREIGN KEY (recibo) 	REFERENCES Venta(filePath)
);

CREATE TABLE LineaOrden (
	uuidProducto INT,
	numeroOrden INT,
	cantidad INT NOT NULL DEFAULT 1 CHECK (cantidad > 0),
	PRIMARY KEY (uuidProducto, numeroOrden)
);

CREATE TABLE Evento (
	identificador INT PRIMARY KEY AUTO_INCREMENT,
	creador BIGINT(255) NOT NULL,
	descripcion TINYTEXT NOT NULL DEFAULT "",
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
	identificador INT PRIMARY KEY AUTO_INCREMENT,
	creador BIGINT(255) NOT NULL,
	cliente VARCHAR(50) NOT NULL,
	FOREIGN KEY (creador) REFERENCES Usuario(cedula),
	FOREIGN KEY (cliente) REFERENCES Cliente(alias)
);

CREATE TABLE AliasContacto (
	identificadorContacto INT PRIMARY KEY,
	alias VARCHAR(100) NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE EtiquetaContacto (
	identificadorContacto INT PRIMARY KEY,
	etiqueta VARCHAR(100) NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Mail (
	identificadorContacto INT PRIMARY KEY,
	direccionEmail VARCHAR(100) UNIQUE NOT NULL CHECK (direccionEmail RLIKE '^[a-zA-Z0-9][a-zA-Z0-9.!#$%&\'*+-/=?^_`{|}~]*?[a-zA-Z0-9._-]?@[a-zA-Z0-9][a-zA-Z0-9._-]*?[a-zA-Z0-9]?\\.[a-zA-Z]{2,63}$'),
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Telefono (
	identificadorContacto INT PRIMARY KEY,
	numeroTelefono VARCHAR(100) UNIQUE NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);

CREATE TABLE Celular (
	identificadorContacto INT PRIMARY KEY,
	numeroCelular VARCHAR(100) UNIQUE NOT NULL,
	FOREIGN KEY (identificadorContacto) REFERENCES Contacto(identificador)
);