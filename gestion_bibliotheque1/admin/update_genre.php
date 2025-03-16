<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_genre = $_GET['id_genre']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM genres WHERE id_genre = ?");
$stmt->execute([$id_genre]);
$genre = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_genre = $_POST['nom_genre'];

    $stmt = $pdo->prepare("UPDATE genres SET nom = ? WHERE id_genre = ?");
    $stmt->execute([$nom_genre, $id_genre]);

    echo "Genre mis à jour avec succès!";
    header("Location: manage_genres.php");
    exit();
}
?>

<div class="container mt-5">
    <h2>Modifier le Genre</h2>
    <form action="update_genre.php?id_genre=<?php echo $genre['id_genre']; ?>" method="POST">
        <label for="nom_genre">Nom du Genre</label>
        <input type="text" name="nom_genre" value="<?php echo htmlspecialchars($genre['nom']); ?>" required><br>
        
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
