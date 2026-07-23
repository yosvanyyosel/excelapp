-- Estructura de la base de datos para Resultados.com (SQL Puro)
CREATE DATABASE IF NOT EXISTS resultados_db;
USE resultados_db;

CREATE TABLE IF NOT EXISTS test_results (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_surname VARCHAR(255) NOT NULL,
    test_type VARCHAR(50) NOT NULL,
    completed_at TIMESTAMP NOT NULL,
    answers JSON NOT NULL, -- Almacena el array de {number, answer}
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
