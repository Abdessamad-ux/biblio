<?php




session_start();
if (!isset($_SESSION['is_admin'])) {
    echo "Welcome, Admin!";
} else {
    echo "Access denied. Admin only.";
}







?>