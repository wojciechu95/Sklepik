<?php
require_once 'header.php';

$sum = 0;

$inCart = $cart->getProducts();

    echo "\t\t" . '<div>
        <form method="post">
            <table>
             <caption><h2>KOSZYK <i class="icon-bag"></i></h2></caption>
                <thead>
                    <tr>
                        <th>Kod</th>
                        <th><i class="icon-list-1"></i>Nazwa Produktu</th>
                        <th><i class="icon-money"></i>Cena netto</th>
                        <th><i class="icon-quantity"></i>Ilość</th>
                        <th><i class="icon-trash-empty"></i>Usuń</th>
                        <th><i class="icon-money1"></i>Łącznie netto</th>
                    </tr>
                </thead>
                <tbody>' .PHP_EOL;

    foreach ($inCart as $product){

        $id = $product['product_id'];
        $productCartId = $product['id'];
        $kod = $product['kod'];
        $name = $product['name'];
        $net_price = $product['net_price'];
        $quantity = $product['quantity'];
        $total = $quantity * $net_price;

        $minus = '<a href="remFromCart.php?id=' . $id  . '" style="color: black;"><i class="icon-minus"></i></a>';
        $plus = '<a href="addToCart.php?id=' . $id . '" style="color: black;"><i class="icon-plus"></i></a>';
        $usun = '<a href="remProCart.php?id=' . $id . '" style="color: black;"><i class="icon-trash"></i></a>';

        $sum += $total;

        echo "\t\t\t\t\t" . '<tr>
                        <td>' .  $kod . '</td>
                        <td><h4><input type="text" name="zab" value="' . $id . '" hidden>
                                <a href="product.php?product_id=' . $id . '" class="icon-right-dir">' . $name . '</a></h4></td>
                        <td>' . $net_price . '</td>
                        <td><h4>' . $minus . ' ' .  $quantity . ' ' . $plus . '</h4></td>
                        <td>'. $usun .'</td>
                        <td>'. $total .'</td>
                    </tr>' .PHP_EOL;
    }
    echo "\t\t\t\t" . '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><h3><i class="icon-wallet"></i> RAZEM DO ZAPŁATY: </h3></td>
                        <td colspan="2"><h3>' . $sum . ' zł netto</h3></td> 
                    </tr>
                </tfoot> 
            </table>' .PHP_EOL;

            if (isset($_POST['order']) && isset($_POST['zab'])) header("Location: order.php");
            else if (isset($_POST['order']) && !isset($_POST['zab'])) echo '<h2 style="text-align: center;'.
                            ' color: red">Dodaj rzeczy do koszyka</h2>' .PHP_EOL;

            echo  "\t\t\t" . '<button name="order" style="width: 450px; margin: 50px; font-size: 20px; padding: 5px">Złóż zamówienie '.
                            '<i class="icon-cart-order" style="font-size: 25px;"></i></button>
        </form>
        </div>' .PHP_EOL;

require_once 'footer.php';
?>