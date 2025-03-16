<?php

require_once '../includes/session_check.php'; 
require_once '../includes/database.php';

$pdo = cnx();
$user_id = $_SESSION['user_id']; 

$stmt = $pdo->prepare("
    SELECT e.id_livre, l.titre
    FROM emprunts e
    JOIN livres l ON e.id_livre = l.id_livre
    LEFT JOIN avis a ON e.id_livre = a.id_livre AND e.id_membre = a.id_membre
    WHERE e.id_membre = ? AND a.id_avis IS NULL
");
$stmt->execute([$user_id]);
$borrowed_books = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_livre = $_POST['id_livre'];
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = (int) $_POST['note'];

    $stmt = $pdo->prepare("
        INSERT INTO avis (id_livre, id_membre, commentaire, note) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$id_livre, $user_id, $commentaire, $note]);

    $success_message = "Merci pour votre avis !";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donner un avis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Donner un avis sur vos emprunts</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($borrowed_books)): ?>
            <form action="review.php" method="POST">
                <div class="mb-3">
                    <label for="id_livre" class="form-label">Livre</label>
                    <select name="id_livre" id="id_livre" class="form-control" required>
                        <option value="" disabled selected>Choisir un livre</option>
                        <?php foreach ($borrowed_books as $book): ?>
                            <option value="<?php echo $book['id_livre']; ?>">
                                <?php echo htmlspecialchars($book['titre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Note (1 à 5)</label>
                    <select name="note" id="note" class="form-control" required>
                        <option value="" disabled selected>Donner une note</option>
                        <option value="1">1 - Mauvais</option>
                        <option value="2">2 - Passable</option>
                        <option value="3">3 - Moyen</option>
                        <option value="4">4 - Bon</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="commentaire" class="form-label">Commentaire</label>
                    <textarea name="commentaire" id="commentaire" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        <?php else: ?>
            <p class="text-muted">Vous n'avez aucun emprunt en attente d'avis.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
