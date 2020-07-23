<?php

class cart{

    public function __construct()
    {

    }

    public function add($id){
        global $pdo, $session;

        $stmt = $pdo-> prepare("SELECT * FROM sklep.sessioncart WHERE product_id = :id AND session_id = :sid");

        $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
        $stmt->execute();

        $stmt1 = $pdo-> prepare("SELECT * FROM sklep.products WHERE id = :id");

        $stmt1-> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        $row = $stmt1-> fetchAll(PDO::FETCH_ASSOC);
        $qty1 = $row[0]['quantity'];

        if ($row = $stmt-> fetchAll(PDO::FETCH_ASSOC)){

            $qty = $row[0]['quantity'] + 1;

            if ($qty <= $qty1)
            {
                $stmt = $pdo-> prepare("UPDATE sklep.sessioncart SET quantity = :qty WHERE session_id = :sid
                                                      AND product_id = :pid");

                $stmt-> bindValue(':qty', $qty, PDO::PARAM_INT);
                $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
                $stmt-> bindValue(':pid', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        } else {

            $stmt = $pdo->prepare("INSERT INTO sklep.sessioncart (id, session_id, product_id, quantity)
                                          VALUES (NULL , :sid, :pid, 1)");

            $stmt->bindValue(':sid', $session->getId(), PDO::PARAM_STR);
            $stmt->bindValue(':pid', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

    }

    public function remove($id){
        global $pdo, $session;

        $stmt = $pdo-> prepare("SELECT * FROM sklep.sessioncart WHERE product_id = :id AND session_id = :sid");

        $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
        $qty = $row[0]['quantity'];

        $qty--;

        if ($qty == 0){

            $stmt = $pdo->prepare("DELETE FROM sklep.sessioncart WHERE product_id = :id AND session_id = :sid");

            $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        } else {

            $stmt = $pdo-> prepare("UPDATE sklep.sessioncart SET quantity = :qty WHERE session_id = :sid
                                                      AND product_id = :id");

            $stmt-> bindValue(':qty', $qty, PDO::PARAM_INT);
            $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
            $stmt-> bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function rem($id){
        global $pdo, $session;

            $stmt = $pdo->prepare("DELETE FROM sklep.sessioncart WHERE product_id = :id AND session_id = :sid");

            $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    }

    public function getProducts(){
        global $pdo, $session;

        $stmt = $pdo-> prepare("SELECT s.id, s.quantity, s.product_id, p.name, p.net_price, p.kod, p.id as pid, p.quantity as qty 
                                          FROM sklep.sessioncart s LEFT OUTER JOIN sklep.products p 
                                          ON (s.product_id = p.id) WHERE s.session_id = :sid");

        $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
        $stmt-> execute();

        $result = $stmt-> fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function clear(){
        global $pdo, $session;

        $stmt = $pdo->prepare("DELETE FROM sklep.sessioncart WHERE session_id = :sid");

        $stmt-> bindValue(':sid', $session->getId(), PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>