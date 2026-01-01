<?php
$connection = mysqli_connect("localhost", "root", "", "my_database");

if (mysqli_connect_errno()) {
    echo json_encode(["success" => false, "message" => "Database not connected"]);
    exit;
}

if(isset($_GET['email'])) {
    $email = $_GET['email'];

    $query = "SELECT name, mobile FROM user_table WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $mobile = $row['mobile'];

        // All orders, history field পাঠাচ্ছি
        $order_query = "SELECT id, history, cost, item 
                        FROM order_history 
                        WHERE name='$name' AND mobile='$mobile'
                        ORDER BY id DESC";

        $order_result = mysqli_query($connection, $order_query);

        if(mysqli_num_rows($order_result) > 0){
            $orders = array();
            while($order = mysqli_fetch_assoc($order_result)){
                $orders[] = $order;
            }

            echo json_encode([
                "success" => true,
                "total" => count($orders),
                "orders" => $orders
            ]);

        } else {
            echo json_encode([
                "success" => false,
                "message" => "No orders found"
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "User not found"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "Email Missing!"
    ]);
}

mysqli_close($connection);
?>
