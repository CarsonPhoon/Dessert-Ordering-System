<?php
session_start();
include 'db.php';

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['menu_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = 1;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $qty
        );
    }
    echo "<script>alert('Added to Cart Successful!'); window.location.href='menu.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Three Beans Soy Desserts</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link rel="icon" type="image/png" href="images/logo.png">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="about.php">About Us</a>
            <a href="cart.php">Cart<?php echo isset($_SESSION['cart']) ? ' (' . count($_SESSION['cart']) . ')' :
                                        ''; ?></a>
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
        <div style="text-align:center; margin-bottom: 50px;">
            <h1>Our Signature Menu</h1>
            <p>Curated specifically for your sweet cravings.</p>
        </div>

        <div class="menu-grid">
            <?php
            $sql = "SELECT * FROM menu";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <form method="post" action="menu.php" class="menu-item">
                        <!-- Picture -->
                        <div style="height: 220px; overflow:hidden">
                            <img src="<?php echo !empty($row['image_url']) ? $row['image_url'] :
                                            'https://placehold.co/300x200'; ?>" alt="<?php echo $row['name']; ?>">
                        </div>

                        <!-- Content -->
                        <div style="padding: 20px;">
                            <h3><?php echo $row['name']; ?></h3>
                            <p style="min-height: 50px; font-size:0.9 rem; color: #777;"><?php echo $row['description']; ?></p>
                            <p style="color: var(--accent-color); font-size: 1.2rem; font-weight:bold;">RM <?php echo $row['price']; ?></p>

                            <input type="hidden" name="menu_id" value="<?php echo $row['menu_id']; ?>">
                            <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">

                            <button type="submit" name="add_to_cart" class="btn" style="width: 100%;">Add to Cart</button>
                        </div>
                    </form>
            <?php
                }
            } else {
                echo "<p style='text-align:center; grid-column: 1/-1;'>No items found in database.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>