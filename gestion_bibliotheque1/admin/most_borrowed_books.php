<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT livres.titre, COUNT(emprunts.id_livre) AS total
                       FROM emprunts
                       JOIN livres ON emprunts.id_livre = livres.id_livre
                       GROUP BY emprunts.id_livre
                       ORDER BY total DESC
                       LIMIT 10");
$stmt->execute();
$most_borrowed_books = $stmt->fetchAll();

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
    <title>Livres les plus empruntés</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f9f9f9;
        }

        .chart-container {
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

    <h2>Livres les plus empruntés</h2>
    <div class="chart-container">
        <canvas id="mostBorrowedBooksChart" width="400" height="200"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('mostBorrowedBooksChart').getContext('2d');
        const mostBorrowedBooksChart = new Chart(ctx, {
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
                    legend: {
                        display: false, 
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Livres'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'emprunts'
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
