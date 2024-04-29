
CREATE DATABASE `dev_classroom` ;

USE `dev_classroom`;


CREATE TABLE usuarios (
  id INT NOT NULL AUTO_INCREMENT,
  rol_id INT NOT NULL,
  usuario varchar(250) NOT NULL,
  nombre varchar(250) NOT NULL,
  telefono varchar(100) NOT NULL,
  password varchar(150) NOT NULL,
  status INT NOT NULL DEFAULT 1,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)ENGINE=INNODB;


CREATE TABLE rooms (
  id INT NOT NULL AUTO_INCREMENT,
  room_title VARCHAR (250) NOT NULL,
  room_desks INT NOT NULL,
  room_status INT NOT NULL DEFAULT 1,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)ENGINE=INNODB;

CREATE TABLE desks (
  id INT NOT NULL AUTO_INCREMENT,
  room_id INT NOT NULL,
  desk_num INT NOT NULL,
  desk_time INT NOT NULL,
  desk_status INT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  CONSTRAINT fk_room_id_desk
  FOREIGN KEY (room_id)
  REFERENCES rooms (id)
)ENGINE=INNODB;

CREATE TABLE tareas_completadas (
  id INT NOT NULL AUTO_INCREMENT,
  puesto VARCHAR (250) NOT NULL,
  asignacion VARCHAR (250) NOT NULL,
  cantidad INT NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
)ENGINE=INNODB;

CREATE TABLE asignaciones (
  id INT NOT NULL AUTO_INCREMENT,
  asignacion_title VARCHAR (250) NOT NULL,
  asignacion_tiempo INT NOT NULL,
  asignacion_status INT NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
)ENGINE=INNODB;
