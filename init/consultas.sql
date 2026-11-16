-- @PROMEDIO DE LAS GANANCIAS DE LOS ULTIMOS 365 DIAS (APROX)
-- WHERE JOIN AVG() ORDER BY

CREATE TEMPORARY TABLE temp_orders 
	SELECT * FROM orden 
		WHERE fechaOrden BETWEEN FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()) - 31622400) AND FROM_UNIXTIME(UNIX_TIMESTAMP(NOW())) 
		ORDER BY numeroOrden ASC;
# SELECT * FROM temp_orders;

CREATE TEMPORARY TABLE temp_line_orders 
	SELECT * FROM lineaorden 
		WHERE numeroOrden IN (SELECT numeroOrden FROM temp_orders) 
		ORDER BY numeroOrden ASC;
# SELECT * FROM temp_line_orders;

CREATE TEMPORARY TABLE temp_products 
	SELECT * FROM producto 
		WHERE id IN (SELECT uuidProducto FROM temp_line_orders) 
		ORDER BY id ASC; 
# SELECT * FROM temp_products;

CREATE TEMPORARY TABLE temp_join_product_lines 
	SELECT * FROM temp_line_orders
		CROSS JOIN temp_products 
		ON temp_line_orders.uuidProducto = temp_products.id 
		ORDER BY numeroOrden ASC;
# SELECT * FROM temp_join_product_lines;

CREATE TEMPORARY TABLE temp_join 
	SELECT  comprador, temp_join_product_lines.numeroOrden, uuidProducto, nombre, cantidad, precio, fechaOrden
	FROM temp_join_product_lines 
		CROSS JOIN temp_orders
		ON temp_join_product_lines.numeroOrden = temp_orders.numeroOrden 
		ORDER BY temp_join_product_lines.numeroOrden ASC;
# SELECT * FROM temp_join;

CREATE TEMPORARY TABLE temp_result
	SELECT comprador, numeroOrden, uuidProducto, nombre, cantidad, GET_PRODUCT_PRICE_ON_DATE(uuidProducto, fechaOrden) AS precioEnFecha, fechaOrden
	FROM temp_join
		ORDER BY numeroOrden ASC;
# SELECT * FROM temp_result;

CREATE TEMPORARY TABLE final_result
	SELECT comprador, numeroOrden, uuidProducto, nombre, precioEnFecha, cantidad, precioEnFecha * cantidad AS gananciaTotal, fechaOrden
	FROM temp_result
		ORDER BY uuidProducto ASC;
SELECT * FROM final_result;

SELECT ROUND(AVG(gananciaTotal), 2)
FROM final_result;

DROP TEMPORARY TABLE temp_orders;
DROP TEMPORARY TABLE temp_line_orders;
DROP TEMPORARY TABLE temp_products;
DROP TEMPORARY TABLE temp_join_product_lines;
DROP TEMPORARY TABLE temp_join;
DROP TEMPORARY TABLE temp_result;
DROP TEMPORARY TABLE final_result;

-- @TODOS LOS DATOS DE CONTACTO DE UN CLIENTE
-- WHERE JOIN ORDER BY
SET @ALIAS = "nonagon.praisingly";

CREATE TEMPORARY TABLE temp_mails
	SELECT cliente, contacto.identificador AS identificadorContacto, direccionEmail AS direccionContacto 
	FROM contacto
		CROSS JOIN mail
		ON contacto.identificador = mail.identificadorContacto
		WHERE contacto.cliente = @ALIAS
		ORDER BY contacto.cliente ASC;

CREATE TEMPORARY TABLE temp_telephone_numbers
	SELECT cliente, contacto.identificador AS identificadorContacto, numeroTelefono AS direccionContacto 
	FROM contacto
		CROSS JOIN telefono
		ON contacto.identificador = telefono.identificadorContacto
		WHERE contacto.cliente = @ALIAS
		ORDER BY contacto.cliente ASC;

CREATE TEMPORARY TABLE temp_cellular_numbers
	SELECT cliente, contacto.identificador AS identificadorContacto, numeroCelular AS direccionContacto 
	FROM contacto
		CROSS JOIN celular
		ON contacto.identificador = celular.identificadorContacto
		WHERE contacto.cliente = @ALIAS
		ORDER BY contacto.cliente ASC;

CREATE TEMPORARY TABLE contact_mediums
	SELECT * FROM temp_mails 
	UNION 
	SELECT * FROM temp_telephone_numbers 
	UNION 
	SELECT * FROM temp_cellular_numbers;

