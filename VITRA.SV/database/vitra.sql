-- VITRA / ZENITH - Base de datos MySQL
-- Ejecuta este archivo una sola vez en tu servidor MySQL.

CREATE DATABASE IF NOT EXISTS vitra
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vitra;

CREATE TABLE IF NOT EXISTS consultas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NULL,
  interes ENUM(
    'ZENITH S',
    'ZENITH D',
    'ZENITH X',
    'Información sobre VITRA'
  ) NOT NULL,
  mensaje VARCHAR(2000) NOT NULL,
  estado ENUM('nuevo', 'leido', 'respondido') NOT NULL DEFAULT 'nuevo',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_consultas_creado_en (creado_en),
  KEY idx_consultas_estado (estado),
  KEY idx_consultas_interes (interes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contador privado de visitas
-- No guarda direcciones IP. visitor_hash identifica de forma anónima el navegador.
CREATE TABLE IF NOT EXISTS visitas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  visitor_hash CHAR(64) NOT NULL,
  pagina VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_visitas_creado_en (creado_en),
  KEY idx_visitas_visitor_hash (visitor_hash),
  KEY idx_visitas_pagina (pagina)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
