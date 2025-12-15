<?php
session_start();
include 'db.php';

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        if ($qty == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

$order_success = false;
if (isset($_POST['place_order']) && !empty($_SESSION['cart'])) {
    $total_price = $_POST['total_price'];

    $sql_order = "INSERT INTO orders (total_price) VALUES ('$total_price')";
    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id;

        foreach ($_SESSION['cart'] as $menu_id => $item) {
            $quantity = $item['quantity'];
            $sql_item = "INSERT INTO order_items (order_id, menu_id, quantity) VALUES ('$order_id', '$menu_id', '$quantity')";
            $conn->query($sql_item);
        }

        unset($_SESSION['cart']);
        $order_success = true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cart - Dessert Shop</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link rel="icon" type="image/png" href="images/logo.png">
</head>

<body>
    <header>
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="about.php">About Us</a>
        <a href="cart.php">Cart</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin/dashboard.php">Admin</a>
            <a href="logout.php">Logout</a>
        <?php elseif (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </header>

    <div class="container">
        <h1>Your Shopping Cart</h1>

        <?php if ($order_success): ?>
            <div style="text-align: center; padding: 20px; border: 2px solid green;">
                <h2 class="success-msg">Order Placed Successfully! âœ…</h2>
                <p>Thank you for ordering. Your Order ID is #<?php echo $order_id; ?></p>
                <a href="menu.php" class="btn">Order More</a>
            </div>
        <?php else: ?>

            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="post" action="cart.php">
                    <table>
                        <tr>
                            <th>Food Name</th>
                            <th>Price (RM)</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                                </td>
                                <td><?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $id; ?>" style="color: red;">Remove</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                            <td><strong>RM <?php echo number_format($total, 2); ?></strong></td>
                            <td></td>
                        </tr>
                    </table>

                    <div style="text-align: right;">
                        <button type="submit" name="update_cart" class="btn" style="background-color: #555;">Update Cart</button>
                        <input type="hidden" name="total_price" value="<?php echo $total; ?>">
                        <button type="submit" name="place_order" class="btn" style="background-color: green;">Place Order</button>
                    </div>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
                <a href="menu.php" class="btn">Go to Menu</a>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</body>

</html>