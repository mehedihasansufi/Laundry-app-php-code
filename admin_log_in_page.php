<?php
$connection = mysqli_connect("localhost", "root", "", "my_database");
if (mysqli_connect_errno()) {
    echo json_encode(array("status" => "error", "message" => "Database not connected"));
    exit();
}

$email = $_GET['email'];
$password = $_GET['password'];

$check_sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
$result = mysqli_query($connection, $check_sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    echo json_encode(array(
        "status" => "found",
        "name" => $row['name'],
        "mobile" => $row['mobile'],
        "address" => isset($row['address']) ? $row['address'] : ""
    ));
} else {
    echo json_encode(array("status" => "not found"));
}
?>
