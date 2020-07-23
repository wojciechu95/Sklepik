<?php
require_once 'header.php';

    if ($session-> getUser() -> isAnonymous()){
        $result = user::checkPassword(addslashes($_POST['login']), $_POST['password']);

        if ($result instanceof user){
            $session-> updateSession($result);
        }

        header('Location: admin.php');

    }

require_once 'footer.php';
?>