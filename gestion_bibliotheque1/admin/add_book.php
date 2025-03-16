<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<?php include('../includes/admin_header.php'); ?>

<div class="container mt-5">
    <h2>Ajouter un Livre</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $titre = htmlspecialchars($_POST['titre']);
        $auteur = htmlspecialchars($_POST['auteur']);
        $genre = htmlspecialchars($_POST['genre']);
        $annee_publication = (int) $_POST['annee_publication'];
        $stock = (int) $_POST['stock'];
        $category_id = (int) $_POST['category_id'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $uploadDir = '../admin/uploads';
            $imagePath = $uploadDir . $imageName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, genre, annee_publication, stock, category_id, image_url) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$titre, $auteur, $genre, $annee_publication, $stock, $category_id, $imagePath]);
                echo "<div class='alert alert-success mt-3'>Livre ajouté avec succès!</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Erreur lors de l'upload de l'image!</div>";
            }
        } else {
            echo "<div class='alert alert-danger mt-3'>Veuillez fournir une image valide!</div>";
        }
    }
    ?>
    <form action="add_book.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="auteur" class="form-label">Auteur</label>
            <input type="text" name="auteur" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="annee_publication" class="form-label">Année de publication</label>
            <input type="number" name="annee_publication" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select name="category_id" class="form-control" required>
                <option value="" disabled selected>Choisir une catégorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id_category']); ?>">
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image du Livre</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
