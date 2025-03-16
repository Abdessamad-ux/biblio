<?php
require_once '../includes/session_check.php';
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_membre = $_GET['id_membre']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
$stmt->execute([$id_membre]);
$member = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("UPDATE membres SET nom = ?, email = ?, telephone = ?, adresse = ? WHERE id_membre = ?");
    $stmt->execute([$nom, $email, $telephone, $adresse, $id_membre]);

    header("Location: manage_membres.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les Informations du Membre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Modifier les Informations du Membre</h2>
            <form action="update_member.php?id_membre=<?= htmlspecialchars($member['id_membre']); ?>" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($member['nom']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($member['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($member['telephone']); ?>">
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea class="form-control" id="adresse" name="adresse" rows="3"><?= htmlspecialchars($member['adresse']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>
