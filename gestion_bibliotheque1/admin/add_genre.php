<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_genre = $_POST['nom_genre'];

    $pdo = cnx();
    $stmt = $pdo->prepare("INSERT INTO genres (nom) VALUES (?)");
    $stmt->execute([$nom_genre]);

    echo "Genre ajouté avec succès!";
    header("Location: manage_genres.php"); 
    exit();
}
?>

<div class="container mt-5">
    <h2>Ajouter un Genre</h2>
    <form action="add_genre.php" method="POST">
        <label for="nom_genre">Nom du Genre</label>
        <input type="text" name="nom_genre" required><br>
        
        <button type="submit" class="btn btn-primary">Ajouter Genre</button>
    </form>
</div>
