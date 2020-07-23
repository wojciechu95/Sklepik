<?php
require_once 'header.php';

if ($session-> getUser() -> isAnonymous()) {
    require 'admin.php';

} else {
    if ($session-> getUser()-> isAdmin()){
        echo '
        <div class="kol">    
            <form method="post">
                <div class="kol1">
                    <p><label>Nazwa użytkownika:</label> <input type="text" name="login" required></p>
                    <p><label>Hasło:</label> <input type="password" name="password" required></p>
                    <p><label>Rodzaj konta 1(user roz.), 2(user):</label>'.
                    ' <input type="number" name="rodzaj" value="2" min="1" max="2" step="1" required></p>
                    
                <input type="submit" name="dodaj" value="Dodaj">
                </div>
            </form>
        </div>
        <div style="clear: both"></div>' .PHP_EOL;

        if (isset($_POST['dodaj'])){

            $wszystko_Ok = true;

            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $konto = $_POST['rodzaj'];

            if (!preg_match('#^[\w\d]{2,50}$#is', $login) ||
                !preg_match('#^[1-2]{1}$#is', $konto))
            {
                $wszystko_Ok = false;
            }

            if ($wszystko_Ok)
            {
                $stmt = $pdo-> prepare("SELECT login FROM sklep.users");
                $stmt-> execute();

                $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
                $spr = $row[0]['login'];

                if ($spr == $login){
                    echo '<h2 style="color: red;">Istnieje już użytkownik o loginie: ' . $login . '</h2>';
                } else {

                    $stmt = $pdo->prepare("INSERT INTO sklep.users (id, login, password, konto)
                                                  VALUES (NULL , :login, :password, :konto)");

                    $stmt->bindValue(":login", $login, PDO::PARAM_STR);
                    $stmt->bindValue(":password", $password, PDO::PARAM_STR);
                    $stmt->bindValue(":konto", $konto, PDO::PARAM_INT);
                    $stmt->execute();

                    echo '<h3>Utworzono użytkownika: ' . $login . '</h3>';
                }
            }
        }

    } else {
        echo '<h3>Nie masz uprawnień do dodawania nowych użytkowników panelu</h3>';
    }
}
require_once 'footer.php';
?>