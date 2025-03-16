<?php
session_start();
require_once '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $id_livre = $_POST['id_livre'];
    $id_membre = $_SESSION['user_id'];

    $pdo = cnx();
    $stmt = $pdo->prepare("INSERT INTO reservations (id_membre, id_livre, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$id_membre, $id_livre]);

    echo "<p>Réservation effectuée avec succès !</p>";
    header('Location: browse.php'); 
    exit();
} else {
    echo "Erreur : utilisateur non connecté ou données invalides.";
}
?>
