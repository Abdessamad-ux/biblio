<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
$pdo = cnx();

$id_livre = $_GET['id_livre']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$book = $stmt->fetch();

if ($book) {
    echo "<div>";
    echo "<h3>" . htmlspecialchars($book['titre']) . "</h3>";
    echo "<p>Auteur: " . htmlspecialchars($book['auteur']) . "</p>";
    echo "<p>Genre: " . htmlspecialchars($book['genre']) . "</p>";
    echo "<p>Année de publication: " . $book['annee_publication'] . "</p>";
    echo "<p>Stock: " . $book['stock'] . "</p>";
    echo "</div>";
} else {
    echo "Livre non trouvé.";
}

$stmt = $pdo->prepare("SELECT commentaire, note, nom FROM avis 
                        JOIN membres ON avis.id_membre = membres.id_membre 
                        WHERE avis.id_livre = ?");
$stmt->execute([$id_livre]);
$reviews = $stmt->fetchAll();

if ($reviews) {
    echo "<h4>Avis des utilisateurs:</h4>";
    foreach ($reviews as $review) {
        echo "<div>";
        echo "<h5>" . htmlspecialchars($review['nom']) . " (" . $review['note'] . "/5)</h5>";
        echo "<p>" . htmlspecialchars($review['commentaire']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Aucun avis disponible pour ce livre.</p>";
}

if (isset($_SESSION['user_id'])) {
    echo "<h4>Ajouter un avis:</h4>";
    echo "<form action='review.php' method='POST'>
            <input type='hidden' name='id_livre' value='" . $id_livre . "'>
            <textarea name='commentaire' placeholder='Écrire un commentaire' required></textarea><br>
            <select name='note'>
                <option value='1'>1</option>
                <option value='2'>2</option>
                <option value='3'>3</option>
                <option value='4'>4</option>
                <option value='5'>5</option>
            </select><br>
            <button type='submit' class='btn btn-primary'>Soumettre</button>
        </form>";
} else {
    echo "<p>Veuillez vous connecter pour ajouter un avis.</p>";
}

?>
