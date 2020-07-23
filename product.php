<?php
require_once 'header.php';

function showProduct($id){
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM sklep.products WHERE id = :id");
    $stmt-> bindValue(":id", $id, PDO::PARAM_INT);
    $stmt-> execute();

    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

        $kod = $row['kod'];
        $name = $row['name'];
        $id = $row['id'];

        echo "\t\t".'<div>
            <h2>' . $name . '</h2>
            <h3>Cena netto: ' . $row['net_price'] . ' zł</h3>' .PHP_EOL;

        foreach (getProductPicture($kod) as $images){
            echo "\t\t\t".'<a href="img/' . $images . '" data-lightbox="' . $kod .
                '" data-title="' . $kod . ' - ' . $name . '"><img src=img/thumbs/min_' . $images .
                ' alt="' . $name . '"/></a>' .PHP_EOL;
        }

        echo "\t\t\t<p>" . $row['description'] . '</p>
            <a href="addToCart.php?id=' . $id . '" style="background-color: #c9ffda; padding: 10px">'.
            'Dodaj do koszyka <i class="icon-cart-add" style="color: black;font-size: 25px"></i></a>
        </div>' .PHP_EOL;
    }

}

$id = $_GET['product_id'];

if (isset($_GET['product_id'])) showProduct($id);

require_once 'footer.php';
?>