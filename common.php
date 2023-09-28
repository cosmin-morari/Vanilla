<?php
require_once("config.php");
require_once("translation.php");

//debugging
function detailsData($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// connect to data base
function connDataBase()
{
    $conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    return $conn;
}
// translate words
function translate($string)
{
    global $words;
    if (array_key_exists($string, $words)) {
        return $words[$string];
    } else {
        echo 'This word has not translation!';
    }
};

// chech if the admin is logged in

function checkAdmin()
{
    if (!$_SESSION['admin']) {
        header('location:login.php');
        exit;
    }
}
