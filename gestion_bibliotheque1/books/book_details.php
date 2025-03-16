<?php
require_once '../includes/database.php';
$pdo = cnx();

// Check if book ID is provided
if (isset($_GET['id_livre'])) {
    $id_livre = $_GET['id_livre'];
} else {
    die("ID du livre manquant.");
}

// Fetch book details
$stmt_book = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt_book->execute([$id_livre]);
$book = $stmt_book->fetch();

if (!$book) {
    die("Livre introuvable.");
}

// Fetch reviews for the book
$stmt_reviews = $pdo->prepare("
    SELECT a.commentaire, a.note, m.nom, a.date_avis
    FROM avis a
    JOIN membres m ON a.id_membre = m.id_membre
    WHERE a.id_livre = ?
    ORDER BY a.date_avis DESC
");
$stmt_reviews->execute([$id_livre]);
$reviews = $stmt_reviews->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <div class="container mt-5 design">
        <h2><?php echo htmlspecialchars($book['titre']); ?></h2>
        <p><strong>Auteur :</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
        <p><strong>Description :</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
        
        

        <h3 class="mt-4">Commentaires et Avis</h3>
        <?php if ($reviews): ?>
            <ul class="list-group">
                <?php foreach ($reviews as $review): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($review['nom']); ?></strong> 
                        <span class="badge bg-primary"><?php echo $review['note']; ?>/5</span>
                        <p><?php echo htmlspecialchars($review['commentaire']); ?></p>
                        <small class="text-muted"><?php echo $review['date_avis']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">Aucun avis pour ce livre.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
