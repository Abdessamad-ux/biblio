<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();

$stmt_borrowings = $pdo->prepare("SELECT COUNT(*) AS total_borrowings
                                  FROM emprunts
                                  WHERE MONTH(date_emprunt) = MONTH(CURDATE()) 
                                  AND YEAR(date_emprunt) = YEAR(CURDATE())");
$stmt_borrowings->execute();
$monthly_borrowings = $stmt_borrowings->fetch();

$stmt_reservations = $pdo->prepare("SELECT COUNT(*) AS total_reservations
                                    FROM reservations
                                    WHERE MONTH(date_reservation) = MONTH(CURDATE()) 
                                    AND YEAR(date_reservation) = YEAR(CURDATE())");
$stmt_reservations->execute();
$monthly_reservations = $stmt_reservations->fetch();

$stmt_users = $pdo->prepare("SELECT COUNT(*) AS total_users
                             FROM membres
                             WHERE MONTH(date_creation) = MONTH(CURDATE()) 
                             AND YEAR(date_creation) = YEAR(CURDATE())");
$stmt_users->execute();
$monthly_users = $stmt_users->fetch();

$stmt_books = $pdo->prepare("SELECT livres.titre, COUNT(emprunts.id_livre) AS total
                             FROM emprunts
                             JOIN livres ON emprunts.id_livre = livres.id_livre
                             GROUP BY emprunts.id_livre
                             ORDER BY total DESC
                             LIMIT 10");
$stmt_books->execute();
$most_borrowed_books = $stmt_books->fetchAll();

$titles = [];
$totals = [];

foreach ($most_borrowed_books as $book) {
    $titles[] = htmlspecialchars($book['titre']); 
    $totals[] = $book['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports Combinés</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            
            margin: 0;
            
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .back .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color:rgb(8, 246, 218);
        }
        .report-container, .chart-container {
            margin-bottom: 50px;
        }
        .report {
            padding: 20px;
            background: black;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }
        .report p {
            font-size: 18px;
            margin: 10px 0;
        }
        .back{
            margin: 30px;
            
            background-color:rgba(21, 22, 22, 0.83);
            padding: 15px;
            
        }
    </style>
</head>
<body>
<?php include('../includes/admin_header.php'); ?>
<div class="back">
<div class="container">
    <h2>Rapports Mensuels</h2>
    <div class="report-container">
        <div class="report">
            <p><strong>Total des emprunts ce mois :</strong> <?php echo $monthly_borrowings['total_borrowings']; ?></p>
            <p><strong>Total des réservations ce mois :</strong> <?php echo $monthly_reservations['total_reservations']; ?></p>
            <p><strong>Total des inscriptions ce mois :</strong> <?php echo $monthly_users['total_users']; ?></p>
        </div>
    </div>
    <canvas id="monthlyReportChart" width="400" height="200"></canvas>

    <h2>Livres les plus empruntés</h2>
    <div class="chart-container">
        <canvas id="mostBorrowedBooksChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    const monthlyCtx = document.getElementById('monthlyReportChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Emprunts', 'Réservations', 'Inscriptions'],
            datasets: [{
                label: 'Rapports Mensuels',
                data: [
                    <?php echo $monthly_borrowings['total_borrowings']; ?>, 
                    <?php echo $monthly_reservations['total_reservations']; ?>, 
                    <?php echo $monthly_users['total_users']; ?>
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const booksCtx = document.getElementById('mostBorrowedBooksChart').getContext('2d');
    const booksChart = new Chart(booksCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($titles); ?>,
            datasets: [{
                label: 'Nombre d\'emprunts',
                data: <?php echo json_encode($totals); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: 'Livres' }},
                y: { beginAtZero: true, title: { display: true, text: 'Nombre d\'emprunts' }}
            }
        }
    });
</script>
</div>
</body>
</html>
