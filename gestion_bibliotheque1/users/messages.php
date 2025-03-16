<?php
include('../includes/session_check.php');
require_once '../includes/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../users/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos Messages</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            display: flex;
            margin: 0;
            color: white;
            height: 100vh;
            background-image: url('../includes/images/back.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            padding: 25px;
        }

        .container {
    background-color: rgba(205, 26, 26, 0.7);
    border: 1px solid rgb(230, 222, 222);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(10px);
    width: 90%;
    max-width: 950px;
    margin: auto;
    opacity: 0; /* Start hidden */
    transform: scale(0.8); /* Start smaller */
    animation: popUp 1s ease-out forwards; /* Trigger the pop-up animation */
}

        h3 {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 20px;
            border-bottom: 2px solid white;
            padding-bottom: 10px;
            text-align: center;
        }

        .message-container {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            background-color: #ffffff;
            margin-bottom: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .message-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .message-container p {
            font-size: 1rem;
            color: #495057;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .message-container small {
            font-size: 0.85rem;
            color: #6c757d;
            display: block;
            text-align: right;
        }

        .text-muted {
            font-size: 1rem;
            color: white;
            margin-top: 20px;
            text-align: center;
        }

        @keyframes popUp {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h3 {
                font-size: 1.5rem;
            }

            .message-container {
                padding: 10px;
            }

            .message-container p {
                font-size: 0.95rem;
            }

            .message-container small {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- User Messages Section -->
        <h3>Vos Messages</h3>
        <?php
        try {
            // Connect to the database
            $pdo = cnx();
            $stmt = $pdo->prepare("SELECT * FROM messages WHERE id_membre = ? ORDER BY created_at DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($messages) {
                foreach ($messages as $message) {
                    echo "<div class='message-container'>";
                    echo "<p>" . nl2br(htmlspecialchars($message['message_content'])) . "</p>";
                    echo "<small>Reçu le: " . htmlspecialchars($message['created_at']) . "</small>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-muted'>Vous n'avez aucun message pour l'instant.</p>";
            }
        } catch (Exception $e) {
            echo "<p class='text-muted'>Une erreur s'est produite lors de la récupération des messages.</p>";
            error_log($e->getMessage());
        }
        ?>
    </div>



    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.container');
        container.style.animation = 'popUp 0.5s ease-out forwards';
    });
</script>
</body>


</html>
