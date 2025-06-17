<?php
require 'config.php';

$url = $_GET['url'] ?? 'https://jamesmotoshop.com.br/';
$campanha = $_GET['campanha'] ?? 'desconhecida';
$data = $_GET['data'] ?? date('Y-m-d');

$stmt = $pdo->prepare("INSERT INTO conversoes (url, campanha, data_campanha) VALUES (:url, :campanha, :data)");
$stmt->execute(['url' => $url, 'campanha' => $campanha, 'data' => $data]);

header("Location: $url");
exit;
?>