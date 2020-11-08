<?php

class User {

    private $connect;
    
    function __construct() {
        $this->connect = mysqli_connect("localhost","root","","phpcore") or die("Cannot connect Database.");
    }
    
    function Save($sql) {
        if (mysqli_query($this->connect, $sql)) {
            return true;
        } else {
            return false;
        }
    }
    
    function Get($check) {
        return mysqli_query($this->connect, $check);
    }
}
