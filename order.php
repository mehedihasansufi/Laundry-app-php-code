<?php
$connection = mysqli_connect("localhost", "root", "", "my_database");

if (mysqli_connect_errno()) {
    echo "not connected" . mysqli_connect_error();
} else {

    // Check all GET values
    if(isset($_GET['email']) && isset($_GET['cost']) && isset($_GET['item'])) {

        $email  = $_GET['email'];
        $cost   = $_GET['cost'];   // <-- তোমার ভুল ছিল $_Get
        $item   = $_GET['item'];   // <-- তোমার ভুল ছিল $_Get

        // user_table থেকে নাম এবং মোবাইল আনবে
        $query = "SELECT name, mobile FROM user_table WHERE email='$email'";
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){

            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $mobile = $row['mobile'];
            $history = 'ordered';

            // order_history তে insert
            $sql = "INSERT INTO order_history (name, mobile, cost, item, history)
                    VALUES ('$name', '$mobile', '$cost', '$item', '$history')";

            if(mysqli_query($connection, $sql)){
                echo "Order Added Successfully";
            } else {
                echo "Insert Failed";
            }

        } else {
            echo "User Not Found";
        }

    } else {
        echo "Missing Parameters";
    }
}
?>
