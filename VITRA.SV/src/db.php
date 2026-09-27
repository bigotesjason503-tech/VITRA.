<?php
declare(strict_types=1);

function envValue(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

function getDatabaseConnection(): PDO
{
    $host = envValue('DB_HOST');
    $port = envValue('DB_PORT', '3306');
    $name = envValue('DB_NAME', 'vitra');
    $user = envValue('DB_USER');
    $password = envValue('DB_PASSWORD', '');

    if (!$host || !$user || !$name) {
        throw new RuntimeException(
            'Faltan variables de entorno de MySQL. Configura DB_HOST, DB_NAME, DB_USER y DB_PASSWORD.'
        );
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $host,
        $port,
        $name
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 8,
    ];

    // Si tu proveedor de MySQL entrega un certificado CA como archivo,
    // define DB_SSL_CA con la ruta de ese archivo dentro del contenedor.
    $sslCa = envValue('DB_SSL_CA');
    if ($sslCa && defined('PDO::MYSQL_ATTR_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
    }

    $pdo = new PDO($dsn, $user, $password, $options);

    // Ajusta CURRENT_DATE()/CURRENT_TIMESTAMP a la zona horaria deseada.
    $timezone = envValue('DB_TIMEZONE', '-06:00');
    if ($timezone && preg_match('/^[+-](?:0\d|1[0-4]):[0-5]\d$/', $timezone)) {
        $pdo->exec('SET time_zone = ' . $pdo->quote($timezone));
    }

    return $pdo;
}
