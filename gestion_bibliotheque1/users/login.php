<?php
session_start();
require_once '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = cnx();
        $stmt = $pdo->prepare("SELECT * FROM membres WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id_membre'];
            $_SESSION['user_name'] = $user['nom'];
            header('Location: ../users/profile.php');
            exit();
        } else {
            $error_message = "Identifiants incorrects !";
        }
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
    <title>Connexion</title>
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

        .container .error-message {
            color: #dc3545;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Connexion</h2>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
           
            <div class="message text-center py-3">
                Cree Votre Compte ? <a href="../users/register.php">Inscriee-vous ici</a>.
            </div>

            <div class="message text-center py-3">
                Forget Your Password ? <a href="../users/reset_request.php">FIX It</a>
            </div>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
