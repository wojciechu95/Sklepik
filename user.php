<?php

class user{
    private $id;
    private $login;
    private $konto;
    private $construct;

    public function __construct($anonymous = true)
    {
        if ($anonymous){
            $this-> id = 0;
            $this-> login = '';
        }
        $this-> construct = true;
    }

    public function isAnonymous(){
        return ($this-> id == 0);
    }

    public function isAdmin(){
        return ($this-> konto == 0);
    }

    public function isUserRoz(){
        return ($this-> konto == 1);
    }

    public function isUser(){
        return ($this-> konto == 2);
    }

    static public function checkPassword($login, $password){
        global $pdo;

            $stmt = $pdo-> prepare("SELECT id, login, konto FROM sklep.users WHERE login = :login AND password = :password");

            $stmt-> bindValue(':login', $login, PDO::PARAM_STR);
            $stmt-> bindValue(':password', md5($password), PDO::PARAM_STR);
            $stmt-> execute();

            if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

                $newUser = new user;
                $newUser->setId($row[0]['id']);
                $newUser->setLogin($row[0]['login']);
                $newUser->setKonto($row[0]['konto']);

                return $newUser;

            } else return 0;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $konto
     */
    public function setKonto($konto)
    {
        $this->konto = $konto;
    }

    /**
     * @return mixed
     */
    public function getKonto()
    {
        return $this->konto;
    }

}

?>