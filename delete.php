<?php
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];
    
    // Delete the recipe
    $sql = "DELETE FROM recipes WHERE id = $recipe_id AND user_id = {$_SESSION['user_id']}";
    $conn->query($sql);
}

header("Location: index.php");
exit();
?>