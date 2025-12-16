<?php

echo getenv("APP_NAME");

echo "<h1>🌍 Render + PostgreSQL funcionando</h1>";

$url = getenv("DATABASE_URL");

if (!$url) {
    die("<p style='color:red'>❌ DATABASE_URL no definida</p>");
}

$db = parse_url($url);

$host = $db['host'];
$port = $db['port'] ?? 5432;
$user = $db['user'];
$pass = $db['pass'];
$name = ltrim($db['path'], '/');

/*
  CLAVE: sslmode=require
  Render obliga a SSL en PostgreSQL
*/
$dsn = "pgsql:host=$host;port=$port;dbname=$name;sslmode=require";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "<p style='color:green'>✅ Conectado a PostgreSQL</p>";

    // Crear tabla
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS mensajes (
            id SERIAL PRIMARY KEY,
            texto VARCHAR(255)
        )
    ");

    // Insertar datos si está vacía
    $count = $pdo->query("SELECT COUNT(*) FROM mensajes")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO mensajes (texto) VALUES
            ('Hola desde PostgreSQL'),
            ('Render funciona correctamente'),
            ('2DAW3 en producción 🚀')
        ");
    }

    // Mostrar datos
    echo "<h2>Mensajes:</h2>";
    foreach ($pdo->query("SELECT texto FROM mensajes") as $row) {
        echo "<p>• {$row['texto']}</p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error: {$e->getMessage()}</p>";
}