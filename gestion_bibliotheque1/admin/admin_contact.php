<?php
require_once '../includes/session_check.php'; 
require_once '../includes/database.php';

$pdo = cnx();
$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY date_sent DESC");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        .message-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            background-color: white;
            color: black;
            border-radius: 5px;
        }
        .message-box-header {
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include '../includes/admin_header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center py-2">Contact Messages</h2>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="message-box">
                <div class="message-box-header">
                    <strong><?php echo htmlspecialchars($message['name']); ?></strong> 
                    <small>(<?php echo htmlspecialchars($message['email']); ?>)</small>
                </div>
                <div class="message-box-content">
                    <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                </div>
                <div class="message-box-footer">
                    <small><em>Received on: <?php echo $message['date_sent']; ?></em></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">No messages have been received yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
