<?php

class Sessions{
    private $id;
    private $ip;
    private $browser;
    private $time;
    private $user;
    private $salt;

    public function __construct()
    {
        global $pdo, $request;

        if (!isset($_COOKIE[SESSIONE_COOKIE])) $_COOKIE[SESSIONE_COOKIE] = '';
        else if (strlen($_COOKIE[SESSIONE_COOKIE]) != SESSIONE_ID_LENGHT) $this->newSession();

        $stmt = $pdo-> prepare("SELECT sessions_id, updated_at, salt_token, user_id,
                                                uniq_info, browser, ip FROM sklep.sessions 
                                          WHERE sessions_id = :sid AND uniq_info = :info AND 
                                                updated_at > :updated AND ip = :ip AND browser = :browser");

        $stmt-> bindValue(':sid', $_COOKIE[SESSIONE_COOKIE], PDO::PARAM_STR);
        $stmt-> bindValue(':updated', time() - SESSIONE_COOKIE_EXPIRE, PDO::PARAM_INT);
        $stmt-> bindValue(':info', $request-> getInfo(), PDO::PARAM_STR);
        $stmt-> bindValue(':ip', $request-> getIp(), PDO::PARAM_STR);
        $stmt-> bindValue(':browser', $request-> getBrowser(), PDO::PARAM_STR);
        $stmt-> execute();

        if ($session = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $stmt-> closeCursor();

            $this-> id = $_COOKIE[SESSIONE_COOKIE];
            $this-> salt = $session['salt_token'];
            $this-> ip = $session['ip'];
            $this-> browser = $session['browser'];
            $this-> time = $session['updated_at'];

            setcookie(SESSIONE_COOKIE, $this-> id, time() + SESSIONE_COOKIE_EXPIRE);

            $stmt = $pdo-> prepare("UPDATE sklep.sessions SET updated_at = :ti WHERE sessions_id = :sid");
            $stmt-> bindValue(':sid', $_COOKIE[SESSIONE_COOKIE], PDO::PARAM_STR);
            $stmt-> bindValue(':ti', time(), PDO::PARAM_INT);
            $stmt-> execute();

            if ($session['user_id'] != 0){

                $stmt = $pdo-> prepare("SELECT login, konto FROM sklep.users WHERE id = :uid");

                $stmt->bindValue(':uid', $session['user_id'], PDO::PARAM_INT);
                $stmt->execute();

                $row = $stmt-> fetchAll(PDO::FETCH_ASSOC);
                $this-> user = new user;
                $this-> user-> setLogin($row[0]['login']);
                $this-> user-> setKonto($row[0]['konto']);
                $this-> user-> setId($session['user_id']);

            } else{
                $this-> user = new user(true);
            }

        } else {
            $stmt-> closeCursor();

            $this-> newSession();
        }

    }

    function newSession()
    {
        global $pdo, $request;

        $this-> id = random_session_id();
        $this-> salt = random_salt(10);
        setcookie(SESSIONE_COOKIE, $this-> id, time() + SESSIONE_COOKIE_EXPIRE);

        $stmt = $pdo-> prepare("INSERT INTO sklep.sessions (sessions_id, updated_at, salt_token, user_id,
                                                uniq_info, browser, ip) 
                                          VALUES (:sessions_id, :time, :salt, :user_id,
                                                :uniq_info, :browser, :ip)");

        $stmt-> bindValue(':sessions_id',$this-> id, PDO::PARAM_STR);
        $stmt-> bindValue(':time',time(), PDO::PARAM_INT);
        $stmt-> bindValue(':salt', $this-> salt, PDO::PARAM_STR);
        $stmt-> bindValue(':user_id', 0, PDO::PARAM_INT);
        $stmt-> bindValue(':uniq_info', $request-> getInfo(), PDO::PARAM_STR);
        $stmt-> bindValue(':browser', $request-> getBrowser(), PDO::PARAM_STR);
        $stmt-> bindValue(':ip', $request-> getIp(), PDO::PARAM_STR);
        $stmt-> execute();

        $this-> user = new user(true);

    }

    function updateSession(user $user){
        global $pdo, $request;

        $newId = random_session_id();
        $newSalt = random_salt(10);
        setcookie(SESSIONE_COOKIE, $newId, time() + SESSIONE_COOKIE_EXPIRE);

        $stmt = $pdo-> prepare("UPDATE sklep.sessions SET salt_token = :salt, updated_at = :time, 
                                          sessions_id = :newId, user_id = :uid WHERE sessions_id = :sid");

        $stmt-> bindValue(':salt', $newSalt, PDO::PARAM_STR);
        $stmt-> bindValue(':time', time(), PDO::PARAM_INT);
        $stmt-> bindValue(':newId', $newId, PDO::PARAM_INT);
        $stmt-> bindValue(':uid', $user-> getId(), PDO::PARAM_INT);
        $stmt-> bindValue(':sid', $this-> id, PDO::PARAM_STR);
        $stmt-> execute();

        $this-> id = $newId;
        $this-> user = $user;

    }

    /**
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}

?>