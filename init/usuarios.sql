-- @ CONFIG ENVIRONMENT VARIABLES
-- // Default user password
SET @ENV_ROOT_PASSWORD = '!DefaultRootPassword1234';
SET @ENV_ADMN_PASSWORD = '!DefaultAdminPassword1234';
SET @ENV_DATA_PASSWORD = '!DefaultAnalystPassword1234';

-- // Admin permissions
-- Permission hierarchy goes left (most) right (least)
-- DELETE MODIFY CREATE VIEW NONE
-- Boolean properties take either DELETE or NONE
SET @ENV_ADMN_USER_PERMISSION		  = "NONE";
SET @ENV_ADMN_FILE_PERMISSION		  = "CREATE";
SET @ENV_ADMN_CONTACT_PERMISSION	  = "CREATE";
SET @ENV_ADMN_CLIENT_PERMISSION		  = "CREATE";
SET @ENV_ADMN_LINK_CONTACT_PERMISSION = "DELETE";
SET @ENV_ADMN_PRODUCT_PERMISSION	  = "DELETE";
SET @ENV_ADMN_ORDER_PERMISSION		  = "DELETE";
SET @ENV_ADMN_EVENT_PERMISSION		  = "VIEW";

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

-- @ CONFIGURE USER PERMISSIONS
GRANT ALL ON empresa.* TO empresa_root;
GRANT SELECT, INSERT, UPDATE, DELETE ON empresa.* TO empresa_admin;
GRANT SELECT ON empresa.* TO empresa_analyst;

-- // Initialize permission table
USE empresa;

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
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "USER_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "FILE_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CONTACT_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "CLIENT_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "LINK_CONTACT_PERMISSION" LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "PRODUCT_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "ORDER_PERMISSION" 		LIMIT 1), "DELETE");
INSERT INTO PropiedadRol (nombreRol, nombrePermiso, valor) VALUES ((SELECT nombre FROM Rol WHERE nombre = "DIRECTOR" LIMIT 1), (SELECT nombre FROM Permiso WHERE nombre = "EVENT_PERMISSION" 		LIMIT 1), "DELETE");

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