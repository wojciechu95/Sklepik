<?php
require_once 'header.php';

if (preg_match('#^[0-9]{1,5}$#is', trim($_GET['id']))){

    $id = $_GET['id'];

    $stmt = $pdo-> prepare("SELECT * FROM sklep.products WHERE id = :id");

    $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $qty = $row[0]['quantity'];

    if ($qty > 0) {
        $cart->add($id);
        header("Location: showcart.php");
    } else echo '<h1>Brak Towaru w sklepie</h1>';

}
require_once 'footer.php';
?>