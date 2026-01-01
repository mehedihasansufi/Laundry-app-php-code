<?php
header("Content-Type: application/json");

// DATABASE CONNECTION
$connection = mysqli_connect("localhost", "root", "", "my_database");
if (!$connection) {
    echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
    exit;
}

// HANDLE POST REQUEST (Accept Button)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'accept') {
    $order_id = $_POST['id'];
    
    $update = mysqli_query($connection, "UPDATE order_history SET history='pending' WHERE id='$order_id' AND LOWER(history)='ordered'");
    
    if ($update) {
        echo json_encode(["success" => true, "message" => "Order status updated to pending"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed: " . mysqli_error($connection)]);
    }
    exit; // Stop further execution
}

// HANDLE GET REQUEST (Fetch Orders)
$query = "SELECT id, name, mobile, cost, item, history 
          FROM order_history 
          WHERE LOWER(history) = 'ordered'
          ORDER BY id DESC";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "SQL Error: " . mysqli_error($connection)]);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    echo json_encode([
        "success" => true,
        "total" => count($orders),
        "orders" => $orders
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No ordered data found"
    ]);
}

mysqli_close($connection);
?>
