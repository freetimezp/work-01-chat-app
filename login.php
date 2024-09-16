<?php

session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    $query = "SELECT * FROM managers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $manager = $result->fetch_assoc();

    //var_dump($password);

    if ($manager && password_verify($password, $manager['password'])) {
        $_SESSION['manager_id'] = $manager['id'];
        $_SESSION['manager_email'] = $manager['email'];
        $_SESSION['topic'] = $manager['topic'];
        //print_r($_SESSION);

        //redirect to main page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SESSION['url']);
        //exit;
    } else {
        //echo "Invalid login!";
    }
}
