<?php
require 'config.php';

$campanha = $_GET['campanha'] ?? 'desconhecida';
$data = $_GET['data'] ?? date('Y-m-d');

$stmt = $pdo->prepare("INSERT INTO aberturas (campanha, data_campanha) VALUES (:campanha, :data)");
$stmt->execute(['campanha' => $campanha, 'data' => $data]);

header('Content-Type: image/png');
readfile('transparent.png');
?>