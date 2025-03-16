<?php
require_once '../includes/database.php'; // Missing semicolon added here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'], $_POST['telephone'])) {
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];

        // Get the PDO object by calling the cnx() function
        $pdo = cnx();

        // Check if email and phone exist in the database
        $sql = "SELECT * FROM membres WHERE email = ? AND telephone = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $telephone]);
        $user = $stmt->fetch();

        if ($user) {
            ?>
            <form method="POST" action="reset_password.php">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required>
                <input type="hidden" name="user_id" value="<? echo $user['id']; ?>" >
                <button type="submit">Reset Password</button>
            </form>
            <?php
        } else {
            echo "No user found with this email and phone number.";
        }
    } else {
        echo "Email and phone number are required.";
    }
}
?>
