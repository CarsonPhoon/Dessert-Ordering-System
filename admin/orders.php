<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur'); 
include '../db.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Delete Order
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Delete order items first (foreign key)
    $conn->query("DELETE FROM order_items WHERE order_id = $id");
    $conn->query("DELETE FROM orders WHERE order_id = $id");
    header("Location: orders.php?msg=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin - Three Beans</title>
    <link rel="stylesheet" href="../app/css/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Orders</a>
            <a href="../logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        </nav>
    </header>

    <div class="container">
        <h1>Customer Orders</h1>
        <p>View and manage all customer orders.</p>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <p style="color: green; background: #e8f5e9; padding: 10px; border-radius: 5px;">Order deleted successfully!</p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php
            $orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
            while ($order = $orders->fetch_assoc()):
                // Get order items
                $items_query = $conn->query("
                    SELECT oi.quantity, m.name 
                    FROM order_items oi 
                    JOIN menu m ON oi.menu_id = m.menu_id 
                    WHERE oi.order_id = {$order['order_id']}
                ");
                $items_list = [];
                while ($item = $items_query->fetch_assoc()) {
                    $items_list[] = $item['name'] . ' x' . $item['quantity'];
                }
            ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td><?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></td>
                    <td><?php echo implode(', ', $items_list) ?: 'No items'; ?></td>
                    <td>RM <?php echo number_format($order['total_price'], 2); ?></td>
                    <td>
                        <a href="orders.php?delete=<?php echo $order['order_id']; ?>"
                            style="color: red;"
                            onclick="return confirm('Delete this order?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($orders->num_rows === 0): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No orders yet.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>

