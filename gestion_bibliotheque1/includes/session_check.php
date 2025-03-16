<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../users/login.php');
    exit();
}

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['is_admin'] = (int) $user['admin']; 
    $_SESSION['username'] = $user['nom'];       
} else {
    header('Location: ../login.php'); 
    exit();
}
?>
