<?php
require_once 'header.php';

    if ($session-> getUser() -> isAnonymous()) {
    require 'admin.php';

    } else {
        if ($session-> getUser()-> isAdmin() ||
            $session-> getUser()-> isUserRoz() ||
            $session-> getUser()-> isUser())
        {
            $add = $name = $kod = $producent = $net_price = $quantity = $description = $kategoria = '';

            $add = $_GET['add'];


            $stmt = $pdo->prepare("SELECT * FROM sklep.categories");
            $stmt -> execute();

            if (($add == 'new') && preg_match('#^[new]{3}$#is', $add))
            {
                if (isset($_SESSION['name']) || isset($_SESSION['kod']) || isset($_SESSION['producent']) ||
                    isset($_SESSION['net_price']) || isset($_SESSION['qty']) || isset($_SESSION['des']) ||
                    isset($_SESSION['cat']))
                {
                    $name = $_SESSION['name'];
                    $kod = $_SESSION['kod'];
                    $producent = $_SESSION['producent'];
                    $net_price = $_SESSION['net_price'];
                    $quantity = $_SESSION['qty'];
                    $description = $_SESSION['des'];
                    $kategoria = $_SESSION['cat'];

                        unset($_SESSION['name']);
                        unset($_SESSION['kod']);
                        unset($_SESSION['producent']);
                        unset($_SESSION['net_price']);
                        unset($_SESSION['qty']);
                        unset($_SESSION['des']);
                        unset($_SESSION['cat']);
                }


                echo '
        <div class="kol">    
            <form action="doAddProduct.php" method="post">
                <div class="kol1">
                    <p><label>Kod Produktu:</label> <input type="text" name="kod" value="' . $kod . '" required></p>
                    <p><label>Producent:</label> <input type="text" name="manufacturer" value="' . $producent . '" required></p>
                    <p><label>Nazwa:</label> <input type="text" name="name" value="' . $name . '" required></p>
                </div>
                <div class="kol2">    
                    <p><label>Cena Netto:</label> <input type="text" name="net_price" value="' . $net_price . '" required></p>
                    <p><label>Ilość szt./kpt:</label> <input type="number" name="quantity" min="1" step="1" value="1" value="' . $quantity . '" required></p>
                    <p><label>Kategoria:</label> <select name="kategoria" value="' . $kategoria . '" required>
                        <option selected disabled>- Wybierz Kat. -</option>' .PHP_EOL;

                    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                            echo "\t\t\t\t\t".'<option value="' . $row['id'] . '">' . $row['name'] . '</option>' . PHP_EOL;
                    }

                    echo '                </select></p>
                </div>
                <div class="kol" style="margin-left: 100px">
                    <p><label style="width: 75%">Opis:</label> <textarea name="description" rows="7" style="width: 75%" required>' . $description . '</textarea></p> 
                </div>   
                <input type="submit" name="dodaj" value="Dodaj">
            </form>
        </div>' .PHP_EOL;
            }

            if (($add == 'zasob') && preg_match('#^[zasob]{5}$#is', $add))
            {
                echo "\t\t\t" . '<form method="post">
                        <p><label>Kategoria:</label> <select name="cat_id" required>
                        <option selected disabled>- Wybierz Kat. -</option>' .PHP_EOL;

                    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                            echo "\t\t\t\t\t".'<option value="' . $row['id'] . '">' . $row['name'] . '</option>' . PHP_EOL;
                    }

                echo "\t\t\t\t" . '</select></p>
                <button >Pokaż</button>
            </form>' .PHP_EOL;

                if (isset($_POST['cat_id'])) $category_id = $_POST['cat_id'];
                else $category_id = null;

                showCategoryadmin($category_id);

            }
        }
    }
require_once 'footer.php';
?>