<?php
session_start();
include '../db.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Helper Function for File Uploads ---
function uploadImage($file)
{
    $target_dir = "../uploads/"; // Directory where images will be stored

    // Check if uploads folder exists, if not, create it
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = basename($file["name"]);
    // Create a unique name to prevent overwriting (e.g., timestamp_filename.jpg)
    $target_file = $target_dir . time() . "_" . $filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');

    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Return the path to store in DB (e.g., "uploads/123_coffee.jpg")
            return "uploads/" . time() . "_" . $filename;
        }
    }
    return null; // Return null if upload failed
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Optional: Delete the actual file from the folder before deleting from DB
    $query = $conn->prepare("SELECT image_url FROM menu WHERE menu_id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $res = $query->get_result();
    if ($row = $res->fetch_assoc()) {
        $file_path = "../" . $row['image_url'];
        if (file_exists($file_path)) {
            unlink($file_path); // Delete the file
        }
    }
    $query->close();

    $stmt = $conn->prepare("DELETE FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?msg=deleted");
    exit();
}

// Handle Add
if (isset($_POST['add_item'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $desc = trim($_POST['description']);

    // Default image if upload fails or none provided
    $img_path = '';

    // Check if image file is uploaded
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $uploaded_path = uploadImage($_FILES['image_file']);
        if ($uploaded_path) {
            $img_path = $uploaded_path;
        }
    }

    $stmt = $conn->prepare("INSERT INTO menu (name, price, description, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $desc, $img_path);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?msg=added");
    exit();
}

// Handle Edit
if (isset($_POST['edit_item'])) {
    $id = intval($_POST['menu_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $desc = trim($_POST['description']);

    // Retrieve current image URL in case the user doesn't upload a new one
    $current_img_path = $_POST['current_image'];
    $new_img_path = $current_img_path;

    // Check if a NEW image file is uploaded
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $uploaded_path = uploadImage($_FILES['image_file']);
        if ($uploaded_path) {
            $new_img_path = $uploaded_path;
            // Optional: Delete old image to save space
            if (!empty($current_img_path) && file_exists("../" . $current_img_path)) {
                unlink("../" . $current_img_path);
            }
        }
    }

    $stmt = $conn->prepare("UPDATE menu SET name=?, price=?, description=?, image_url=? WHERE menu_id=?");
    $stmt->bind_param("sdssi", $name, $price, $desc, $new_img_path, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?msg=updated");
    exit();
}

// Get item for editing
$edit_item = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_item = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Three Beans</title>
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
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your menu items below.</p>

        <?php if (isset($_GET['msg'])): ?>
            <p class="admin-message">
                <?php
                $msgs = ['added' => 'Item added!', 'updated' => 'Item updated!', 'deleted' => 'Item deleted!'];
                echo $msgs[$_GET['msg']] ?? 'Success!';
                ?>
            </p>
        <?php endif; ?>

        <div class="admin-form-container">
            <h2><?php echo $edit_item ? 'Edit Item' : 'Add New Item'; ?></h2>

            <form method="post" action="dashboard.php" enctype="multipart/form-data">
                <?php if ($edit_item): ?>
                    <input type="hidden" name="menu_id" value="<?php echo $edit_item['menu_id']; ?>">
                    <input type="hidden" name="current_image" value="<?php echo $edit_item['image_url']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div>
                        <label class="form-label">Name</label>
                        <input type="text" name="name" required value="<?php echo $edit_item['name'] ?? ''; ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Price (RM)</label>
                        <input type="number" step="0.01" name="price" required value="<?php echo $edit_item['price'] ?? ''; ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Image Upload</label>
                        <input type="file" name="image_file" accept="image/*" class="form-input">
                        <?php if ($edit_item && !empty($edit_item['image_url'])): ?>
                            <small>Current: <a href="../<?php echo $edit_item['image_url']; ?>" target="_blank">View Image</a></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-field-full">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-input"><?php echo $edit_item['description'] ?? ''; ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="<?php echo $edit_item ? 'edit_item' : 'add_item'; ?>" class="btn">
                        <?php echo $edit_item ? 'Update Item' : 'Add Item'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="dashboard.php" class="btn btn-cancel">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h2>All Menu Items</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM menu ORDER BY menu_id DESC");
            while ($row = $result->fetch_assoc()):
                // Check if image starts with http (external) or is local path
                $imgSrc = $row['image_url'];
                if (!empty($imgSrc) && !preg_match("~^(?:f|ht)tps?://~i", $imgSrc)) {
                    $imgSrc = "../" . $imgSrc; // Prepend ../ if it's a local path relative to admin folder
                } elseif (empty($imgSrc)) {
                    $imgSrc = 'https://placehold.co/60x60';
                }
            ?>
                <tr>
                    <td><?php echo $row['menu_id']; ?></td>
                    <td><img src="<?php echo $imgSrc; ?>" class="table-img-thumb" style="max-width:60px; height:auto;"></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>RM <?php echo number_format($row['price'], 2); ?></td>
                    <td class="table-desc-col"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <a href="dashboard.php?edit=<?php echo $row['menu_id']; ?>" class="table-link-edit">Edit</a> |
                        <a href="dashboard.php?delete=<?php echo $row['menu_id']; ?>" class="table-link-delete" onclick="return confirm('Delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>