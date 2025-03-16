<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .main-header {
            padding: 10px 20px; 
        }

        .main-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            width: 50px; 
            height: auto;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .nav-links a {
            margin: 0 15px; 
            color: #007bff; 
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .icons {
            display: flex;
            align-items: center;
            position: relative;
        }

        .icons a {
            margin-right: 15px;
            color: #007bff;
            font-size: 24px;
        }

        .icons i:hover {
            color: #0056b3; 
        }

        .position-absolute {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
        }

        .badge {
            --bs-badge-padding-x: 0.43em;
            --bs-badge-padding-y: 0.12em;
            --bs-badge-font-size: 0.75em;
            --bs-badge-font-weight: 700;
        }

        /* Modal Content Styling */
        .modal-content {
            max-height: 80vh;
           
        }

        /* When modal is opened, darken the background */
        .modal-open-body {
            background-color: black;
        }
    </style>
</head>
<body>
<header class="main-header">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="logo">
            <a href="index.php">
                <img src="../includes/logo.png" alt="Bibliothèque Logo" class="logo-img">
            </a>
        </div>

        <!-- Navigation Links (Centered) -->
        <nav class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../books/browse.php">Browse Books</a>
            <a href="../books/history.php">My History</a>
        </nav>

        <!-- Icons (Messages and Settings) -->
        <div class="icons">
            <!-- Message Icon -->
            <a href="../users/messages.php" id="message-icon" class="position-relative">
                <i class="fa-solid fa-envelope"></i>
                <!-- Notification Badge -->
                <span class="position-absolute badge rounded-pill bg-danger">
                    <!-- Replace '3' with PHP variable for dynamic count -->
                    !
                </span>
            </a>

            <!-- Settings Icon -->
            <a href="../users/profile.php">
                <i class="fa-solid fa-gear"></i>
            </a>
        </div>
    </div>
</header>

<!-- Modal for Messages -->


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
