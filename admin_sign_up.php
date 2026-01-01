<?php

$connection = mysqli_connect("localhost", "root", "", "my_database");

if (mysqli_connect_errno())
    echo "not connected" . (mysqli_connect_error());
else
    {

    $name = $_GET['name'];
    $email = $_GET['email'];
    $mobile = $_GET['mobile'];
    $password = $_GET['password'];

    if (empty($name) || empty($email) || empty($mobile) || empty($password)) {
        echo "All fields are required!";
        exit();
    }


    $check_sql = "SELECT * FROM admin WHERE email='$email' OR mobile='$mobile'";
    $check_result = mysqli_query($connection, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "User already exists with same email or mobile!";
        exit();
    }

    $sql = "INSERT INTO admin (name,email,mobile,password) VALUES ('$name','$email','$mobile','$password') ";
    $result = mysqli_query($connection, $sql);

    if ($result)
        echo "Sign up Successfully";
}