CREATE TEMPORARY TABLE final_result
	SELECT identificadorContacto, nombre, direccionContacto
	FROM cliente
		CROSS JOIN contact_mediums
		ON contact_mediums.cliente = cliente.alias
		WHERE alias = @ALIAS
		ORDER BY identificadorContacto ASC;
SELECT * FROM final_result;

DROP TEMPORARY TABLE temp_mails;
DROP TEMPORARY TABLE temp_telephone_numbers;
DROP TEMPORARY TABLE temp_cellular_numbers;
DROP TEMPORARY TABLE contact_mediums;
DROP TEMPORARY TABLE final_result;

-- @TODAS LAS ORDENES DE UN CLIENTE
-- WHERE JOIN ORDER BY
SET @ALIAS = "formaldehydesulphoxylic.anomalous";

SELECT comprador, nombre, numeroOrden, fechaOrden
FROM orden
	CROSS JOIN cliente
	ON cliente.alias = orden.comprador
	WHERE comprador = @ALIAS
	ORDER BY fechaOrden ASC;

-- @TODOS LOS CONTACTOS CREADOS POR UN EMPLEADO
-- WHERE JOIN
SET @ID_EMPLOYEE = 25060346;

CREATE TEMPORARY TABLE temp_contacts
	SELECT cedula, nombre AS nombreEmpleado, identificador AS idCont, cliente AS aliasCliente
	FROM contacto 
		CROSS JOIN usuario
		ON contacto.creador = usuario.cedula
		WHERE cedula = @ID_EMPLOYEE
		ORDER BY idCont ASC;
# SELECT * FROM temp_contacts;

CREATE TEMPORARY TABLE temp_mails
	SELECT temp_contacts.idCont, direccionEmail AS direccionContacto 
	FROM temp_contacts
		CROSS JOIN mail
		ON temp_contacts.idCont = mail.identificadorContacto
		WHERE mail.identificadorContacto IN (SELECT temp_contacts.idCont)
		ORDER BY idCont ASC;
# SELECT * FROM temp_mails;

CREATE TEMPORARY TABLE temp_telephone_numbers
	SELECT temp_contacts.idCont, numeroTelefono AS direccionContacto 
	FROM temp_contacts
		CROSS JOIN telefono
		ON temp_contacts.idCont = telefono.identificadorContacto
		WHERE telefono.identificadorContacto IN (SELECT temp_contacts.idCont)
		ORDER BY idCont ASC;
# SELECT * FROM temp_telephone_numbers;

CREATE TEMPORARY TABLE temp_cellular_numbers
	SELECT temp_contacts.idCont, numeroCelular AS direccionContacto 
	FROM temp_contacts
		CROSS JOIN celular
		ON temp_contacts.idCont = celular.identificadorContacto
		WHERE celular.identificadorContacto IN (SELECT temp_contacts.idCont)
		ORDER BY idCont ASC;
# SELECT * FROM temp_cellular_numbers;

CREATE TEMPORARY TABLE contact_mediums
	SELECT * FROM temp_mails 
	UNION 
	SELECT * FROM temp_telephone_numbers 
	UNION 
	SELECT * FROM temp_cellular_numbers;
# SELECT * FROM contact_mediums;

CREATE TEMPORARY TABLE temp_contact_data
	SELECT cedula, nombreEmpleado, temp_contacts.idCont, aliasCliente, direccionContacto
	FROM temp_contacts
		CROSS JOIN contact_mediums
		ON contact_mediums.idCont = temp_contacts.idCont
		ORDER BY temp_contacts.idCont ASC;
# SELECT * FROM temp_contact_data;

CREATE TEMPORARY TABLE final_result
	SELECT cedula, nombreEmpleado, temp_contact_data.idCont, aliasCliente, nombre AS nombreCliente, direccionContacto
	FROM temp_contact_data
		CROSS JOIN cliente
		ON cliente.alias <=> temp_contact_data.aliasCliente
		ORDER BY temp_contact_data.idCont ASC;
SELECT * FROM final_result;

DROP TEMPORARY TABLE temp_contacts;
DROP TEMPORARY TABLE temp_mails;
DROP TEMPORARY TABLE temp_telephone_numbers;
DROP TEMPORARY TABLE temp_cellular_numbers;
DROP TEMPORARY TABLE contact_mediums;
DROP TEMPORARY TABLE temp_contact_data;
DROP TEMPORARY TABLE final_result;

-- @CANTIDAD DE VECES QUE SE HA COMPRADO UN PRODUCTO + CANTIDAD DE PRODUCTO COMPRADO TOTAL
-- WHERE COUNT() SUM()
SET @PRODUCT_ID = 11;

