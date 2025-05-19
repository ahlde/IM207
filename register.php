<?php

require_once 'vendor/autoload.php';

use Geonzon\Dbmodel\Models\User;

session_start();

// Redirect if already logged in
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = new User();
$successMessage = '';

if (isset($_POST['submit'])) {
    $registered = $user->register([
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ]);

    if ($registered) {
        $successMessage = 'You have successfully registered! You may now <a href="login.php">login</a>.';
    } else {
        $successMessage = 'Registration failed. Please try again.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #c9d6ff, #e2e2e2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 360px;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
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
            max-width: 350px;
        }

        input[type="submit"] {
            background-color: #0077ff;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #005fcc;
        }

        .login-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 0;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }

        .message a {
            color: #0077ff;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="POST" action="register.php">
        <h1>Register</h1>
        <?php if (!empty($successMessage)): ?>
            <div class="message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="submit" value="Register">
        <a class="login-btn" href="login.php">Back to Login</a>
    </form>
</body>
</html>
