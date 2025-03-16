<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';
require_once '../admin/admin_verify.php';

$id_livre = $_GET['id_livre']; 
$id_membre = $_GET['id_membre']; 

$pdo = cnx();
$stmt = $pdo->prepare("SELECT stock FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$book = $stmt->fetch();

if ($book && $book['stock'] > 0) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $pdo->prepare("INSERT INTO emprunts (id_livre, id_membre, date_emprunt) VALUES (?, ?, CURDATE())");
        $stmt->execute([$id_livre, $id_membre]);

        $stmt = $pdo->prepare("UPDATE livres SET stock = stock - 1 WHERE id_livre = ?");
        $stmt->execute([$id_livre]);

        echo "Emprunt validé avec succès!";
        header("Location: manage_borrowings.php"); 
        exit();
    }
} else {
    echo "Ce livre n'est pas disponible.";
}
?>

<form action="borrow_book.php?id_livre=<?php echo $id_livre; ?>&id_membre=<?php echo $id_membre; ?>" method="POST">
    <button type="submit" class="btn btn-primary">Valider l'emprunt</button>
</form>
