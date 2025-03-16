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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports Mensuels</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

body {
        font-family: 'Arial', sans-serif; 
        background-color: #f4f4f4; 
        margin: 0;
        padding: 0;
        
    }
        

.report-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 30vh; 
        background-color: #f4f4f4;
    }

    .report {
        
        background-image: url('../includes/images/back.jpg');
            background-size: cover;
            background-position: center;
        border: 3px solid white; 
        border-radius: 8px; 
        padding: 20px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        width: 600px; 
        
    }

    .report h2 {
        color:rgb(8, 246, 218); 
        margin-bottom: 20px;
        margin-top: -2px; 
        
    }

    .report p {
        font-size: 17px; 
        margin: 10px 0; 
        color: white; 
    }

    </style>
</head>
<body>
    
    <div class="report-container">
    <div class="report">
        <h2>Rapports Mensuels</h2>
        <p><strong>Total des emprunts ce mois :</strong> <?php echo $monthly_borrowings['total_borrowings']; ?></p>
        <p><strong>Total des réservations ce mois :</strong> <?php echo $monthly_reservations['total_reservations']; ?></p>
        <p><strong>Total des inscriptions ce mois :</strong> <?php echo $monthly_users['total_users']; ?></p>
    </div>
</div>

    <canvas id="monthlyReportChart" width="400" height="200"></canvas>

    <script>
        const ctx = document.getElementById('monthlyReportChart').getContext('2d');
        const chart = new Chart(ctx, {
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
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
