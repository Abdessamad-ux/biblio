<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
include '../includes/admin_header.php'; 
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2>Gestion des Catégories</h2>
    <a href="add_category.php" class="btn btn-success mb-4">Ajouter une Catégorie</a>

    <div class="list-group">
        <?php foreach ($categories as $category): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span><?php echo htmlspecialchars($category['name']); ?></span>
                <div>
                    <a href="update_category.php?id_category=<?php echo $category['id_category']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="delete_category.php?id_category=<?php echo $category['id_category']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php  // Include footer if it exists ?>
