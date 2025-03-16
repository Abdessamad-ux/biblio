<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_category = $_GET['id_category']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id_category = ?");
$stmt->execute([$id_category]);
$category = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id_category = ?");
    $stmt->execute([$category_name, $id_category]);

    echo "Catégorie mise à jour avec succès!";
    header("Location: manage_categories.php"); 
    exit();
}
?>

<div class="container mt-5">
    <h2>Modifier la Catégorie</h2>
    <form action="update_category.php?id_category=<?php echo $category['id_category']; ?>" method="POST">
        <label for="category_name">Nom de la Catégorie</label>
        <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required><br>
        
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
