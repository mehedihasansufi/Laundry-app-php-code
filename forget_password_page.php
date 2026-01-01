<?php

$connection =mysqli_connect("localhost","root","","my_database");

if(mysqli_connect_errno())
    echo "not connected" . (mysqli_connect_error());
else
    // echo "connected";
{
    $email=$_GET['email'];
    $mobile=$_GET['mobile'];

    $sql="SELECT * FROM user_table WHERE email='$email' AND mobile='$mobile'";
    $result=mysqli_query($connection,$sql);

    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        echo " Your Password: " . $row['password']; 
    }else 
     echo "not found";
}