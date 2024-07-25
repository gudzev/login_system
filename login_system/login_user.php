<?php

require_once('config.php');

if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
    die();
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT user_id, password FROM users WHERE username = ?";

$run = $connect -> prepare($sql);
$run -> bind_param("s", $username);
$run -> execute();

$results = $run -> get_result();

// Ako ne pronadje ni jedan red, znaci da username ne postoji
if(!$results -> num_rows == 1)
{
    $_SESSION['error'] = "Wrong username!";

    header('location: index.php');
    exit;
}

$row = $results -> fetch_assoc();

// Ako se dekriptovana sifra iz baze i uneta sifra poklapaju, program ide dalje
if(password_verify($password, $row["password"]) == true)
{
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['success'] = "Login successful.";

    header('location: dashboard.php');
    exit;
}
else
{
    $_SESSION['error'] = "Wrong password!";

    header('location: index.php');
    exit;
}