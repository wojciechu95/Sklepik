<?php
    require_once 'header.php';
if (preg_match('#^[0-9]{0,5}$#is', trim(@$_GET['cat_id']))) {

    if (isset($_GET['cat_id'])) $category_id = $_GET['cat_id'];
    else $category_id = null;
    showCategory($category_id);
}
    require_once 'footer.php';
?>
