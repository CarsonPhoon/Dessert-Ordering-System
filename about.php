<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Three Beans</title>
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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/dashboard.php">Admin</a>
                <a href="logout.php">Logout</a>
            <?php elseif (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="color: var(--accent-color);">Our Story</h1>
            <p style="max-width: 600px; margin: 0 auto;">Making the world sweeter, one soy bean at a time.</p>
        </div>

        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-bottom: 60px;">
            <img src="images/catpudding.jpg"
                onerror="this.src='https://images.unsplash.com/photo-1606312619070-d48b4c652a52?q=80&w=600&auto=format&fit=crop'"
                style="width: 100%; max-width: 500px; border-radius: 15px; box-shadow: var(--shadow);"
                alt="Making Desserts">

            <div style="flex: 1; min-width: 300px; align-self: center;">
                <h2>Heritage & Health</h2>
                <p>At <strong>Three Beans Soy Desserts</strong>, we believe in the power of nature. Our journey began with a simple bowl of Tau Fu Fah, made from premium non-GMO soybeans.</p>
                <p>We insist on traditional stone-grinding techniques to ensure the texture is silky smooth and the aroma is rich. No preservatives and just pure joy.</p>
                <br>
                <a href="menu.php" class="btn">Taste Our Menu</a>
            </div>
        </div>

        <div style="background-color: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow); text-align: center; max-width: 600px; margin: 0 auto;">
            <h2 style="margin-bottom: 30px;">Contact Us</h2>

            <div style="font-size: 1.1rem; line-height: 2;">
                <p><strong>Founder:</strong><br> Jia Le</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

                <p><strong>Email:</strong><br> <a href="mailto: jiale123@gmail.com" style="color: var(--accent-color); text-decoration: none;">carson123@gmail.com</a></p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

                <p><strong>Phone:</strong><br> 012 - 3456789</p>
            </div>
        </div>
    </div>
</body>

</html>