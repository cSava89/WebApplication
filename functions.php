<?php 
  $dbhost  = 'localhost';    
  $dbname  = 'Test3DprintersBook';   
  $dbuser  = 'Claudio';   
  $dbpass  = 'basket123';   
  
  
  $salt1="pr&t*";
  $salt2="h*&gy";
  $num_machines=4;//parametro modificabile per cambiare il numero di macchine
  for($i=1;$i<=$num_machines;$i++)
        $machine[$i]="DeltaWasp2040_".$i;
  

  

  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  $err=$connection->autocommit(FALSE);//per le transazioni
  if($err==FALSE)
                die("Autocommit unset failure");
  if ($connection->connect_error) die($connection->connect_error);
  

  

 

 
  
  function destroySessionTimeoutRedirect(){
      session_start();
$t=time();//nel momento in cui accediamo al sito recuperiamo il tempo
$dif=0;
$new=false;

if(isset($_SESSION['s232521_time'])){ // se la variabile time è settata
    $t0=$_SESSION['s232521_time']; // ce la memoriazziamo per poi
    $dif=($t-$t0); //calcolare il periodo di inattività
    }
    else{
    $new=true;//altrimenti è una nuova sessione
    }
    
if($new||($dif>120)){ //se è una nuova sessione o sono passati due minuti
    $_SESSION=array();//distruggo le variabili di sessione dopodiche devo distruggere anche l'identificativo di sessione e l eventuale cookie
    if(ini_get("session.use_cookies")){//posto che uso i cookie
        $params=session_get_cookie_params();//mi salvo i parametri
        if(setcookie(session_name(), '',time()-3600*24,$params["path"],$params["domain"],
    $params["secure"],$params["httponly"]))//e setto i cookie AL PASSATO,cio' lo distrugge
    $_COOKIE=array();
    }
    session_destroy();//distruggo l'identificativo
    header('HTTP/1.1 307 temporary redirect');//reindirizzo il browser
    header('Location:login.php');
    exit;//dopo la redirect il codice php non deve essere piu' eseguito
    } else//la sessione esisteva e il timeout non è scaduto
    $_SESSION['s232521_time']=time();//aggiorno semplicemente il tempo}
}

function isSecure() { //funzione atta a verificare se la connessione è sicura o meno
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
     || $_SERVER['SERVER_PORT'] == 443
     || (
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
         || (!empty($_SERVER['HTTP_X_FORWARDED_SSL'])   && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
        )
    );
}

function requireHTTPS() {
    if (!isSecure()) {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TRUE, 301);
        exit;
    }
}
function requireHTTP() {//essenzialmente per tornare all'intex con http
    if (isSecure()) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TRUE, 301);
        exit;
    }
}


  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);//code inj
    $var = htmlentities($var);//code inj
    $var = stripslashes($var);//un-quotes
    return $connection->real_escape_string($var);//sql inj
  }
  function sanitizeInsert($var)
  {
    global $connection;
    $var = strip_tags($var);
 
    return $connection->real_escape_string($var);
  }
  function fix_string($string)
  {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return htmlentities ($string);
  }

//funzione atte a controllare che i campi rispettino le politiche adottate
   function validate_forename($field) 
  {   
      if($field=="") return "Forename:*Obliged field <br>";
      else if (strlen($field) < 2)
      return  "First Name must be at least 2 characters<br>";
    else if (strlen($field) > 16)
      return "First Name must be at most 16 characters<br>";
      else{
           if (preg_match("/[^a-zA-Z]/", $field))
              return "Only letters in First Name<br>";
           if (!ctype_upper($field[0]) || !ctype_lower(mb_substr($field, 1)))
	  return "First Name:please,first letter uppercase,lowercase the following ones<br>"; 
      }
    return "";	
  	
  }
  
  function validate_surname($field)
  {
      if($field=="") return "Last Name:*Obliged field <br>";
      else if (strlen($field) < 2)
      return  "Last Name must be at least 2 characters<br>";
    else if (strlen($field) > 16)
      return "Last Name must be at most 16 characters<br>";
     else{
           if (preg_match("/[^a-zA-Z]/", $field))
              return "Only letters in Last Name<br>";
           if (!ctype_upper($field[0]) || !ctype_lower(mb_substr($field, 1)))
	  return "Last Name:please,first letter uppercase,lowercase the following ones<br>"; 
      }
    return "";
  }
  
 
  
  function validate_password($field)
  {
     if($field=="") return "Password:*Obliged field <br>";
   
      
    else if (preg_match("/[^a-zA-Z0-9]/", $field))
      return "Please,no special characters in Password<br>";
      
    return "";
  }
  
  
  
  function validate_email($field,$connection)
  {
      if($field=="") return "Email:*Obliged field <br>";
      
         else if (!eregi("^([a-z0-9.-])+@(([a-z0-9-])+.)+[a-z.]{2,6}$", trim($field)))
         return "The Email address is invalid<br>";
    else{
        
        if (isset($_POST['user'])){
                $user=sanitizeString($_POST['user']);
              
       
       $res=$connection->query("SELECT* FROM members WHERE user= '$user'"); 
        if($res->num_rows)
        return "This email is invalid<br>";
        $res->close();
     
      
        }
        
        }
    
    return "";
  }
  
  function endingTime($h,$m,$d){//funzione atta a calcolare l'ending time di una prenotazione,e ritorna errore nel caso in cui venga superata la mezzanotte(il giorno cambia)
      
   $data=new DateTime("$h:$m:00");
   $data->add(DateInterval::createFromDateString("$d minutes"));
   $temp=$data->format("H:i:s-d");
   $endT=explode('-',$temp);
   if(date("d")!=$endT[1])//il giorno è cambiato
   return "error";
   else
   return$endT[0];
      
      
}
    
  function validate_hour($field){
      if($field=="") return "";
      else if(is_numeric($field)){
          if($field>=0 && $field<=23)
          return "";
          }
      return "Hour format error<br>";
      }
    function validate_min($field){
      if($field=="") return "";
      else if(is_numeric($field)){
          if($field>=0 && $field<=59)
          return "";
          }
      return "Minutes format error<br>";
      }
      
    function validate_dur($field){
      if($field=="") 
      return "Duration:*Obliged field <br>";
      
      else if(is_numeric($field)){
          if($field<5 || $field>1440)
          return "Duration must be at least 5 minutes or 24 hours(1440 minutes) at most <br>";
          }
      
      else if(!is_numeric($field))
             return "Duration format error <br>";
              
         
      return "";
      }
      
      function checkPassInsert($h,$min){
          $temp=explode(':',date("H:i:s"));
          if((int)$h<(int)$temp[0] ||(int)$h==(int)$temp[0] && (int)$min<(int)$temp[1])
          return "error";
         
          
          return "";
          }
    
  
  

  
?>