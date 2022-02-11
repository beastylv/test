<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=test', 'root', 'test12345');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = $_POST['email'] ?? null;

if (!$email) return header('Location: /');

echo '<pre>';
echo var_dump($_POST);
echo '</pre>';