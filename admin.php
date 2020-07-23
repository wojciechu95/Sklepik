<?php
require_once 'header.php';

    if ($session-> getUser() -> isAnonymous()) {

        echo "\t\t\t" . '<form action="doLogin.php" method="post">
                <p><label>Login:</label> <input type="text" name="login"></p>
                <p><label>Hasło:</label> <input type="password" name="password"></p>
        
                <input type="submit" value="Zaloguj">
            </form>' .PHP_EOL;

    } else {
        if ($session-> getUser()-> isAdmin() ||
            $session-> getUser()-> isUserRoz() ||
            $session-> getUser()-> isUser())
        {
            echo 'Witaj ' . $session-> getUser() -> getLogin() . ' :)
            <br><br><br>
            <ul>
                <li><a href="addProduct.php?add=new">Dodaj nowy Produkt</a></li>
                <li><a href="addProduct.php?add=zasob">Zwiększ zasoby produktów</a></li>
                <li><a href="addCategory.php">Dodaj Kategorię</a> - tylko grupy admin, użytkownikRozszerzony</li>
                <li><a href="addUser.php">Dodaj Użytkownika panelu</a> - tylko admin</li>
            </ul>' .PHP_EOL;
        }
    }
require_once 'footer.php';
?>