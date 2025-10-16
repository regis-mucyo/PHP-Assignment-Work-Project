<?php
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$recipe_id = $_GET['id'];

// Fetch the recipe from the database
$sql = "SELECT * FROM recipes WHERE id = $recipe_id AND user_id = {$_SESSION['user_id']}";
$result = $conn->query($sql);
$recipe = $result->fetch_assoc();

if (!$recipe) {
    // Redirect if recipe doesn't exist or doesn't belong to the user
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    
    if (!empty($title) && !empty($ingredients) && !empty($steps)) {
        // Update the recipe
        $sql = "UPDATE recipes SET title = '$title', ingredients = '$ingredients', steps = '$steps' WHERE id = $recipe_id";
        
        if ($conn->query($sql)) {
            header("Location: index.php"); // Redirect after successful update
            exit();
        } else {
            $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Error updating recipe.</div>';
        }
    } else {
        $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Please fill in all fields.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white border-2 border-green-500 rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-green-500 mb-4">Edit Recipe</h2>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Recipe Title</label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"
                           value="<?php echo $recipe['title']; ?>">
                </div>
                <div class="mb-4">
                    <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">Ingredients</label>
                    <textarea id="ingredients" name="ingredients" required rows="6"
                              class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"><?php echo $recipe['ingredients']; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="steps" class="block text-sm font-medium text-gray-700 mb-2">Cooking Steps</label>
                    <textarea id="steps" name="steps" required rows="6"
                              class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"><?php echo $recipe['steps']; ?></textarea>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white font-semibold py-3 px-6 rounded-lg">
                    Update Recipe
                </button>
            </form>
        </div>
    </div>
</body>
</html>