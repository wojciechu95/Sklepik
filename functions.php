<?php

define('SESSIONE_COOKIE', 'cookiesklep');
define('SESSIONE_ID_LENGHT', 40);
define('SESSIONE_COOKIE_EXPIRE', 3600);


function getProductPicture($kod){
    $images = array();

    for ($i = 0 ; $i < 10 ; $i++){

        $filename = $kod . "-" . $i . ".jpg";
        $filepath = "img/" . $filename;

        if (file_exists($filepath)) $images[] = $filename;
    }

    return $images;
}

function showHeader(){
    global $pdo, $session;

    echo "\t\t\t" . '<div class="userbar">' .PHP_EOL;

        if ($session-> getUser() -> isAnonymous()) {
            echo "\t\t\t\t" . '<a href="admin.php" class="icon-login">Zaloguj do panelu Administratora </a>' .PHP_EOL;
        } else {
            echo "\t\t\t\t" . '<a href="admin.php" class="icon-right-dir">Panel Administratora</a> | - | <a href="logout.php" class="icon-logout">Wyloguj </a>' .PHP_EOL;
        }

    echo "\t\t\t" . '</div>
            <div class="logo">
                <h1>Katalog Części i Akcesoriów motoryzacyjnych</h1>
            </div>' .PHP_EOL;
}

function showMenu(){
    global $pdo, $session;

    $stmt = $pdo->prepare("SELECT * FROM sklep.categories");
    $stmt -> execute();

    echo "\t\t\t" . '<nav>
                <ul>
                    <li><a href="index.php">Strona Główna</a></li>' .PHP_EOL;

    while ($row = $stmt-> fetch(PDO::FETCH_ASSOC)){
        echo "\t\t\t\t\t" . '<li><a href="index.php?cat_id=' . $row['id'] . '">' .
            $row['name'] . '</a></li>' .PHP_EOL;
    }

    echo "\t\t\t\t\t" . '<br><br><br>
                    <li><a href="showcart.php"><i class="icon-cart" style="font-size: 35px"></i></a></li>
                </ul>
            </nav>' .PHP_EOL;
}

function showCategoryadmin($category_id = null){
    global $pdo;

    if ($category_id){
        $stmt = $pdo->prepare("SELECT * FROM sklep.products WHERE category_id = :cid");
        $stmt-> bindValue(":cid", $category_id, PDO::PARAM_INT);
        $stmt-> execute();

        $stmt2 = $pdo->prepare("SELECT * FROM sklep.categories WHERE id = :cid");
        $stmt2-> bindValue(":cid", $category_id, PDO::PARAM_INT);
        $stmt2-> execute();

        $tab = $stmt2-> fetchAll(PDO::FETCH_ASSOC);
        $cat = $tab[0]['name'];

    }   else {
        $stmt = $pdo->prepare("SELECT * FROM sklep.products");
        $stmt-> execute();

        $cat = 'Części i Akcesoraia Motoryzacyjne';
    }

    echo "\t\t\t" . '<div>
                <table>
                     <caption><h1>' . $cat . '</h1></caption>' .PHP_EOL;

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

        $id = $row['id'];
        $kod = $row['kod'];
        $name = $row['name'];
        $producent = $row['producent'];
        $net_price = $row['net_price'];
        $quantity = $row['quantity'];
        $images = getProductPicture($kod);

        $minus = '<a href="minus.php?id=' . $id  . '" style="color: black;"><i class="icon-minus"></i></a>';
        $plus = '<a href="plus.php?id=' . $id . '" style="color: black;"><i class="icon-plus"></i></a>';
        $usun = '<a href="usun.php?id=' . $id . '" style="color: black;"><i class="icon-trash"></i></a>';

        echo "\t\t\t\t\t" . '<tr>
                    <td>';

        if (!empty($images)) $image = $images[0];
        else $image = "no-pic.jpg";

        echo '<img src="img/min/min_' . $image . '"/></td>
                        <td><h4>' . $kod . '</h4></td>
                        <td><h4><a href="product.php?product_id=' . $id . '" class="icon-right-dir">' . $name . '</a></h4>
                        <small>' . $producent . '</small></td>
                        <td><h4>' . $net_price . ' zł netto</h4></td>
                        <td><h5>' . $minus . $quantity . $plus . ' szt./kpt</h5></td>
                        <td><h3>' . $usun . '</h3></td>
                    </tr>' .PHP_EOL;
    }
    echo "\t\t\t\t" . '</table>
            </div>' .PHP_EOL;
}

