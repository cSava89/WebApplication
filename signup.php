<!DOCTYPE html>
<html>

<head>

<?php require_once 'functions.php'; 


requireHTTPS();//se la connessione non è https,reindirizzo la pagina stessa abilitandola.Anche se nella traccia è richiesta connessione https solo in fase di autenticazione e quando si è correttamente loggati per la gestione delle prenotazioni,ho preferito
//utilizzarla anche qui per proteggere i dati sensibili relativi alla registrazione.
?>


  

  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<link href="default.css" rel="stylesheet" type="text/css" >



<script src='jquery-1.11.1.min.js'></script>

<script>
$(document).ready(function() {

var refreshId = setInterval(function() {
$("#reservations").load("checkReservations.php");//caricamento asincrono per la corretta visualizzazione delle prenotazioni in caso di update di altri utenti.
}, 1000);
});
</script>


</head>
<body>
<script src='javascript.js'></script>
<div id="page" class="container">

        <div id="header">
		<div id="logo">
			<img src="pic02.jpg" alt="" />
			<h1><a>3DP_L@b_APP</a></h1>
			<span>Design by <a>CLIDE89</a></span>
                </div>
                
                                    <div class='menu'>
                                                <ul>
                                                    <li ><a href='index.php' accesskey='1' >Homepage</a></li>
                                                    <li class='current_page_item'><a href='signup.php' accesskey='2' >Sign Up</a></li>
                                                    <li><a href='login.php' accesskey='3'>Log In</a></li>
                                                 
                                                </ul>
                                    </div>
        </div>
	
	<div id="main">
		<div id="banner">
			<img src="1.jpg" alt="" class="image-full" >
		</div>
                
                  
                <div id="welcome">
			<div class="title">
				<h2>Welcome to Signup section</h2>
				
			</div>
			<p>Here you have the possibility to join our 3D modeling program,providing us just your First Name,Last Name,university e-mail and of course... a strong password!</p>
			
		</div>
             
                
                <div class="signupwrapper">
                         <h1 class="h1signup">Register For An Account</h1>
                         <p class="psignup">In this way you will be able to reserve one of our 3D printers machine for your studying purpose. Please use valid credentials.</p>
		 <?php
                    
                    //prima di questo ho chiamate ajax che avvisano l'utente della politica di gestione dei campi
  $tmpuser = $tmppass = $tmpforename = $tmpsurename = $suggest= "";
  
  

//dopo le chiamate ajax suppongo che l'utente possa comunque aver inserito campi sbagliati e li valuto lato server
  
    if(isset($_POST['forename'])) 
  $tmpforename = fix_string($_POST['forename']);
     if(isset($_POST['surename'])) 
  $tmpsurename = fix_string($_POST['surename']);
  
  if(isset($_POST['user'])) 
  $tmpuser = fix_string($_POST['user']);
  if(isset($_POST['pass']))
  $tmppass = fix_string($_POST['pass']); 
  
   //utilizzo fix string,che prevede solo le funzioni stripslashes e htmlentities per prevenire code injection,in modo da poter comunque testare la politica scelta per i campi del form
 

  $suggest  = validate_forename($tmpforename);
  $suggest  .= validate_surname($tmpsurename);
  $suggest  .= validate_email($tmpuser,$connection);//utilizzo il parametro connection perchè accedo al database per verificare se l utente esiste già,nel qual caso lo considero errore e blocco l invio
  $suggest  .= validate_password($tmppass);
 
  
  if(isset($_POST['user'])&& $suggest!="")// se si sono verificati errori li rendo visibili all'utente
  {
       echo <<<_END

  <div><p>Sorry, the following errors were found<br>
          in your form: </p><p>$suggest</i></p>
 </div>
  
_END;
      
      
      }
  
  if($suggest=="" && isset($_POST['user'])){
      $user=sanitizeInsert($tmpuser);//la funzione sanitizeInsert ha lo scopo di evitare sql injection grazie alla funzione real_escape_string.
      //In ogni caso l'intera sanitizzazione fatta è stata attuata in maniera coerente con la politica dei campi del form,dove, nel caso della password ad esempio, ho deciso di accettare solo stringhe di numeri e caratteri,ma non i caratteri speciali.
      $pass=sanitizeInsert($tmppass);
      $forename=sanitizeInsert($tmpforename);
      $surename=sanitizeInsert($tmpsurename);
     
      
      
      $token = hash('ripemd128',"$salt1$pass$salt2");//salting per una maggiore sicurezza lato db
      
       try{
        
        $err=$connection->query("START TRANSACTION");
        if($err==FALSE)
                throw new Exception("Start transaction failure");
        
        $res=$connection->query("SELECT* FROM members WHERE user= '$user' FOR UPDATE");//restituirà un empty set ma è per bypassare un bug che ho riscontrato quando si disabilità l'autocommit false.Le operazioni di modifica,almeno nel mio caso,non venivano effettuate senza un commit finale
        
        
        
            if($res->num_rows){
            echo  "<span >&nbsp;&#x2714; " .
                  "This email is invalid,I can't complete the insert</span>";
                  $res->close();
                  $connection->commit();
                  $connection->close();
            }
            else{
                
                    $res2=$connection->query("INSERT INTO members VALUES('$forename','$surename','$user','$token')");//inserisco i valori opportunamente sanitizzati.Per la password inserisco il token calcolato,che dovà essere opportunamente gestito lato login
                 
                    
                   
                    
                    if($res2){
                                 $res->close();
                                
                                $connection->commit();
                                $connection->close();
                                die("<h4>Account created</h4>Please Log in.<br><br>");//non eseguo codice ulteriore,poichè suppongo che l'azione immediatamente successiva sia il login
                        }
                        
                        else{   
                                         $res->close();
                                         $res2->close();
                                        $connection->rollback();
                                        $connection->close();
                                throw new Exception("Insert error:No row affected");
                                
                    }
                }
   
                
              
      
          }catch(Exception $e)
          {
              $connection->rollback();
              $connection->close();
              echo "Rollback".$e->getMessage();
              }
        
      
      }
