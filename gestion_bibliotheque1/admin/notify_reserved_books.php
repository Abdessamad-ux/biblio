<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT reservations.id_reservation, membres.nom AS member_name, membres.email AS member_email, livres.titre AS book_title
                       FROM reservations
                       JOIN livres ON reservations.id_livre = livres.id_livre
                       JOIN membres ON reservations.id_membre = membres.id_membre
                       WHERE livres.stock > 0 AND reservations.notified = 0");
$stmt->execute();
$reservations = $stmt->fetchAll();

foreach ($reservations as $reservation) {
    $to = $reservation['member_email'];
    $subject = "Votre réservation est disponible!";
    $message = "Bonjour " . $reservation['member_name'] . ",\n\nLe livre que vous avez réservé, '" . $reservation['book_title'] . "', est maintenant disponible. Vous pouvez venir le récupérer à la bibliothèque.\n\nMerci!";
    $headers = "From: admin@bibliotheque.com";

    if (mail($to, $subject, $message, $headers)) {
        $stmt_update = $pdo->prepare("UPDATE reservations SET notified = 1 WHERE id_reservation = ?");
        $stmt_update->execute([$reservation['id_reservation']]);
        echo "Notification envoyée à " . htmlspecialchars($reservation['member_name']) . " pour le livre '" . htmlspecialchars($reservation['book_title']) . "'.<br>";
    } else {
        echo "Erreur lors de l'envoi de l'email pour " . htmlspecialchars($reservation['member_name']) . ".<br>";
    }
}
?>
