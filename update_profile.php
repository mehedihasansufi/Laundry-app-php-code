<?php
// development-এ সুবিধার জন্য (production-এ বন্ধ করো)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connect — তোমার কনফিগ অনুযায়ী বদলে নাও
$connection = mysqli_connect("localhost", "root", "", "my_database");
if (!$connection) {
    http_response_code(500);
    echo "DB connect failed: " . mysqli_connect_error();
    exit();
}

// Email দরকার (GET/POST/REQUEST যেকোনো)
$email = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : '';

if ($email === '') {
    echo "Error: email is required.";
    exit();
}

// Collect only provided, non-empty fields
$fields = [];
$params = [];
$types = "";

if (isset($_REQUEST['name']) && trim($_REQUEST['name']) !== '') {
    $fields[] = "name = ?";
    $params[] = trim($_REQUEST['name']);
    $types .= "s";
}

if (isset($_REQUEST['mobile']) && trim($_REQUEST['mobile']) !== '') {
    $fields[] = "mobile = ?";
    $params[] = trim($_REQUEST['mobile']);
    $types .= "s";
}

if (isset($_REQUEST['address']) && trim($_REQUEST['address']) !== '') {
    $fields[] = "address = ?";
    $params[] = trim($_REQUEST['address']);
    $types .= "s";
}

if (count($fields) === 0) {
    echo "No fields provided to update. Send at least one of: name, mobile, address.";
    exit();
}

// Build SQL
$set_clause = implode(", ", $fields);
$sql = "UPDATE user_table SET $set_clause WHERE email = ?";

// Prepare
$stmt = mysqli_prepare($connection, $sql);
if (!$stmt) {
    echo "Prepare failed: " . mysqli_error($connection);
    exit();
}

// bind types + email param
$types .= "s";
$params[] = $email;

// mysqli bind_param needs references
$bind_names = [];
$bind_names[] = $types;
for ($i = 0; $i < count($params); $i++) {
    $paramName = 'p' . $i;
    $$paramName = $params[$i];
    $bind_names[] = &$$paramName;
}

// Bind and execute
call_user_func_array([$stmt, 'bind_param'], $bind_names);

if (mysqli_stmt_execute($stmt)) {
    $affected = mysqli_stmt_affected_rows($stmt);
    if ($affected > 0) {
        echo "Profile updated successfully.";
    } else {
        // affected 0 -> either email not found or same values sent
        $check_sql = "SELECT id FROM user_table WHERE email = '" . mysqli_real_escape_string($connection, $email) . "' LIMIT 1";
        $check = mysqli_query($connection, $check_sql);
        if ($check && mysqli_num_rows($check) > 0) {
            echo "No changes made (maybe same values were sent).";
        } else {
            echo "No user found with this email.";
        }
    }
} else {
    echo "Execute failed: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
