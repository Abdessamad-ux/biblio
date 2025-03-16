<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_category = $_GET['id_category']; 

$pdo = cnx();
$stmt = $pdo->prepare("DELETE FROM categories WHERE id_category = ?");
$stmt->execute([$id_category]);

echo "Catégorie supprimée avec succès!";
header("Location: manage_categories.php"); 
exit();
