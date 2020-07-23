<?php

class userRequest{
    public $browser;
    public $ip;
    public $info;

    public function __construct()
    {
        $this-> browser = $_SERVER["HTTP_USER_AGENT"];
        $this-> ip = $_SERVER["REMOTE_ADDR"];
        $this-> info = md5($this-> browser . $this-> ip . "fdi409wjk2");
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        $agent = substr($this-> browser, 0, 255);
        return $agent;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this-> ip;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this-> info;
    }
}

?>