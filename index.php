<?php
// Include database connection
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Variable to store success or error messages
$message = '';
$user_id = $_SESSION['user_id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    
    // Check if all fields are filled
    if (!empty($title) && !empty($ingredients) && !empty($steps)) {
        // Prepare SQL query to insert recipe
        $sql = "INSERT INTO recipes (title, ingredients, steps, user_id) VALUES ('$title', '$ingredients', '$steps', $user_id)";
        
        // Execute query
        if ($conn->query($sql)) {
            $message = '<div class="mb-4 p-4 bg-green-500 text-white rounded-lg">Recipe added successfully!</div>';
        } else {
            $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Error adding recipe.</div>';
        }
    } else {
        $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Please fill in all fields.</div>';
    }
}

// Get all recipes from database for the logged in user
$sql = "SELECT * FROM recipes WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="min-h-screen">
        <header class="bg-green-500 text-white shadow-lg">
            <div class="container mx-auto px-4 py-6 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold">Recipe Manager</h1>
                    <p class="mt-2">Welcome, <?php echo $_SESSION['username']; ?>!</p>
                </div>
                <a href="logout.php" class="bg-white text-green-500 font-semibold py-2 px-4 rounded-lg">Logout</a>
            </div>
        </header>

        <div class="container mx-auto px-4 py-8">
            <?php echo $message; ?>

            <div class="bg-white border-2 border-green-500 rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-green-500 mb-4">Add New Recipe</h2>
                <form method="POST" action="">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Recipe Title</label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"
                               placeholder="e.g., Chocolate Chip Cookies">
                    </div>
                    
                    <div class="mb-4">
                        <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-2">Ingredients (one per line)</label>
                        <textarea id="ingredients" name="ingredients" required rows="6"
                                  class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"
                                  placeholder="2 cups flour&#10;1 cup sugar&#10;3 eggs"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="steps" class="block text-sm font-medium text-gray-700 mb-2">Cooking Steps (one per line)</label>
                        <textarea id="steps" name="steps" required rows="6"
                                  class="w-full px-4 py-2 border-2 border-green-500 rounded-lg"
                                  placeholder="1. Preheat oven to 350°F&#10;2. Mix dry ingredients&#10;3. Add wet ingredients"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-500 text-white font-semibold py-3 px-6 rounded-lg">
                        Add Recipe
                    </button>
                </form>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-green-500 mb-6">
                    My Recipes (<?php echo $result->num_rows; ?>)
                </h2>
                
                <?php if ($result->num_rows == 0): ?>
                    <div class="text-center py-12 bg-white border-2 border-green-500 rounded-lg shadow-md">
                        <p class="text-gray-500 text-lg">No recipes yet. Add your first recipe above!</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php while($recipe = $result->fetch_assoc()): ?>
                            <div class="bg-white border-2 border-green-500 rounded-lg shadow-md">
                                <div class="bg-green-500 text-white p-4 flex justify-between items-center">
                                    <h3 class="text-xl font-bold"><?php echo $recipe['title']; ?></h3>
                                    <div>
                                        <a href="edit.php?id=<?php echo $recipe['id']; ?>" class="text-sm bg-white text-green-500 py-1 px-2 rounded-lg mr-2">Edit</a>
                                        <a href="delete.php?id=<?php echo $recipe['id']; ?>" class="text-sm bg-red-500 text-white py-1 px-2 rounded-lg" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-green-500 mb-2">Ingredients</h4>
                                        <div class="text-sm text-gray-600 bg-gray-50 rounded p-3 max-h-40 overflow-y-auto border border-green-500">
                                            <?php 
                                            $ingredients_list = explode("\n", $recipe['ingredients']);
                                            foreach ($ingredients_list as $ingredient) {
                                                if (trim($ingredient) != '') {
                                                    echo '<div class="mb-1">• ' . htmlspecialchars($ingredient) . '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-green-500 mb-2">Steps</h4>
                                        <div class="text-sm text-gray-600 bg-gray-50 rounded p-3 max-h-40 overflow-y-auto border border-green-500">
                                            <?php 
                                            $steps_list = explode("\n", $recipe['steps']);
                                            foreach ($steps_list as $step) {
                                                if (trim($step) != '') {
                                                    echo '<div class="mb-2">' . htmlspecialchars($step) . '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4 border-t border-green-500">
                                        <span class="text-xs text-gray-500">
                                            Added: <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>