function showCategory($category_id = null){
    global $pdo;

    if ($category_id){
        $stmt = $pdo->prepare("SELECT * FROM sklep.products WHERE category_id = :cid");
        $stmt-> bindValue(":cid", $category_id, PDO::PARAM_INT);
        $stmt-> execute();

        $stmt2 = $pdo->prepare("SELECT * FROM sklep.categories WHERE id = :cid");
        $stmt2-> bindValue(":cid", $category_id, PDO::PARAM_INT);
        $stmt2-> execute();

        $tab = $stmt2-> fetchAll(PDO::FETCH_ASSOC);
        $cat = $tab[0]['name'];

    }   else {
        $stmt = $pdo->prepare("SELECT * FROM sklep.products");
        $stmt-> execute();

        $cat = 'Części i Akcesoraia Motoryzacyjne';
    }

    echo "\t\t\t" . '<div>
                <table>
                     <caption><h1>' . $cat . '</h1></caption>' .PHP_EOL;

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

        $id = $row['id'];
        $kod = $row['kod'];
        $name = $row['name'];
        $producent = $row['producent'];
        $net_price = $row['net_price'];
        $quantity = $row['quantity'];
        $images = getProductPicture($kod);

        echo "\t\t\t\t\t" . '<tr>
                    <td>';

        if (!empty($images)) $image = $images[0];
        else $image = "no-pic.jpg";

        echo '<img src="img/min/min_' . $image . '"/></td>
                        <td><h4><a href="product.php?product_id=' . $id . '" class="icon-right-dir">' . $name . '</a></h4>
                        <small>' . $producent . '</small></td>
                        <td><h4>' . $net_price . ' zł netto</h4></td>
                        <td><h5>' . $quantity . ' szt./kpt</h5></td>
                    </tr>' .PHP_EOL;
    }
    echo "\t\t\t\t" . '</table>
            </div>' .PHP_EOL;
}

function plus($id, $kod){
    global $pdo;

    $stmt = $pdo-> prepare("SELECT * FROM sklep.products WHERE id = :id AND kod = :kod");

    $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
    $stmt-> bindValue(':kod', $kod, PDO::PARAM_STR);
    $stmt->execute();

    if ($row = $stmt-> fetchAll(PDO::FETCH_ASSOC)){

        $qty = $row[0]['quantity'] + 1;

        $stmt = $pdo-> prepare("UPDATE sklep.products SET quantity = :qty WHERE id = :id
                                                      AND kod = :kod");

        $stmt-> bindValue(':qty', $qty, PDO::PARAM_INT);
        $stmt-> bindValue(':id', $id, PDO::PARAM_STR);
        $stmt-> bindValue(':kod', $kod, PDO::PARAM_INT);
        $stmt->execute();
    }
}

function minus($id, $kod){
    global $pdo;

    $stmt = $pdo-> prepare("SELECT * FROM sklep.products WHERE id = :id
                                                      AND kod = :kod");

    $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
    $stmt-> bindValue(':kod', $kod, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $qty = $row[0]['quantity'];

    $qty--;

    if ($qty >= 0){

        $stmt = $pdo-> prepare("UPDATE sklep.products SET quantity = :qty WHERE id = :id
                                                      AND kod = :kod");

        $stmt-> bindValue(':qty', $qty, PDO::PARAM_INT);
        $stmt-> bindValue(':id', $id, PDO::PARAM_STR);
        $stmt-> bindValue(':kod', $kod, PDO::PARAM_INT);
        $stmt->execute();

    }
}

function usun($id, $kod){
    global $pdo;

    $stmt = $pdo-> prepare("DELETE FROM sklep.products WHERE id = :id 
                                                      AND kod = :kod");

    $stmt-> bindValue(':id', $id, PDO::PARAM_STR);
    $stmt-> bindValue(':kod', $kod, PDO::PARAM_INT);
    $stmt->execute();
}

function random_session_id(){
    $utime = time();
    $id = random_salt(40 - strlen($utime)) . $utime;

    return $id;
}

function random_salt($len){
    return random_text($len);
}

function random_text($len){
    $base = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
    $max =strlen($base) - 1;
    $rstring = '';
    mt_srand((double)microtime()*1000000);

    while (strlen($rstring) < $len) $rstring .= $base[mt_rand(0, $max)];

    return $rstring;
}

?>