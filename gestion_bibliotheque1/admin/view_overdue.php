<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();
$stmt = $pdo->prepare("
    SELECT * FROM emprunts 
    WHERE date_retour IS NULL AND TIMESTAMPDIFF(MINUTE, date_emprunt, NOW()) > 1
");
$stmt->execute();
$overdue_books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprunts en Retard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
        }
        .card {
            border: 1px solid #ddd;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: bold;
        }
        .alert {
            font-size: 1.2rem;
        }
        .text-primary {
            color: #0056b3 !important;
        }
        .text-success {
            color: #198754 !important;
        }
    </style>
</head>
<body>
    <?php include '../includes/admin_header.php'; ?> 
    
    <div class="container">
        <h2 class="text-center mb-4">Emprunts en Retard</h2>
        <?php if (!empty($overdue_books)): ?>
            <div class="row">
                <?php foreach ($overdue_books as $borrow): ?>
                    <?php
                    $stmt_member = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
                    $stmt_member->execute([$borrow['id_membre']]);
                    $member = $stmt_member->fetch();

                    $stmt_book = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
                    $stmt_book->execute([$borrow['id_livre']]);
                    $book = $stmt_book->fetch();

                    $stmt_overdue = $pdo->prepare("
                        SELECT TIMESTAMPDIFF(MINUTE, date_emprunt, NOW()) AS minutes_overdue 
                        FROM emprunts WHERE id_emprunt = ?
                    ");
                    $stmt_overdue->execute([$borrow['id_emprunt']]);
                    $overdue_info = $stmt_overdue->fetch();
                    $minutes_overdue = $overdue_info['minutes_overdue'];

                    $message_content = "Bonjour " . $member['nom'] . ",\n\nVotre emprunt pour le livre '" . $book['titre'] . 
                    "' est en retard de " . $minutes_overdue . 
                    " minutes. Veuillez retourner le livre dès que possible.\n\nMerci!";

                    $stmt_message = $pdo->prepare("
                        INSERT INTO messages (id_membre, message_content) 
                        VALUES (:id_membre, :message_content)
                    ");
                    $stmt_message->execute([ 
                        ':id_membre' => $member['id_membre'],
                        ':message_content' => $message_content
                    ]);
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($member['nom']); ?></h5>
                                <p class="card-text">
                                    <strong>Livre:</strong> <?php echo htmlspecialchars($book['titre']); ?><br>
                                    <strong>Date d'emprunt:</strong> <?php echo htmlspecialchars($borrow['date_emprunt']); ?><br>
                                    <strong>Retard:</strong> <?php echo $minutes_overdue; ?> minutes
                                </p>
                                <p class="text-danger"><strong>Message genere:</strong> Le membre a reçu une alerte pour le retard.</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Aucun emprunt en retard pour le moment.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
