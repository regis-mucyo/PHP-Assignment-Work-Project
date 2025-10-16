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
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = '<div class="mb-4 p-4 bg-green-500 text-white rounded-lg">Recipe deleted successfully!</div>';
    } else {
        $_SESSION['message'] = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Error deleting recipe.</div>';
    }
}

header("Location: index.php");
exit();
?>