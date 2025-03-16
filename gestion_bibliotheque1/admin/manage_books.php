<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM livres");
$stmt->execute();
$books = $stmt->fetchAll();
?>

<?php include('../includes/admin_header.php'); ?>

<div class="container mt-5">
    <h2>Gérer les Livres</h2>
    <div class="row">
        <?php foreach ($books as $book): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <?php if ($book['image_url']): ?>
                        <img src="<?= htmlspecialchars($book['image_url']); ?>" class="card-img-top" alt="Book Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($book['titre']) ?></h5>
                        <p class="card-text"><strong>Auteur:</strong> <?= htmlspecialchars($book['auteur']) ?></p>
                        <p class="card-text"><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
                        <p class="card-text"><strong>Stock:</strong> <?= $book['stock'] ?></p>
                        <div class="d-flex justify-content-between">
                            <a href="update_book.php?id_livre=<?= $book['id_livre'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="delete_book.php?id_livre=<?= $book['id_livre'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
