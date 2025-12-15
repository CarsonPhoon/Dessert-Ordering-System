<?php
session_start();
include 'db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: menu.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Three Beans</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link rel="icon" type="image/png" href="images/logo.png">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="about.php">About Us</a>
            <a href="cart.php">My Cart</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <div class="container">
        <div class="login-box">
            <h1 class="login-title">Admin Login</h1>

            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" required class="login-input">
                </div>

                <div class="form-group-large">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required class="login-input">
                </div>

                <button type="submit" name="login" class="btn btn-full">Login</button>
            </form>

            <p class="login-footer">
                Customers can order without logging in!<br>
                <a href="menu.php">Browse Menu →</a>
            </p>
        </div>
    </div>
</body>

</html>