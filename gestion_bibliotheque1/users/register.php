<?php
require_once '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);

    try {
        $pdo = cnx();
        $sql = "INSERT INTO membres (nom, email, mot_de_passe, telephone, adresse) 
                VALUES (:nom, :email, :password, :telephone, :adresse)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':password' => $password,
            ':telephone' => $telephone,
            ':adresse' => $adresse
        ]);
        $success_message = "Inscription réussie ! Vous pouvez maintenant <a href='../users/login.php'>vous connecter</a>.";
    } catch (Exception $e) {
        $error_message = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .container .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            font-size: 16px;
        }

        .container .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            background-color: #007bff;
            border: none;
        }

        .container .btn-primary:hover {
            background-color: #0056b3;
        }

        .container .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .container .message a {
            color: #007bff;
            text-decoration: none;
        }

        .container .message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <?php if (!empty($success_message)): ?>
            <div class="message alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="message alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="message">
            Déjà un compte ? <a href="../users/login.php">Connectez-vous ici</a>.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
