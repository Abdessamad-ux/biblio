<?php
include '../includes/admin_header.php'; 
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM emprunts WHERE date_retour IS NULL");
$stmt->execute();
$borrowings = $stmt->fetchAll();

echo "<div class='container mt-4'>";
echo "<h2 class='mb-4 text-center'>Gestion des Emprunts</h2>";

echo "<div class='card mb-4'>";
echo "<div class='card-header bg-primary text-white'><h3>Emprunts en Cours</h3></div>";
echo "<div class='card-body'>";

if (count($borrowings) > 0) {
    foreach ($borrowings as $borrow) {
        $stmt_member = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
        $stmt_member->execute([$borrow['id_membre']]);
        $member = $stmt_member->fetch();

        $stmt_book = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
        $stmt_book->execute([$borrow['id_livre']]);
        $book = $stmt_book->fetch();

        echo "<div class='border rounded p-3 mb-3'>";
        echo "<p><strong>Nom du membre:</strong> " . htmlspecialchars($member['nom']) . "</p>";
        echo "<p><strong>Livres empruntes:</strong> " . htmlspecialchars($book['titre']) . "</p>";
        echo "<p><strong>Date d'emprunt:</strong> " . htmlspecialchars($borrow['date_emprunt']) . "</p>";
        echo "<a href='return_book.php?id_emprunt=" . $borrow['id_emprunt'] . "' class='btn btn-primary btn-sm '>Valider le retour</a>";
        echo "</div>";
    }
} else {
    echo "<p class='text-muted'>Aucun emprunt en cours.</p>";
}
echo "</div>";
echo "</div>";



$stmt_overdue = $pdo->prepare("SELECT * FROM emprunts WHERE date_retour IS NULL AND DATEDIFF(CURDATE(), date_emprunt) > 30");
$stmt_overdue->execute();
$overdue_books = $stmt_overdue->fetchAll();

if (count($overdue_books) > 0) {
    foreach ($overdue_books as $borrow) {
        $stmt_member = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
        $stmt_member->execute([$borrow['id_membre']]);
        $member = $stmt_member->fetch();

        $stmt_book = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
        $stmt_book->execute([$borrow['id_livre']]);
        $book = $stmt_book->fetch();

        $days_overdue = (new DateTime())->diff(new DateTime($borrow['date_emprunt']))->days;

        echo "<div class='border rounded p-3 mb-3'>";
        echo "<p><strong>Nom du membre:</strong> " . htmlspecialchars($member['nom']) . "</p>";
        echo "<p><strong>Livres empruntés:</strong> " . htmlspecialchars($book['titre']) . "</p>";
        echo "<p><strong>Date d'emprunt:</strong> " . htmlspecialchars($borrow['date_emprunt']) . "</p>";
        echo "<p><strong>Retard de:</strong> " . $days_overdue . " jours</p>";
        echo "</div>";

        $to = $member['email'];
        $subject = "Alerte: Emprunt en retard";
        $message = "Bonjour " . $member['nom'] . ",\n\nVotre emprunt pour le livre " . $book['titre'] . " est en retard de " . $days_overdue . " jours. Veuillez retourner le livre dès que possible.\n\nMerci!";
        $headers = "From: admin@bibliotheque.com";

        mail($to, $subject, $message, $headers);
    }
} else {
    echo "<p class='text-muted'>Aucun livre en retard.</p>";
}
echo "</div>";
echo "</div>";
echo "</div>";
?>
