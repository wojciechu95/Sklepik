<?php
require_once 'header.php';

if ($session-> getUser() -> isAnonymous()) {
    require 'admin.php';

} else {
    if ($session-> getUser()-> isAdmin() ||
        $session-> getUser()-> isUserRoz() ||
        $session-> getUser()-> isUser())
    {

        if (isset($_POST['dodaj'])) {
            $wszystko_OK = true;

            $kod = $_POST['kod'];
            $kod = trim($kod);

            $producent = $_POST['manufacturer'];
            $producent = trim($producent);

            $name = $_POST['name'];
            $name = trim($name);

            $net_price = $_POST['net_price'];
            $net_price = trim($net_price);

            $quantity = $_POST['quantity'];
            $quantity = trim($quantity);

            $description = $_POST['description'];
            $description = trim($description);

            $kategoria = $_POST['kategoria'];
            $kategoria = trim($kategoria);

            if (!preg_match('#^[\w\d\040\-\.]{2,50}$#is', $kod) ||
                !preg_match('#^[\w\d\040\-\.]{2,50}$#is', $producent) ||
                !preg_match('#^[0-9\.]{2,9}$#is', $net_price) ||
                !preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,155}$#is', $name) ||
                !preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{5,}$#i', $description) ||
                !preg_match('#^[0-9]{1,5}$#is', $quantity) ||
                !preg_match('#^[0-9]{1,5}$#is', $kategoria)) {
                $wszystko_OK = false;
            }

            if ($wszystko_OK) {

                $stmt = $stmt = $pdo->prepare("INSERT INTO sklep.products (id, kod, producent, name, net_price,  
                                                        quantity, description, category_id) VALUES (NULL , 
                                                        :kod, :prod, :nam, :np, :qty, :des, :cid)");

                $stmt->bindValue(':kod', $kod, PDO::PARAM_INT);
                $stmt->bindValue(':prod', $producent, PDO::PARAM_INT);
                $stmt->bindValue(':nam', $name, PDO::PARAM_INT);
                $stmt->bindValue(':np', $net_price, PDO::PARAM_INT);
                $stmt->bindValue(':qty', $quantity, PDO::PARAM_INT);
                $stmt->bindValue(':des', $description, PDO::PARAM_INT);
                $stmt->bindValue(':cid', $kategoria, PDO::PARAM_INT);
                $stmt->execute();

                echo '<h1>Produkt dodano :)</h1>' . PHP_EOL;
            } else {
                //Zapamiętaj wprowadzone dane
                $_SESSION['name'] = $name;
                $_SESSION['kod'] = $kod;
                $_SESSION['producent'] = $producent;
                $_SESSION['net_price'] = $net_price;
                $_SESSION['qty'] = $quantity;
                $_SESSION['des'] = $description;
                $_SESSION['cat'] = $kategoria;

                header('Location: addProduct.php?add=new');
            }
        }
    } else {
        echo '<h1>Nie masz uprawnień do dodawania produktów do bazy</h1>';
    }
}

require_once 'footer.php';
?>