<?php
require_once '../includes/database.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ? OR genre LIKE ?");
$stmt->execute(["%$query%", "%$query%", "%$query%"]);
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        body {
            background-image: url('../includes/images/back.jpg');
            background-size: cover; /* Ensure the image covers the entire background */
            background-position: center; /* Center the background image */
            font-family: Arial, sans-serif;
        }

        .container {
    background-color: rgba(205, 26, 26, 0.7); /* Semi-transparent background */
    border: 1px solid rgb(230, 222, 222);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px); /* Apply the blur effect */
    -webkit-backdrop-filter: blur(10px); /* For Safari compatibility */
    width: 90%; /* Adjust the width to your preference */
    max-width: 1200px; /* Optional: limits the maximum width */
    margin: 0 auto; /* Centers the container */
}

       /* video
       body {
        margin: 0;
        padding: 0;
        overflow: hidden; 
    }

video#background-video {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; 
    z-index: -1; 
}

.container {
    background-color: rgba(205, 26, 26, 0.7); 
    border: 1px solid rgb(230, 222, 222);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px); 
    -webkit-backdrop-filter: blur(10px); 
    width: 90%; 
    max-width: 1200px; 
    margin: 0 auto; 
}
        */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40; /* Dark gray */
        }

        .book-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .book-card h3 {
            margin-bottom: 10px;
            color: #007bff; /* Bootstrap primary blue */
        }

        .book-card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .book-card .btn-group {
            display: flex;
            gap: 10px; /* Space between buttons */
            margin-top: 15px;
        }

        .btn-reserve {
            background-color: #28a745; /* Green for "Réserver" */
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-reserve:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .btn-details {
            background-color: #007bff; /* Blue for "Voir Détails" */
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-details:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .text-danger {
            font-weight: bold;
        }

        /* Custom style for horizontal book layout */
        .book-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .book-col {
            flex: 1 1 20%; /* Each book card will take up 30% of the container */
            
        }
    </style>
</head>
<body>
    <div>
        <?php include('../includes/header.php'); ?>
    </div>

    <div class="container mt-5">
        <form action="browse.php" method="GET" class="d-flex">
            <input type="text" name="query" placeholder="Rechercher des Livres par Titre, Auteur, Genre" class="form-control me-2">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form> <br>

        <?php if ($books): ?>
            <div class="book-row">
                <?php foreach ($books as $book): ?>
                    <div class="book-col">
                        <div class="book-card">
                            <?php if ($book['image_url']): ?>
                                <img src="<?= htmlspecialchars($book['image_url']); ?>" alt="Image du Livre" style="width: 100%; height: auto; border-radius: 10px;">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($book['titre']); ?></h3>
                            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($book['auteur']); ?></p>
                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
                            <p><strong>Stock:</strong> <?php echo $book['stock'] > 0 ? $book['stock'] : "<span class='text-danger'>Indisponible</span>"; ?></p>

                            <div class="btn-group">
                                <?php if ($book['stock'] > 0): ?>
                                    <form action="reserve.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_livre" value="<?php echo $book['id_livre']; ?>">
                                        <button type="submit" class="btn-reserve">Réserver</button>
                                    </form>
                                <?php endif; ?>
                                <button class="btn-details" onclick="showBookDetails(<?php echo $book['id_livre']; ?>)">Voir Avis</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">Aucun livre trouvé pour votre recherche.</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookDetailsModalLabel">Details du Livre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookDetailsContent">
                    <!-- Book details will be loaded here -->
                    <p class="text-center">Chargement...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showBookDetails(idLivre) {
            const modal = new bootstrap.Modal(document.getElementById('bookDetailsModal'));
            const modalBody = document.getElementById('bookDetailsContent');
            modalBody.innerHTML = '<p class="text-center">Chargement...</p>';
            
            fetch(`book_details.php?id_livre=${idLivre}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.show();
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger text-center">Erreur lors du chargement des détails.</p>';
                    console.error(error);
                });
        }
    </script>
</body>
</html>
