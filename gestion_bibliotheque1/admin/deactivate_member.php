<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_membre = $_GET['id_membre']; 

$pdo = cnx();
$stmt = $pdo->prepare("UPDATE membres SET active = 0 WHERE id_membre = ?");
$stmt->execute([$id_membre]);

echo "Compte désactivé avec succès!";
header("Location: manage_members.php"); 
exit();
