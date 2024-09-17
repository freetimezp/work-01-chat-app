<?php

session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    //var_dump($user);
    //print_r($_SESSION['url']);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];

        //redirect to main page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        //exit;
    } else {
        //echo "Invalid login!";
    }
}
