<?php
session_start();
require_once 'vendor/autoload.php';

use Aries\Dbmodel\Models\User;

$userModel = new User();
$message = '';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInfo = $userModel->login([
        'username' => $_POST['username'],
    ]);

    if ($userInfo && password_verify($_POST['password'], $userInfo['password'])) {
        $_SESSION['user'] = $userInfo;
        header('Location: index.php');
        exit;
    } else {
        $message = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 15px;
        }

        form {
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            display: block;
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
            max-width: 350px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #0077ff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #0077ff;
            color: #fff;
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #005fcc;
        }

        .register-btn {
            display: block;
            text-align: center;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .register-btn:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <h1>Login</h1>
        <?php if (!empty($message)): ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
        <a class="register-btn" href="register.php">Don't have an account? Register</a>
    </form>
</body>
</html>
