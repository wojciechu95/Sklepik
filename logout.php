<?php
require_once 'header.php';

$session-> updateSession(new user(true));
header('Location: index.php');

require_once 'footer.php';
?>