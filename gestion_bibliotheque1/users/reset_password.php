<?php
require_once '../includes/database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_password'], $_POST['user_id'])) {

        
        $new_password = $_POST['new_password'];
        $user_id = $_POST['user_id'];

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $pdo = cnx();

        $update_sql = "UPDATE membres SET mot_de_passe = ? WHERE id_membre = ?";
        $stmt = $pdo->prepare($update_sql);

        try {
            $stmt->execute([$hashed_password, $user_id]);
            echo "Password successfully reset! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            echo "Error updating password: " . $e->getMessage();
        }
    } else {
        echo "Missing new password or user ID.";
    }
}
?>
