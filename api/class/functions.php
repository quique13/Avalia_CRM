<?php

class Functions
{
    public function getHeaders(){
        header('Content-Type: application/json');
        ini_set("display_errors", 0);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, API-KEY");
    }

    public function encryptPassword($pass) {
        $val = 0;
        for ($i=0; $i < strlen($pass); $i++) { 
            $val+= ord(substr($pass,$i,1));
        }
        return $val;
    }
}

?>