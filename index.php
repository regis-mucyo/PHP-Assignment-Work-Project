<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    if (!empty($title) && !empty($ingredients) && !empty($steps)) {
        $sql = "INSERT INTO recipes (title, ingredients, steps, user_id) VALUES ('$title', '$ingredients', '$steps', $user_id)";
        if ($conn->query($sql)) {
            $_SESSION['message'] = '<div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-400 rounded-lg">Recipe added successfully!</div>';
        } else {
            $_SESSION['message'] = '<div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-400 rounded-lg">Error adding recipe.</div>';
        }
    } else {
        $_SESSION['message'] = '<div class="mb-4 p-4 bg-yellow-100 text-yellow-800 border border-yellow-400 rounded-lg">Please fill in all fields.</div>';
    }
    header("Location: index.php");
    exit();
}
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
<body class="bg-gray-50">
    <div class="min-h-screen">
        <header class="bg-green-500 text-white shadow-lg sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div>
                        <h1 class="text-3xl font-bold">Recipe Manager</h1>
                        <p class="mt-1 text-green-100">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    </div>
                    <a href="logout.php" class="bg-white text-green-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-100 transition duration-300">Logout</a>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <?php 
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Add a New Recipe</h2>
                <form method="POST" action="">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Recipe Title</label>
                                <input type="text" id="title" name="title" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       placeholder="e.g., Chocolate Chip Cookies">
                            </div>
                            <div class="mb-4">
                                <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients (one per line)</label>
                                <textarea id="ingredients" name="ingredients" required rows="8"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                          placeholder="2 cups flour&#10;1 cup sugar&#10;3 eggs"></textarea>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label for="steps" class="block text-sm font-medium text-gray-700 mb-1">Cooking Steps (one per line)</label>
                                <textarea id="steps" name="steps" required rows="12"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                          placeholder="1. Preheat oven to 350°F&#10;2. Mix dry ingredients&#10;3. Add wet ingredients"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition duration-300">
                        Add Recipe
                    </button>
                </form>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">My Recipes (<?php echo $result->num_rows; ?>)</h2>
                
                <?php if ($result->num_rows == 0): ?>
                    <div class="text-center py-16 bg-white rounded-lg shadow-md border border-gray-200">
                        <p class="text-gray-500 text-lg">You haven't added any recipes yet!</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while($recipe = $result->fetch_assoc()): ?>
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                    <span class="text-xs text-gray-500">
                                        Added: <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="p-6 pt-0 flex-grow">
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-green-600 mb-2">Ingredients</h4>
                                        <ul class="list-disc list-inside text-sm text-gray-600 bg-gray-50 rounded p-3 h-32 overflow-y-auto border border-gray-200">
                                            <?php 
                                            $ingredients_list = explode("\n", $recipe['ingredients']);
                                            foreach ($ingredients_list as $ingredient) {
                                                if (trim($ingredient) != '') {
                                                    echo '<li>' . htmlspecialchars(trim($ingredient)) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-green-600 mb-2">Steps</h4>
                                        <ol class="list-decimal list-inside text-sm text-gray-600 bg-gray-50 rounded p-3 h-32 overflow-y-auto border border-gray-200">
                                            <?php 
                                            $steps_list = explode("\n", $recipe['steps']);
                                            foreach ($steps_list as $step) {
                                                if (trim($step) != '') {
                                                    echo '<li>' . htmlspecialchars(trim($step)) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center gap-2 rounded-b-lg">
                                    <a href="edit.php?id=<?php echo $recipe['id']; ?>" class="flex items-center gap-1 text-sm text-blue-500 font-semibold py-1 px-3 rounded-lg hover:bg-blue-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z" /></svg>
                                        Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $recipe['id']; ?>" class="flex items-center gap-1 text-sm text-red-500 font-semibold py-1 px-3 rounded-lg hover:bg-red-100 transition" onclick="return confirm('Are you sure you want to delete this recipe?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Delete
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>