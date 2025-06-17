<?php
$host = "localhost";
$db = "Nome do banco de dados";
$user = "Usuário do banco de dados";
$pass = "Senha do usuário do banco de dados";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>