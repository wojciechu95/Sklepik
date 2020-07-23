<?php
require_once 'header.php';

if (preg_match('#^[0-9]{1,5}$#is', trim($_GET['id']))) {

    $id = $_GET['id'];

    $cart->rem($id);
    header("Location: showcart.php");
}
require_once 'footer.php';
?>