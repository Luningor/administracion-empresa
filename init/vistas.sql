-- @TODOS LOS EVENTOS A PARTIR DE LA FECHA ACTUAL ORDENADOS POR FECHA
CREATE VIEW proximos_eventos
	SELECT identificador, descripcion, fecha AS fechaEvento
	FROM evento
		WHERE identificador IN (SELECT identificadorEvento FROM eventounico) AND fecha > NOW()
	UNION
	SELECT identificador, descripcion, GET_RECURRENT_EVENT_DATE(identificador) AS fechaEvento
	FROM evento
		WHERE identificador IN (SELECT identificadorEvento FROM eventoperiodico)
		ORDER BY fechaEvento ASC;

-- @TODOS LOS CONTACTOS SIN UN CLIENTE RELACIONADO
CREATE VIEW contactos_libres AS
	SELECT contacto.identificador AS identificadorContacto, direccionEmail AS direccionContacto 
	FROM contacto
		CROSS JOIN mail
		ON contacto.identificador = mail.identificadorContacto
		WHERE ISNULL(cliente)
	UNION
	SELECT contacto.identificador AS identificadorContacto, numeroTelefono AS direccionContacto 
	FROM contacto
		CROSS JOIN telefono
		ON contacto.identificador = telefono.identificadorContacto
		WHERE ISNULL(cliente)
	UNION
	SELECT contacto.identificador AS identificadorContacto, numeroCelular AS direccionContacto 
	FROM contacto
		CROSS JOIN celular
		ON contacto.identificador = celular.identificadorContacto
		WHERE ISNULL(cliente)
		ORDER BY cliente ASC;

-- @TODAS LAS ORDENES ADJUNTAS CON EL ID Y NOMBRE DEL CLIENTE
CREATE VIEW ordenes_ext AS
	SELECT numeroOrden, comprador, nombre, fechaOrden, recibo FROM orden
		CROSS JOIN cliente
		ON comprador = alias
		ORDER BY nombre;