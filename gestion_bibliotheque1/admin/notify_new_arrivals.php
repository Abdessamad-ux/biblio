<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';

$pdo = cnx();
$stmt_members = $pdo->prepare("SELECT email FROM membres WHERE active = 1");
$stmt_members->execute();
$members = $stmt_members->fetchAll();

$stmt_books = $pdo->prepare("SELECT titre, auteur FROM livres WHERE DATE(date_ajout) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt_books->execute();
$new_books = $stmt_books->fetchAll();

if (count($new_books) > 0) {
    $book_list = "";
    foreach ($new_books as $book) {
        $book_list .= "- " . htmlspecialchars($book['titre']) . " by " . htmlspecialchars($book['auteur']) . "\n";
    }

    foreach ($members as $member) {
        $to = $member['email'];
        $subject = "Nouveautés à la bibliothèque!";
        $message = "Bonjour,\n\nVoici les nouveaux livres ajoutés à la bibliothèque cette semaine:\n\n" . $book_list . "\n\nBonne lecture!";
        $headers = "From: admin@bibliotheque.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Notification envoyée à " . htmlspecialchars($to) . ".<br>";
        } else {
            echo "Erreur lors de l'envoi de l'email pour " . htmlspecialchars($to) . ".<br>";
        }
    }
} else {
    echo "Aucun nouveau livre ajouté cette semaine.";
}
?>
