<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Części</title>

    <link href="css/style.css" rel="stylesheet" media="screen">
    <link href="css/lightbox.css" rel="stylesheet">
    <link href="css/fontello.css" rel="stylesheet" media="screen">

    <script src="js/jquery-3.2.1.js" type="text/javascript"></script>

</head>
<body>
<main>
    <section class="container">
<?php
session_start();

require_once 'functions.php';
require_once 'userRequest.php';
require_once 'user.php';
require_once 'sessions.php';
require 'cart.php';

    $pdo = new PDO('mysql:host=localhost;port3306;dbname=sklep', 'root', '');

        $pdo-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo-> exec("SET NAMES 'utf8'");

        $request = new userRequest;
        $session = new sessions;
        $cart = new cart;

        echo "\t\t" . '<header>' .PHP_EOL;

            showHeader();

        echo "\t\t" . '</header>' .PHP_EOL;

        echo "\t\t" . '<aside>' .PHP_EOL;

            showMenu();

        echo "\t\t" . '</aside>' .PHP_EOL;

        echo "\t\t" . '<section class="content">' .PHP_EOL;
?>