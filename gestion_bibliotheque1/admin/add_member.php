<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';
?>

<?php include('../includes/admin_header.php'); ?> 

<div class="container mt-5">
    <h2 class="text-center">Ajouter un Nouveau Membre</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $active = isset($_POST['active']) ? 1 : 0; 

        $pdo = cnx();
        $stmt = $pdo->prepare("INSERT INTO membres (nom, email, telephone, adresse, active) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $adresse, $active]);

        echo "<div class='alert alert-success mt-3'>Membre ajouté avec succès!</div>";
    }
    ?>
    <div class="form-container">
        <form action="add_member.php" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse" rows="3"></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="active" name="active" checked>
                <label for="active" class="form-check-label">Activer le Membre</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>
</div>
