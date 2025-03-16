<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

include('../includes/admin_header.php');

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM membres");
$stmt->execute();
$members = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            color: white;
            background-color: #f8f9fa; 
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Gestion des Membres</h2>
        <?php if (!empty($members)): ?>
            <div class="row">
                <?php foreach ($members as $member): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($member['nom']); ?></h5>
                                <p><strong>Email:</strong> <?= htmlspecialchars($member['email']); ?></p>
                                <p><strong>Téléphone:</strong> <?= htmlspecialchars($member['telephone']); ?></p>
                                <p><strong>Adresse:</strong> <?= htmlspecialchars($member['adresse']); ?></p>
                                <p><strong>Status:</strong> <?= $member['active'] == 1 ? 'Actif' : 'Désactivé'; ?></p>
                                <div class="action-links">
                                    <a href="update_member.php?id_membre=<?= $member['id_membre']; ?>" class="text-primary">Modifier</a>
                                    <?php if ($member['active'] == 1): ?>
                                        <a href="deactivate_member.php?id_membre=<?= $member['id_membre']; ?>" class="text-danger">Désactiver</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Aucun membre trouvé.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
