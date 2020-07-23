<?php
require_once 'header.php';

    if (isset($_POST['zamow'])){
        $wszystko_OK = true;

        $imie = $_POST['customerName'];
        $imie = ucwords($imie);
        $imie = trim($imie);

        $nazwisko = $_POST['customerSurename'];
        $nazwisko = ucwords($nazwisko);
        $nazwisko = trim($nazwisko);

        $nr_tel = $_POST['nr_tel'];

        $email = $_POST['email'];

        $ulica = $_POST['street'];
        $ulica = ucwords($ulica);
        $ulica = trim($ulica);

        $nr_domu = $_POST['nr_Domu'];
        $nr_domu = trim($nr_domu);

        $nr_mieszkania = $_POST['nr_Mieszkania'];
        $nr_mieszkania = trim($nr_mieszkania);

        $kod_pocztowy = $_POST['post_code'];

        $miejscowosc = $_POST['city'];
        $miejscowosc = ucwords($miejscowosc);
        $miejscowosc = trim($miejscowosc);


            //Sprawdzanie długości zmiennych z tablicy _POST
        if ((strlen($email) < 7) || (strlen($email) > 120))
        {
            $wszystko_OK = false;
        }

        if (!preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,80}$#is', $imie) ||
            !preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,80}$#is', $nazwisko) ||
            !preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,80}$#is', $miejscowosc) ||
            !preg_match('#^[\w\d\040ĄąĆćĘęŁłŃńÓóŚśŹźŻż\-\.]{2,80}$#is', $ulica) ||
            !preg_match('#^[\w\d\040\-\.]{1,5}$#is', $nr_domu) ||
            !preg_match('#^[\w\d\040\-\.]{0,5}$#is', $nr_mieszkania) ||
            !preg_match('#^[0-9\-]{6}$#is', $kod_pocztowy) ||
            !preg_match('#^[0-9\-]{11}$#is', $nr_tel))
        {
            $wszystko_OK = false;
        }

        if (!isset($_POST['regulamin']))
        {
            $wszystko_OK = false;
        }

        //Sprawdzanie adresu e-mail
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);//sanityzacja adresu e-mail
        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email))
        {
            $wszystko_OK = false;
        }

        if($wszystko_OK)
        {
            $stmt = $pdo->prepare("INSERT INTO sklep.orders (id, imie, nazwisko, nr_tel,
                                        email, ulica, nr_domu, nr_mieszkania, kod_pocztowy, miejscowosc)
                                             VALUES (NULL , :imie, :nazwisko, :nr_tel, :email, :ul, :nr_domu,
                                        :nr_mieszkania, :kod_pocztowy, :miejscowosc)");

            $stmt->bindValue(":imie", $imie, PDO::PARAM_STR);
            $stmt->bindValue(":nazwisko", $nazwisko, PDO::PARAM_STR);
            $stmt->bindValue(":nr_tel", $nr_tel, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":ul", $ulica, PDO::PARAM_STR);
            $stmt->bindValue(":nr_domu", $nr_domu, PDO::PARAM_STR);
            $stmt->bindValue(":nr_mieszkania", $nr_mieszkania, PDO::PARAM_STR);
            $stmt->bindValue(":kod_pocztowy", $kod_pocztowy, PDO::PARAM_STR);
            $stmt->bindValue(":miejscowosc", $miejscowosc, PDO::PARAM_STR);
            $stmt->execute();

            $ordid = $pdo->lastInsertId();

            $orderedProducts = $cart->getProducts();

            foreach ($orderedProducts as $product) {

                $pid = $product['pid'];
                $qty = $product['quantity'];
                $quantity = $product['qty'];
                $kod = $product['kod'];

                $stmt = $stmt = $pdo->prepare("INSERT INTO sklep.ordersproducts (id, order_id, product_id, 
                                                        quantity) VALUES (NULL , :orderId, :pid, :qty)");

                $stmt->bindValue(':orderId', $ordid, PDO::PARAM_INT);
                $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
                $stmt->bindValue(':qty', $qty, PDO::PARAM_INT);
                $stmt->execute();

                $quantity -= $qty;

                $stmt = $pdo-> prepare("UPDATE sklep.products SET quantity = :qty WHERE id = :id");

                $stmt-> bindValue(':qty', $quantity, PDO::PARAM_INT);
                $stmt-> bindValue(':id', $pid, PDO::PARAM_INT);
                $stmt->execute();

            }

            $cart->clear();

            echo '<h1>Dziękujemy za złożenie zamówienia :)</h1>';

            //mail($_POST['email'], "zamówienie nr $ordid", "Potwierdzamy zamówienie towaru");

        } else {

            //Zapamiętaj wprowadzone dane
            $_SESSION['imie'] = $imie;
            $_SESSION['nazwisko'] = $nazwisko;
            $_SESSION['email'] = $email;
            $_SESSION['ulica'] = $ulica;
            $_SESSION['nr_tel'] = $nr_tel;
            $_SESSION['nr_domu'] = $nr_domu;
            $_SESSION['nr_mieszkania'] = $nr_mieszkania;
            $_SESSION['kod_pocztowy'] = $kod_pocztowy;
            $_SESSION['miejscowosc'] = $miejscowosc;
            if (isset($_POST['regulamin'])) $_SESSION['regulamin'] = true;

             header('Location: order.php');
        }

    }

require_once 'footer.php';
?>


