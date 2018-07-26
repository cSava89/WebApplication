<?php
require_once 'functions.php';
requireHTTPS();
$_SESSION=array();//distruggo le variabili di sessione dopodiche devo distruggere anche l'identificativo di sessione e l eventuale cookie
    if(ini_get("session.use_cookies")){//posto che uso i cookie
        $params=session_get_cookie_params();//mi salvo i parametri
        if(setcookie(session_name(), '',time()-3600*24,$params["path"],$params["domain"],
    $params["secure"],$params["httponly"]))//e setto i cookie AL PASSATO,cio' lo distrugge
    $_COOKIE=array();
    }
    session_destroy();//distruggo l'identificativo
    header('HTTP/1.1 307 temporary redirect');//reindirizzo il browser
    header('Location:index.php');
    exit;//dopo la redirect il codice php non deve essere piu' eseguito
    
?>