<?php
// PHP code remains the same
include 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $message = '<div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-400 rounded-lg">Invalid username or password.</div>';
            }
        } else {
            $message = '<div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-400 rounded-lg">Invalid username or password.</div>';
        }
    } else {
        $message = '<div class="mb-4 p-4 bg-yellow-100 text-yellow-800 border border-yellow-400 rounded-lg">Please fill in all fields.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Recipe Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md">
            <div class="bg-green-500 text-white text-center py-4 rounded-t-lg">
                <h1 class="text-2xl font-bold">Recipe Manager</h1>
            </div>
            <div class="bg-white p-8 rounded-b-lg shadow-lg border border-gray-200">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login to Your Account</h2>
                <?php echo $message; ?>
                <form method="POST" action="" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="username" name="username" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                    </div>
                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition duration-300">
                        Login
                    </button>
                </form>
                <p class="text-center text-sm text-gray-600 mt-6">
                    Don't have an account? <a href="register.php" class="font-semibold text-green-600 hover:underline">Register here</a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>