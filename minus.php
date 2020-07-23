<?php
require_once 'header.php';

if (preg_match('#^[0-9]{1,5}$#is', trim($_GET['id']))) {

    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT kod FROM sklep.products WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $kod = $row[0]['kod'];

    minus($id, $kod);

    header("Location: addProduct.php?add=zasob");
}
require_once 'footer.php';
?>