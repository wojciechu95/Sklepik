<?php
require_once 'header.php';

if ($session-> getUser() -> isAnonymous()) {
    require 'admin.php';

} else {
    if ($session-> getUser()-> isAdmin() ||
        $session-> getUser()-> isUserRoz()){
            echo '
        <div class="kol">    
            <form method="post">
                <div class="kol1">
                    <p><label>Nazwa kategorii:</label> <input type="text" name="name" required></p>
                <input type="submit" name="dodaj" value="Dodaj">
                </div>
            </form>
        </div>
        <div style="clear: both"></div>' .PHP_EOL;

        if (isset($_POST['dodaj'])){

            $wszystko_Ok = true;

            $kategoria = $_POST['name'];

            if (!preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,80}$#is', $kategoria))
            {
                $wszystko_Ok = false;
            }

            if ($wszystko_Ok)
            {
                $stmt = $pdo-> prepare("SELECT name FROM sklep.categories");
                $stmt-> execute();

                $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
                $spr = $row[0]['name'];

                if ($spr == $kategoria){
                    echo '<h2 style="color: red;">Istnieje już kategoria o nazwie: ' . $kategoria . '</h2>';
                } else {

                    $stmt = $pdo->prepare("INSERT INTO sklep.categories (id, name)
                                                  VALUES (NULL , :name)");

                    $stmt->bindValue(":name", $kategoria, PDO::PARAM_STR);
                    $stmt->execute();

                    echo '<h3>Utworzono użytkownika: ' . $kategoria . '</h3>';
                }
            } else echo '<p>Nie prawidłowa nazwa. Kategoria może zawierać litery A-z w tym polskie znaki, cyfry 0-9, znaki -.'.
                ' oraz być niedłuższa niż 80znaków</p>';
        }
    } else {
        echo '<h3>Nie masz uprawnień do dodawania nowych kategorii</h3>';
    }
}
require_once 'footer.php';
?>