<?php
require_once '../includes/session_check.php'; 

$pdo = cnx(); 
$user_id = $_SESSION['user_id']; 

$stmt_reservations = $pdo->prepare("
    SELECT r.id_reservation, r.date_reservation, r.status, l.titre 
    FROM reservations r 
    JOIN livres l ON r.id_livre = l.id_livre 
    WHERE r.id_membre = ?
    ORDER BY r.date_reservation DESC
");
$stmt_reservations->execute([$user_id]);
$reservations = $stmt_reservations->fetchAll();

$stmt_emprunts = $pdo->prepare("
    SELECT e.id_emprunt, e.date_emprunt, e.date_retour, l.titre 
    FROM emprunts e 
    JOIN livres l ON e.id_livre = l.id_livre 
    WHERE e.id_membre = ?
    ORDER BY e.date_emprunt DESC
");
$stmt_emprunts->execute([$user_id]);
$emprunts = $stmt_emprunts->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des emprunts et réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

body {
            background-image: url('../includes/images/back.jpg');
            background-size: cover; /* Ensure the image covers the entire background */
            background-position: center; /* Center the background image */
            font-family: Arial, sans-serif;
            color: white;
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
        

        /* Enhanced Buttons */
        .btn-details {
            background: linear-gradient(45deg,rgb(33, 243, 14), #00b4ff);
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 30px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-details:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Card-based Table Styling */
        .card-table {
            box-shadow: 0 4px 8px rgba(210, 13, 13, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            background-color: white;
        }

        .card-table-header {
            background: #007bff;
            color: white;
            padding: 10px;
            font-weight: bold;
        }

        .card-table-body {
            padding: 15px;
        }

        .container h2 {
            color: white;
            text-align: center;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        /* Status Indicators */
        .badge {
            font-size: 14px;
            padding: 5px 10px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            margin: 20px 0;
        }

        .empty-state img {
            width: 100px;
            margin-bottom: 10px;
        }

        .dot {
            height: 12px;
            width: 12px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div>
        <?php include('../includes/header.php'); ?>
    </div>

    <div class="container mt-5">
        

        <h2>Historique des emprunts et réservations</h2>

        <section class="mt-4">
            
            <?php if (!empty($reservations)): ?>
                <div class="card-table">
                    <div class="card-table-header">Réservations</div>
                    <div class="card-table-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de Réservation</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $reservation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['date_reservation']); ?></td>
                                        <td>
                                            <?php 
                                                if ($reservation['status'] === 'pending') {
                                                    echo '<span class="badge bg-warning text-dark">En attente</span>';
                                                } elseif ($reservation['status'] === 'approved') {
                                                    echo '<span class="badge bg-success">Approuvée</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">Annulée</span>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <img src="path/to/empty-state-icon.svg" alt="No data">
                    <p class="text-muted">Aucune réservation trouvée.</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="mt-5">
            
            <?php if (!empty($emprunts)): ?>
                <div class="card-table">
                    <div class="card-table-header">Emprunts</div>
                    <div class="card-table-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date d'emprunt</th>
                                    <th>Date de retour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprunts as $emprunt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($emprunt['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($emprunt['date_emprunt']); ?></td>
                                        <td>
                                            <?php 
                                                echo $emprunt['date_retour'] ? 
                                                    htmlspecialchars($emprunt['date_retour']) : 
                                                    '<span class="text-danger">Non retourné</span>';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <img src="path/to/empty-state-icon.svg" alt="No data">
                    <p class="text-muted">Aucun emprunt trouvé.</p>
                </div>
            <?php endif; ?>
        </section>
        
        <a href="review.php" class="btn-details mt-4">Laisser un commentaire</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
