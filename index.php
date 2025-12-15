<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three Beans Soy Desserts</title>
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
        <div class="hero">
            <h1 style="color: var(--accent-color);">Three Beans Soy Desserts</h1>
            <p>Handcrafted with love, using the finest ingredients for your delight.</p>
            <br>
            <a href="menu.php" class="btn">Browse Menu</a>
        </div>

        <div style="text-align: center; margin-top: 60px;">
            <h2 style="font-size: 2rem;">Why Choose Us?</h2>
            <div style="display: flex; justify-content: center; gap: 40px; margin-top: 30px; flex-wrap: wrap;">
                <div style="max-width: 300px;">
                    <h3 style="color: var(--accent-color);">Fresh Ingredients</h3>
                    <p>We source locally and use only the freshest soybean, toppings and fruits.</p>
                </div>
                <div style="max-width: 300px;">
                    <h3 style="color: var(--accent-color);">Artisan Made</h3>
                    <p>Every desserts is made with passion by our expert pastry chefs.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>