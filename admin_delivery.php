<?php
header("Content-Type: application/json");

// DATABASE CONNECTION
$connection = mysqli_connect("localhost", "root", "", "my_database");
if (!$connection) {
    echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
    exit;
}

// HANDLE GET REQUEST (Fetch Only Delivered Orders)
$query = "SELECT id, name, mobile, cost, item, history 
          FROM order_history 
          WHERE LOWER(history) = 'delivered'
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
        "message" => "No delivered orders found"
    ]);
}

mysqli_close($connection);
?>
