<?php
require_once '../includes/session_check.php';
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$pdo = cnx();

$stmt = $pdo->query("SELECT r.id_reservation, r.date_reservation, r.status, l.titre, m.nom 
    FROM reservations r
    JOIN livres l ON r.id_livre = l.id_livre
    JOIN membres m ON r.id_membre = m.id_membre
    WHERE r.status = 'pending'");
$reservations = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_reservation = $_POST['id_reservation'];
    $action = $_POST['action']; 

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'approved' WHERE id_reservation = ?");
        $stmt->execute([$id_reservation]);

        $stmt_emprunt = $pdo->prepare("INSERT INTO emprunts (id_membre, id_livre)
            SELECT id_membre, id_livre FROM reservations WHERE id_reservation = ?");
        $stmt_emprunt->execute([$id_reservation]);
    } else {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id_reservation = ?");
        $stmt->execute([$id_reservation]);
    }

    header('Location: manage_reservations.php'); 
    exit();
}
?>

<?php include('../includes/admin_header.php'); ?>

<div class="container mt-5">
    <h2>Gestion des Reservations</h2>
    <?php if (!empty($reservations)): ?>
        <div class="row">
            <?php foreach ($reservations as $reservation): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <strong>Utilisateur: <?php echo htmlspecialchars($reservation['nom']); ?></strong>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Livre: <?php echo htmlspecialchars($reservation['titre']); ?></h5>
                            <p class="card-text">
                                <strong>Date de Reservation:</strong> <?php echo htmlspecialchars($reservation['date_reservation']); ?>
                            </p>
                            <div class="d-flex justify-content-between">
                                <form action="" method="POST" style="margin-right: 5px;">
                                    <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approuver</button>
                                </form>
                                <form action="" method="POST">
                                    <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                    <button type="submit" name="action" value="cancel" class="btn btn-danger">Annuler</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-3">Aucune reservation en attente.</div>
    <?php endif; ?>
</div>
