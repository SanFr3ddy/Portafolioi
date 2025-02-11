psql -U postgres -d postgres
CREATE DATABASE lavanderia;

CREATE TABLE cliente (
    id_cliente INT PRIMARY KEY,
    nombre VARCHAR(255),
    apellido VARCHAR(255),
    telefono CHAR(10),
    id_direccion INT,
    fecha_ingreso DATE
);

CREATE TABLE direccion (
    id_direccion INT PRIMARY KEY,
    ciudad VARCHAR(255),
    codigo_postal VARCHAR(10),
    numero_casa VARCHAR(10),
    calle VARCHAR(255)
);

ALTER TABLE cliente
ADD CONSTRAINT FK_cliente_direccion FOREIGN KEY (id_direccion) REFERENCES direccion(id_direccion);

CREATE TABLE productos_y_servicios (
    id_producto_servicio INT PRIMARY KEY,
    id_cliente INT,
    nombre VARCHAR(255),
    precio DECIMAL(10,2),
    tipo VARCHAR(255),
    id_clasificacion INT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_clasificacion) REFERENCES tipo(id_clasificacion)
);

CREATE TABLE tipo (
    id_tipo INT PRIMARY KEY,
    id_producto_servicio INT,
    id_clasificacion INT,
    FOREIGN KEY (id_producto_servicio) REFERENCES productos_y_servicios(id_producto_servicio),
    FOREIGN KEY (id_clasificacion) REFERENCES clasificacion(id_clasificacion)
);

CREATE TABLE clasificacion (
    id_clasificacion INT PRIMARY KEY,
    nombre VARCHAR(255),
    tipo VARCHAR(255),
    descripcion TEXT
);

CREATE TABLE orden (
    id_orden INT PRIMARY KEY,
    id_cliente INT,
    id_direccion INT,
    id_nuevo_orden INT,
    fecha_ingreso DATE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_direccion) REFERENCES direccion(id_direccion)
);

CREATE TABLE ordenes (
    id_cliente INT,
    id_orden INT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_orden) REFERENCES orden(id_orden)
);

CREATE TABLE subtotal (
    id_producto INT,
    id_orden INT,
    cantidad INT,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_producto) REFERENCES productos_servicios(id_producto),
    FOREIGN KEY (id_orden) REFERENCES orden(id_orden)
);
