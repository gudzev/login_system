<?php

require_once('config.php');

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];


    // Provera da li su sifre iste
    if($password != $confirm_password)
    {
    $_SESSION['error'] = "Passwords don't match!";

    header('location: register.php');
    exit;
    }


    // Provera da li sifra ima bar 8 karaktera
    if(strlen($password) <= 7)
    {
    $_SESSION["error"] = "Password must be at least 8 characters long!";

    header('location: register.php');
    exit;
    }

    // Provera da li username ima bar 3 karaktera
    if(strlen($username) <= 2)
    {
    $_SESSION["error"] = "Username must be at least 3 characters long!";
    
    header('location: register.php');
    exit;
    }


    // Provera da li sifra ima bar 1 broj
    if(preg_match('/\d/', $password) == false)
    {
        $_SESSION["error"] = "Password must contain at least 1 number!";

        header('location: register.php');
        exit;
    }

    // Provera da li sifra sadrzi prazno mesto
    if(str_contains($password, ' ') == true)
    {
        $_SESSION["error"] = "Password can't contain an empty space!";

        header('location: register.php');
        exit;
    }

    $sql = "SELECT username FROM users WHERE username = ?";
    $run = $connect -> prepare($sql);
    $run -> bind_param("s", $username);
    $run -> execute();
    $results = $run -> get_result();

    // Provera da li username vec postoji u bazi
    if($results -> num_rows > 0)
    {
        $_SESSION["error"] = "Username like that already exists.";

        header('location: register.php');
        exit;
    }
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(username, password, email) VALUES(?, ?, ?)";

$run = $connect -> prepare($sql);

$run -> bind_param("sss", $username, $hashed_password, $email);

$run -> execute();

$_SESSION['success'] = "Registration successful.";

$connect -> close();
$run -> close();

header('location: register.php');
exit;