<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';

$id_emprunt = $_GET['id_emprunt']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM emprunts WHERE id_emprunt = ?");
$stmt->execute([$id_emprunt]);
$borrow = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE emprunts SET date_retour = CURDATE() WHERE id_emprunt = ?");
    $stmt->execute([$id_emprunt]);

    $stmt = $pdo->prepare("UPDATE livres SET stock = stock + 1 WHERE id_livre = ?");
    $stmt->execute([$borrow['id_livre']]);

    echo "Retour validé avec succès!";
    header("Location: manage_borrowings.php"); 
    exit();
}
?>

<form action="return_book.php?id_emprunt=<?php echo $borrow['id_emprunt']; ?>" method="POST">
    <button type="submit" class="btn btn-primary">Valider le retour</button>
</form>