CREATE TEMPORARY TABLE compras_producto
	SELECT uuidProducto, cantidad 
	FROM lineaorden
		WHERE uuidProducto = @PRODUCT_ID
		ORDER BY uuidProducto;
# SELECT * FROM compras_producto;

SELECT producto.id, nombre, descripcion, COUNT(uuidProducto) as cantidadCompras, SUM(cantidad) as ventasTotales 
FROM compras_producto
	CROSS JOIN producto
	ON compras_producto.uuidProducto = producto.id;

DROP TEMPORARY TABLE compras_producto;

-- @TODOS LOS PRODUCTOS DENTRO DE UN RANGO, DEL MAS CARO AL MAS BARATO
-- WHERE ORDER BY BETWEEN

SET @MINVAL = 1000;
SET @MAXVAL = 2100;

SELECT * FROM producto WHERE precio BETWEEN @MINVAL AND @MAXVAL ORDER BY precio DESC;

-- @DATOS DEL CLIENTE CON LA COMPRA QUE GENERO MAS INGRESOS
-- MAX() JOIN GROUP BY ORDER BY SUM

CREATE TEMPORARY TABLE temp_order_products 
	SELECT comprador, lineaorden.numeroOrden, uuidProducto, GET_PRODUCT_PRICE_ON_DATE(uuidProducto, fechaOrden) AS precio, cantidad, fechaOrden
	FROM orden
		CROSS JOIN lineaorden
		ON lineaorden.numeroOrden = orden.numeroOrden
		ORDER BY comprador ASC;
# SELECT * FROM temp_order_products;

CREATE TEMPORARY TABLE temp_gasto_clientes
	SELECT comprador, numeroOrden, ROUND(SUM(precio), 2) AS precioTotal, fechaOrden
	FROM temp_order_products
		GROUP BY comprador
		ORDER BY precioTotal DESC;
# SELECT * FROM temp_gasto_clientes;

CREATE TEMPORARY TABLE temp_comprador_ids
	SELECT comprador FROM temp_gasto_clientes;

CREATE TEMPORARY TABLE final_result
	SELECT comprador, nombre, informacion, MAX(precioTotal), numeroOrden, fechaOrden
	FROM temp_gasto_clientes
		CROSS JOIN cliente
		ON temp_gasto_clientes.comprador = cliente.alias
		WHERE alias IN (SELECT comprador FROM temp_comprador_ids);
SELECT * FROM final_result;

DROP TEMPORARY TABLE temp_order_products;
DROP TEMPORARY TABLE temp_gasto_clientes;
DROP TEMPORARY TABLE temp_comprador_ids;
DROP TEMPORARY TABLE final_result;

-- @TODOS LOS PRODUCTOS ORDENADOS POR MENOR CANTIDAD DE STOCK
-- ORDER BY
SELECT * FROM producto ORDER BY stock ASC;

-- @EL EVENTO MAS CERCANO A LA FECHA ACTUAL
-- WHERE MIN()
CREATE TEMPORARY TABLE once_events
	SELECT identificador, descripcion, fecha AS fechaEvento
	FROM evento
		WHERE identificador IN (SELECT identificadorEvento FROM eventounico) AND fecha > NOW()
		ORDER BY fechaEvento ASC;
# SELECT * FROM once_events;

CREATE TEMPORARY TABLE recurrent_events
	SELECT identificador, descripcion, GET_RECURRENT_EVENT_DATE(identificador) AS fechaEvento
	FROM evento
		WHERE identificador IN (SELECT identificadorEvento FROM eventoperiodico)
		ORDER BY fechaEvento ASC;
# SELECT * FROM recurrent_events;

CREATE TEMPORARY TABLE temp_events
	SELECT * FROM once_events
	UNION
	SELECT * FROM recurrent_events
		ORDER BY fechaEvento ASC;
SELECT * FROM temp_events;

SET @MIN_FECHA = (SELECT MIN(fechaEvento) FROM temp_events);

SELECT * FROM temp_events 
	WHERE fechaEvento = @MIN_FECHA;

DROP TEMPORARY TABLE once_events;
DROP TEMPORARY TABLE recurrent_events;
DROP TEMPORARY TABLE temp_events;

-- @TODOS LOS CLIENTES, AGRUPADOS POR ETIQUETA
-- JOIN GROUP BY
SELECT etiqueta, COUNT(cliente) AS clientesEnEtiqueta
FROM etiquetacontacto
	CROSS JOIN contacto
	ON etiquetacontacto.identificadorContacto = contacto.identificador
	WHERE NOT ISNULL(contacto.cliente)
	GROUP BY etiqueta
	ORDER BY clientesEnEtiqueta DESC;