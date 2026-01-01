<?php
header("Content-Type: application/json");

// DATABASE CONNECTION
$connection = mysqli_connect("localhost", "root", "", "my_database");
if (!$connection) {
    echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
    exit;
}

// HANDLE POST REQUEST (Delivery Button)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $order_id = $_POST['id'];
    $action = $_POST['action'];

    if($action == 'delivery') {
        $update = mysqli_query($connection, "UPDATE order_history SET history='delivered' WHERE id='$order_id'");
        if($update) {
            echo json_encode(["success" => true, "message" => "Order marked as delivered"]);
        } else {
            echo json_encode(["success" => false, "message" => "Update failed: ".mysqli_error($connection)]);
        }
        exit;
    }
}

// HANDLE GET REQUEST (Fetch Only Pending Orders)
$query = "SELECT id, name, mobile, cost, item, history 
          FROM order_history 
          WHERE LOWER(history) = 'pending'
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
        "message" => "No pending orders found"
    ]);
}

mysqli_close($connection);
?>
