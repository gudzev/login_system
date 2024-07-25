<?php

session_start();

if(isset($_SESSION['user_id']) == true)
{
    echo "Login successful.";
}
else
{
    header('location: index.php');
    exit;
}

?>