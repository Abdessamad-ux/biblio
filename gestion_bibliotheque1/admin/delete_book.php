<?php
require_once '../includes/session_check.php';
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_livre = $_GET['id_livre']; 

$pdo = cnx();
$stmt = $pdo->prepare("DELETE FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);

echo "Livre supprimé avec succès!";
header("Location: manage_books.php"); 
exit();
?>
