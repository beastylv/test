<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=test', 'root', 'test12345');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = $_POST['email'] ?? null;

if (!$email) {
    header('Location: /');
    exit;
}

$error = 0;

if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email)) $error = 1; //Please provide a valid e-mail address

if (str_ends_with($email, '.co')) $error = 2; //We are not accepting subscriptions from Colombia emails

if ($error !== 0) {
    header("Location: /?er=$error");
    exit;
}

$statement = $pdo->prepare("INSERT INTO emails (email, date) VALUES (:email, :date)");

$statement->bindValue(':email', $email);
$statement->bindValue(':date', date('Y-m-d H:i:s'));

$statement->execute();

header('Location: /success.html');