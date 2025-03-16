<?php

require_once 'includes/database.php';


$conn = cnx();


$query = "SELECT id_livre, titre, auteur, genre, annee_publication, stock, image_url 
          FROM livres 
          ORDER BY id_livre DESC 
          LIMIT 10";
$stmt = $conn->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Latest Books</h1>
        <?php if (count($books) > 0): ?>
            <div class="row">
                <?php foreach ($books as $book): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            
                            <img src="uploads/<?= htmlspecialchars(basename($book['image_url'])) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['titre']) ?>">

                            <div class="card-body">
                                
                                <h5 class="card-title"><?= htmlspecialchars($book['titre']) ?></h5>
                                
                                
                                <p class="card-text mt-2">Genre: <?= htmlspecialchars($book['genre']) ?></p>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No books have been added yet.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
