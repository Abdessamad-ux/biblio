<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_genre = $_GET['id_genre']; 

$pdo = cnx();
$stmt = $pdo->prepare("DELETE FROM genres WHERE id_genre = ?");
$stmt->execute([$id_genre]);

echo "Genre supprimé avec succès!";
header("Location: manage_genres.php"); 
exit();
