
<?php
require_once '../includes/session_check.php';
require_once '../admin/admin_verify.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #18283b;
            --sidebar-hover: #2c3e50;
            --sidebar-text: #f5f6fa;
            --main-bg: #f8f9fa;
        }

        body {
            display: flex;
            margin: 0;
            color: white;
            height: 100vh;
            background-image: url('../includes/images/back.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px 5px;
            position: fixed;
            height: calc(100% - 40px);
            margin: 20px;
            border-radius: 25px;
        }

        .sidebar a {
            text-decoration: none;
            color: var(--sidebar-text);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--sidebar-hover);
            border-radius: 15px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 300px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .header {
            
            border-bottom: 1px solid #0d74db;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .settings-icon i {
            font-size: 24px;
            color: #007bff;
            cursor: pointer;
        }

        .header .settings-icon i:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="../index.php"><i class="fas fa-home"></i> Home</a>
        <a href="../admin/add_book.php"><i class="fas fa-plus"></i> Add Book</a>
        <a href="../admin/add_member.php"> <i class="fas fa-user"></i> Add Member</a>
        <a href="../admin/manage_books.php"><i class="fas fa-book"></i> Manage Books</a>
        <a href="../admin/manage_membres.php"><i class="fas fa-users"></i> Manage Members</a>
        <a href="../admin/manage_categories.php"><i class="fas fa-cog"></i> Manage Categories</a>
        <a href="../admin/manage_reservations.php"><i class="fas fa-cog"></i> Manage Reservations</a>
        <a href="../admin/manage_borrowings.php"><i class="fas fa-cog"></i> gérer les emprunts </a>
        <a href="../admin/reports.php"><i class="fas fa-chart-bar"></i> Statistics</a>
        <a href="../admin/view_overdue.php"><i class="fas fa-book-reader"></i> Emprunts en Retard </a>
        <a href="../admin/admin_contact.php"><i class="fas fa-user-cog"></i> Contact Us Messages</a>
        <a href="../users/profile.php"><i class="fas fa-user-cog"></i> Profile</a>
    </div>
    <div class="main-content">
        <header class="header">
            <h1>Admin Dashboard</h1>
            <div class="settings-icon">
                <a href="../admin/profile.php">
                    <i class="fa-solid fa-gear"></i>
                </a>
            </div>
        </header>
