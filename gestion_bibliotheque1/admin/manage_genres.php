<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
include '../includes/admin_header.php'; 
require_once '../admin/admin_verify.php';

$pdo = cnx(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_genre'])) {
    $nom_genre = htmlspecialchars($_POST['nom_genre']);
    try {
        $stmt = $pdo->prepare("INSERT INTO genres (nom_genre) VALUES (?)");
        $stmt->execute([$nom_genre]);
        $success_message = "Genre ajouté avec succès.";
    } catch (Exception $e) {
        $error_message = "Erreur : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_genre'])) {
    $id_genre = $_POST['id_genre'];
    $nom_genre = htmlspecialchars($_POST['nom_genre']);
    try {
        $stmt = $pdo->prepare("UPDATE genres SET nom_genre = ? WHERE id_genre = ?");
        $stmt->execute([$nom_genre, $id_genre]);
        $success_message = "Genre modifié avec succès.";
    } catch (Exception $e) {
        $error_message = "Erreur : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_genre'])) {
    $id_genre = $_POST['id_genre'];
    try {
        $stmt = $pdo->prepare("DELETE FROM genres WHERE id_genre = ?");
        $stmt->execute([$id_genre]);
        $success_message = "Genre supprimé avec succès.";
    } catch (Exception $e) {
        $error_message = "Erreur : " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM genres ORDER BY nom_genre ASC");
$genres = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2>Gestion des Genres</h2>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="manage_genres.php" method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="nom_genre" class="form-control" placeholder="Nom du genre" required>
            <button type="submit" name="add_genre" class="btn btn-primary">Ajouter</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Genre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($genres as $genre): ?>
                <tr>
                    <td><?php echo $genre['id_genre']; ?></td>
                    <td><?php echo htmlspecialchars($genre['nom_genre']); ?></td>
                    <td>
                        <form action="manage_genres.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id_genre" value="<?php echo $genre['id_genre']; ?>">
                            <div class="input-group mb-2">
                                <input type="text" name="nom_genre" class="form-control" value="<?php echo htmlspecialchars($genre['nom_genre']); ?>" required>
                                <button type="submit" name="edit_genre" class="btn btn-warning">Modifier</button>
                            </div>
                        </form>

                        <form action="manage_genres.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id_genre" value="<?php echo $genre['id_genre']; ?>">
                            <button type="submit" name="delete_genre" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