?>
                <!--effettuo chiamate ajax per ogni campo in modo da avvertire immediatamente l'utente di un corretto inserimento o meno -->
                         <form class="signupform" method="post" action="signup.php">
                            
                            <input class="signupname"type="text"  maxlength='16' name='forename'  onBlur='checkUser(this,fn,"checkforename.php","forename=")' placeholder="*First Name">
                              <div id='fn'><!--div contenente la risposta ajax -->
                              </div>
                             <div>
                                    <p class="name-help">Please enter your First Name.</p>
                             </div>
                            <script >
                              $(".signupname").focus(function(){ //suggerisco cosa inserire  nel campo tramite uno slide all'atto del focus,effettuandone il successivo clear  tramite slide up all'atto dell'eliminazione del focus
                                    $(".name-help").slideDown(500);}).blur(function(){
                                    $(".name-help").slideUp(500);});
                           </script>
                           
                           
                           
                           <input class="signuplname"type="text"  maxlength='16' name='surename' onBlur='checkUser(this,sn,"checksurname.php","surename=")'  placeholder="*Last Name">
                              <div id='sn'>
                              </div>
                             <div>
                                    <p class="lname-help">Please enter your Last Name.</p>
                             </div>
                            
                           
                            <script >
                              $(".signuplname").focus(function(){
                                    $(".lname-help").slideDown(500);}).blur(function(){
                                    $(".lname-help").slideUp(500);});
                           </script>
     
                            
                                <input class="email" type="text" maxlength='32' name='user' onBlur='checkUser(this,un,"checkemail.php","user=")' placeholder="*Email">
                                <div id='un'>
                                </div>
                                <div>
                                        <p class="email-help">Please enter your current email address.</p>
                                </div>
                                 <script >
                                    $(".email").focus(function(){
                                    $(".email-help").slideDown(500);}).blur(function(){
                                    $(".email-help").slideUp(500);});
                                 </script>
                                 
                                 
                                 <input class="passw" type='password' maxlength='16' name='pass' onBlur='checkUser(this,pw,"checkpass.php","pass=")' placeholder="*Password">
                                 <div id='pw'>
                                 </div>
                                 <div>
                                        <p class="pass-help">Please enter your password.</p>
                                </div>
                                 <script >
                                    $(".passw").focus(function(){
                                    $(".pass-help").slideDown(500);}).blur(function(){
                                    $(".pass-help").slideUp(500);});
                                 </script>
                                
                                <input type="submit" class="submit" value="Join Us!">
                        </form>
                </div>
		
                
               
                
                
                
                
                <div id="featured">
			<div class="title">
				<h2>Here you are able to see our reservations for today.</h2>
				
                              
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
  


    
        
    

  
  
 