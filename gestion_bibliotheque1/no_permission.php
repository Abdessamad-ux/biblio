<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acces Refuse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('includes/images/darkerback.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(22, 20, 20, 0.8);
            backdrop-filter: blur(3px);
            padding: 40px;
            border: 3px solid red;
            border-radius: 10px;
            box-shadow: -1px 0px 55px red;
            text-align: center;
            animation: popup 2s ease-in-out;
        }

        @keyframes popup {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.5;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            color: rgb(238, 12, 35);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .warning-image {
            width: 550px;
            height: auto;
            margin-bottom: 40px;
        }

        .btn-home {
            background-color: rgb(179, 69, 0);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-home:hover {
            background-color: rgb(255, 0, 0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vous n'avez pas la permission d'administrateur <br> pour accéder à cette page.</h1>
        <img src="includes/images/access.png" alt="Warning Icon" class="warning-image">
        <br>
        <a href="users/login.php" class="btn-home">Retour à l'accueil</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
