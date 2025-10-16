<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if (!empty($username) && !empty($_POST['password'])) {
        // Check if username already exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Username already taken.</div>';
        } else {
            // Insert new user
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            
            if ($conn->query($sql)) {
                header("Location: login.php");
                exit();
            } else {
                $message = '<div class="mb-4 p-4 bg-red-300 text-white rounded-lg">Error creating account.</div>';
            }
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
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold text-center text-green-500 mb-6">Create Account</h2>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-2 border-2 border-green-500 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2 border-2 border-green-500 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-green-500 text-white font-semibold py-3 px-6 rounded-lg">
                    Register
                </button>
            </form>
            <p class="text-center mt-4">
                Already have an account? <a href="login.php" class="text-green-500">Login here</a>.
            </p>
        </div>
    </div>
</body>
</html>