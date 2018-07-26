<?php 
require_once 'functions.php';
requireHTTPS();//stabilisco la connessione https se non lo fosse
destroySessionTimeoutRedirect();//gestione esplicità dell'inattività.Nel caso in cui l utente voglia andare nella sezione di gestione,che richiede autenticazione,e sono scaduti i due minuti di timeout,viene reindirizzato all'autenticazione 

if (isset($_SESSION['s232521_user']))//codice per evitare di accedere alla pagina senza essere autenticato.In particolare sfrutto una variabile di sessione,in quanto so che la sessione parte solo quando l utente è autenticato,altrimenti effettuo la redirect
  {
    $user = sanitizeString($_SESSION['s232521_user']);//poichè accedo al database sanitizzo per evitare sql injection
    $temp_pass = sanitizeString($_SESSION['s232521_pass']);
    
    if ($user == "" || $temp_pass == ""){
       
        header('Location: login.php', TRUE, 301);
        exit;
     
        }
    else
    {
        
       
        $res=$connection->query("SELECT pass FROM members WHERE user='$user'");
        
       
       
            
            if($res->num_rows){
                
                $row=$res->fetch_array(MYSQLI_NUM);
                $res->close();
                
                
                $token=hash('ripemd128',"$salt1$temp_pass$salt2");
                
              
                    
                      if($token==$row[0]){
                                           
                                           
                                            $member= $_SESSION['s232521_fname']." ".$_SESSION['s232521_lname'];
                                           
                                            
                                            
                                                
                                        }
                        else {
                                    
                              
                      
                                $connection->close();
                                header('Location: login.php', TRUE, 301);//redirect  password  invalida
                                exit;
                              
                                
            
                                
                        }
                      } else{
                            $res->close();
                           // $connection->rollback();
                            $connection->close();
                            header('Location: login.php', TRUE, 301);//redirect user non valido
                                exit;
                          
             }                
            
     
      

     
    }
  }else{
  header('Location: login.php', TRUE, 301);
  exit;
}



?>
  
<!DOCTYPE html>
<html>

<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >



<link href="default.css" rel="stylesheet" type="text/css" >


<script src='jquery-1.11.1.min.js'></script>

<script>
$(document).ready(function() {

var refreshId = setInterval(function() {
$("#reservations").load("checkOthers.php");//carico asincronamente le prenotazioni degli altri utenti,e non piu' l intera lista
}, 1000);
});
</script>



</head>
<body>
<div id="page" class="container">

        <div id="header">
		<div id="logo">
			<img src="pic02.jpg" >
			<h1><a>3DP_L@b_APP</a></h1>
			<span>Design by <a>CLIDE89</a></span>
                </div>
                
                                    <div class='menu'>
                                                <ul>
                                                    <li class='current_page_item'><a href='members.php' accesskey='1' >Homepage</a></li>
                                                    <li><a href='manage.php' accesskey='2' >Manage</a></li>
                                                    <li><a href='logout.php' accesskey='3'>Logout</a></li>
                                                    
                                                 
                                                </ul>
                                    </div>
        </div>
	
	<div id="main">
		<div id="banner">
			<img src="1.jpg"  class="image-full" >
		</div>
                  
		<div id="welcome">
			<div class="title">
                                <?php 
                                      
				      echo"<h2>Welcome back dear $member</h2>";
                                ?>
				<span class="byline">These are your personal reservations for today.If you need to manage your reservations,please click on the left menu "Manage".</span>
			</div>
			
                                        
                                        <?php  //in questo caso carico in maniera statica le prenotazioni in quanto i possibili update sono effettuabili esclusivamente dall'utente
                                          echo"<ul class='style1'>"; 
                                          $day=explode(':',date("M:d"));//calcolo mese e giorno corrente,solo per estetica della pagina
                                          $res = $connection->query( "SELECT  machine, startingtime, duration FROM books WHERE client='$user' ORDER BY startingtime");
                                          
                                          if($res->num_rows){ //se ci sono prenotazioni le visualizzo opportunamente
                                          $rows=$res->num_rows;
                                         
                                          for($j=0;$j<$rows;++$j){
                                          $res->data_seek($j);
                                          $row=$res->fetch_array(MYSQLI_NUM);
                                        
                                          
                                          
                                          echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b><a>$row[1]</a></a></p>".
                                                "<h3>Dear $member,your reservation:</h3>".
                                                "<p><a>machine:'$row[0]',duration:'$row[2]' minutes </a></p>".
                                              "</li>";
                                        
                                        
                                         }
                                         echo" </ul>";
                                        $res->close();
                                        }else{//altrimenti evidenzio la mancaza di prenotazioni
                                        
                                            echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b></a></p>".
                                                "<h3>Dear $member,your reservation:</h3>".
                                                "<p><a>No reserved machine </a></p>".
                                              "</li>";
                                              echo" </ul>";
                                      
                                        }
                                    $connection->close();
                                    ?>
                                        
                                        
                                
                                
                            
                        
		</div>
		<div id="featured">
			<div class="title">
				<h2>These are today's reservations of  other users</h2>
				
                               
			</div>
                        <div id="reservations">
                        
                        </div>
		</div>
		<div id="copyright">
			<span>&copy; Untitled. All rights are not reserved. | Photos by Google Image</a></span>
			<span>Design by <a >CLIDE89</a>.</span>
		</div>
	</div>
</div>
</body>
</html>