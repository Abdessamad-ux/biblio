<?php
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header('Location: ../no_permission.php'); 
    exit();
}

?>