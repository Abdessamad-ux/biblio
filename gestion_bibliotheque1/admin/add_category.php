<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    $pdo = cnx();
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$category_name]);

    echo "Catégorie ajoutée avec succès!";
    header("Location: manage_categories.php"); 
    exit();
}
?>

<div class="container mt-5">
    <h2>Ajouter une Catégorie</h2>
    <form action="add_category.php" method="POST">
        <label for="category_name">Nom de la Catégorie</label>
        <input type="text" name="category_name" required><br>
        
        <button type="submit" class="btn btn-primary">Ajouter Catégorie</button>
    </form>
</div>
