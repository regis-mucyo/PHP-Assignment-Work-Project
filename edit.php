<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$recipe_id = $_GET['id'];
$sql = "SELECT * FROM recipes WHERE id = $recipe_id AND user_id = {$_SESSION['user_id']}";
$result = $conn->query($sql);
$recipe = $result->fetch_assoc();
if (!$recipe) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    if (!empty($title) && !empty($ingredients) && !empty($steps)) {
        $sql = "UPDATE recipes SET title = '$title', ingredients = '$ingredients', steps = '$steps' WHERE id = $recipe_id";
        if ($conn->query($sql)) {
            $_SESSION['message'] = '<div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-400 rounded-lg">Recipe updated successfully!</div>';
            header("Location: index.php"); 
            exit();
        } else {
            $_SESSION['message'] = '<div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-400 rounded-lg">Error updating recipe.</div>';
        }
    } else {
        $_SESSION['message'] = '<div class="mb-4 p-4 bg-yellow-100 text-yellow-800 border border-yellow-400 rounded-lg">Please fill in all fields.</div>';
    }
    header("Location: edit.php?id=$recipe_id");
    exit();
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
<body class="bg-gray-50">
    <div class="container mx-auto max-w-4xl py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Edit Recipe</h2>
                <a href="index.php" class="text-sm font-semibold text-green-600 hover:underline">&larr; Back to Recipes</a>
            </div>
            
            <?php 
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Recipe Title</label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                           value="<?php echo htmlspecialchars($recipe['title']); ?>">
                </div>
                <div>
                    <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
                    <textarea id="ingredients" name="ingredients" required rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
                </div>
                <div>
                    <label for="steps" class="block text-sm font-medium text-gray-700 mb-1">Cooking Steps</label>
                    <textarea id="steps" name="steps" required rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><?php echo htmlspecialchars($recipe['steps']); ?></textarea>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition duration-300">
                    Update Recipe
                </button>
            </form>
        </div>
    </div>
</body>
</html>