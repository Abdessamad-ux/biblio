<?php
include('../includes/session_check.php');
require_once '../includes/database.php';


$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id_membre = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


$upload_dir = 'uploads/';
$allowed_file_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_file_size = 2 * 1024 * 1024; 


if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: url('../includes/images/darkerback.jpg') no-repeat center center/cover;
            height: 200px;
            position: relative;
            border-radius: 10px 10px 0 0;
        }
    </style>
</head>
<body>
    
    <?php include('../includes/header.php'); ?>

    
    <div class="container mt-5">
        <div class="profile-header">
        <img   class="profile-pic">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bienvenue dans votre profil</h2>
            <a href="logout.php" class="btn btn-danger">Se Déconnecter</a>
        </div>

        <!-- User Profile Form -->
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>">
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse" rows="3"><?php echo htmlspecialchars($user['adresse']); ?></textarea>
            </div>

            <!-- Profile Picture Upload -->
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Photo de profil</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                <?php if ($user['profile_picture']): ?>
                    <div class="mt-3">
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-fluid" width="150">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary mb-3">Mettre à jour</button>
        </form>

        <!-- Change Password Form -->
        <h3>Changer le mot de passe</h3>
        <form action="profile.php" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
        </form>

        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if (isset($_POST['nom'])) {
                $nom = $_POST['nom'];
                $email = $_POST['email'];
                $telephone = $_POST['telephone'];
                $adresse = $_POST['adresse'];

                $stmt = $pdo->prepare("UPDATE membres SET nom = ?, email = ?, telephone = ?, adresse = ? WHERE id_membre = ?");
                $stmt->execute([$nom, $email, $telephone, $adresse, $_SESSION['user_id']]);
                echo "<div class='alert alert-success mt-4'>Profil mis à jour avec succès!</div>";
            }

            
            if (isset($_POST['current_password'])) {
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];

                if (!password_verify($current_password, $user['mot_de_passe'])) {
                    echo "<div class='alert alert-danger mt-4'>Mot de passe actuel incorrect!</div>";
                } elseif ($new_password !== $confirm_password) {
                    echo "<div class='alert alert-danger mt-4'>Les nouveaux mots de passe ne correspondent pas!</div>";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE membres SET mot_de_passe = ? WHERE id_membre = ?");
                    $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                    echo "<div class='alert alert-success mt-4'>Mot de passe changé avec succès!</div>";
                }
            }

           
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $file = $_FILES['profile_picture'];
                $file_type = $file['type'];
                $file_size = $file['size'];
                $file_name = $_SESSION['user_id'] . '-' . basename($file['name']);
                $file_path = $upload_dir . $file_name;

                if (!in_array($file_type, $allowed_file_types)) {
                    echo "<div class='alert alert-danger mt-4'>Seuls les fichiers JPEG, PNG ou GIF sont autorisés.</div>";
                }
                elseif ($file_size > $max_file_size) {
                    echo "<div class='alert alert-danger mt-4'>Le fichier est trop volumineux. La taille maximale autorisée est de 2 Mo.</div>";
                } else {
                    if (move_uploaded_file($file['tmp_name'], $file_path)) {
                        $stmt = $pdo->prepare("UPDATE membres SET profile_picture = ? WHERE id_membre = ?");
                        $stmt->execute([$file_name, $_SESSION['user_id']]);
                        echo "<div class='alert alert-success mt-4'>Photo de profil mise à jour avec succès!</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-4'>Erreur lors de l'upload de la photo de profil.</div>";
                    }
                }
            }
        }
        ?>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
