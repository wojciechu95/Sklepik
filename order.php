<?php
require_once 'header.php';
?>
    <div class="kol">
        <h1>Dane do zamówienia</h1>
        <form action="orderSummary.php" method="post" class="order">
            <div class="kol1">
                <p><i class="icon-address1" style="font-size: 35px"></i>  Dane:</p>
                <p><label>Imię<b style="color: red">*</b>:</label> <input type="text" name="customerName" value="<?php
                    if (isset($_SESSION['imie']))
                    {
                        echo $_SESSION['imie'];
                        unset($_SESSION['imie']);
                    }
                    ?>" required></p>
                <p><label>Nazwisko<b style="color: red">*</b>:</label> <input type="text" name="customerSurename" value="<?php
                    if (isset($_SESSION['nazwisko']))
                    {
                        echo $_SESSION['nazwisko'];
                        unset($_SESSION['nazwisko']);
                    }
                    ?>" required></p>
                <p><label>Nr telefonu<b style="color: red">*</b>:</label> <input type="tel" name="nr_tel" id="phone" value="<?php
                    if (isset($_SESSION['nr_tel']))
                    {
                        echo $_SESSION['nr_tel'];
                        unset($_SESSION['nr_tel']);
                    }
                    ?>" required></p>
                <p><label>Email<b style="color: red">*</b>:</label> <input type="email" name="email" value="<?php
                    if (isset($_SESSION['email']))
                    {
                        echo $_SESSION['email'];
                        unset($_SESSION['email']);
                    }
                    ?>" required></p>
            </div>
            <div class="kol2">
                <p><i class="icon-address" style="font-size: 35px"></i>  Adres:</p>
                <p><label>Ulica<b style="color: red">**</b>:</label> <input type="text" name="street" value="<?php
                    if (isset($_SESSION['ulica']))
                    {
                        echo $_SESSION['ulica'];
                        unset($_SESSION['ulica']);
                    }
                    ?>" required></p>
                <p><label>Nr domu<b style="color: red">*</b>:</label> <input type="text" maxlength="5" name="nr_Domu" value="<?php
                    if (isset($_SESSION['nr_domu']))
                    {
                        echo $_SESSION['nr_domu'];
                        unset($_SESSION['nr_domu']);
                    }
                    ?>" required></p>
                <p><label>Nr mieszkania:</label> <input type="text" maxlength="5" name="nr_Mieszkania" value="<?php
                    if (isset($_SESSION['nr_mieszkania']))
                    {
                        echo $_SESSION['nr_mieszkania'];
                        unset($_SESSION['nr_mieszkania']);
                    }
                    ?>" ></p>
                <p><label>Kod Pocztowy<b style="color: red">*</b>:</label> <input type="text" name="post_code" id="postcode" value="<?php
                    if (isset($_SESSION['kod_pocztowy']))
                    {
                        echo $_SESSION['kod_pocztowy'];
                        unset($_SESSION['kod_pocztowy']);
                    }
                    ?>" required></p>
                <p><label>Miejscowość<b style="color: red">*</b>:</label> <input type="text" name="city" value="<?php
                    if (isset($_SESSION['miejscowosc']))
                    {
                        echo $_SESSION['miejscowosc'];
                        unset($_SESSION['miejscowosc']);
                    }
                    ?>" required></p>
            </div>

            <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
            <script type="text/javascript">
            jQuery(function($){
            $("#phone").mask("999-999-999");
            $("#postcode").mask("99-999");
            });
            </script>

            <div class="kol" style="text-align:justify; clear: both">
                <label style="display: table;"><input type="checkbox" name="regulamin" style="width: 25px" required><b style="color: red">*</b>
                    Zapoznałem się i akceptuję regulamin świadczenia usług serwisu z częściami i akcesoriami motoryzacyjnymi. Wyrażam zgodę...
                    <a href="regulamin.php">czytaj całość</a></label><br>
                <label style="display: table;"><input type="checkbox" name="info" style="width: 25px">
                     Chcę otrzymywać informacje o ofercie handlowej, aktualnych promocjach oraz oferty marketingowe...
                    <a href="regulamin.php">czytaj całość</a></label>
            </div>

            <button type="submit" name="zamow"><i class="icon-ok">Zamów </i></button>
        </form>
        <p><b style="color: red">*</b> - Pola obowiązkowe</p>
        <p><b style="color: red">**</b> - Jeśli mieszkasz w miejscowości gdzie nie ma nazw ulic wpisz jej nazwę</p>
    </div>
<?php
require_once 'footer.php';
?>