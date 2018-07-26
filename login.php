<?php

require_once 'functions.php';
requireHTTPS();


  
 
 
                    
  $user = $pass = $suggest= "";
  
  if(isset($_SERVER['HTTP_REFERER'])&&($_SERVER['HTTP_REFERER']=="https://cclix11.polito.it/~s232521/c29cd4/members.php" || $_SERVER['HTTP_REFERER']=="https://cclix11.polito.it/~s232521/c29cd4/manage.php"))
  $timeout="Sorry,but you are forced to Login again because inactivity timeout has expired"; //avviso l utente del perchè è stato reindirizzato qui quando scade il timeout legato all'inattività
  else 
  $timeout="";
  

if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);//sanitarizzo da code injection,html injection e sql injection
    $temp_pass = sanitizeString($_POST['pass']);
    
    if ($user == "" || $temp_pass == ""){
        $suggest = "Not all fields were entered";
     
        }
    else
    {
        
    
        $res=$connection->query("SELECT name,lastname,pass FROM members WHERE user='$user'");//seleziono anche nome e cognome per memorizzarli nelle variabili di sessione per un successivo utilizzo
        if(!$res)die($connection->error);
       
        else {
            
            if($res->num_rows){
                
                $row=$res->fetch_array(MYSQLI_NUM);
                $res->close();
                
                
                $token=hash('ripemd128',"$salt1$temp_pass$salt2");//effettuo il salting lato login per ritrovare correttamente la password inserita dall'utente
                
              
                    
                      if($token==$row[2]){
                                           
                                            $connection->commit();
                                            $connection->close();
                                            session_start();//essendo l'utente correttamente loggato,faccio partire la sessione,e lo faccio prima di qualsiasi linea html per un corretto funzionamento 
                                            $_SESSION['s232521_user']=$user;
                                            $_SESSION['s232521_pass']=$temp_pass;
                                            $_SESSION['s232521_fname']=$row[0];
                                            $_SESSION['s232521_lname']=$row[1];
                                            $_SESSION['s232521_time']=time();
                                            if(isset($_SESSION['s232521_user'])&&isset($_SESSION['s232521_pass'])&&isset($_SESSION['s232521_fname'])&&isset($_SESSION['s232521_lname'])&&isset($_SESSION['s232521_time']))
                                             header('Location: members.php', TRUE, 301);
                                            exit;
                                            
                                            
                                                
                                        }
                        else {
                                    
                              
                             
                                 $connection->close();
                                $suggest="Attention! Password or Username invalid";//non faccio capire quale dei due campi sia sbagliati,ma scrivo per primo quello errato per debugging
                              
                                
            
                                
                        }
                      } else{
                            $res->close();
                          
                             $connection->close();
                             $suggest="Attention! Username or Password invalid";
                          
             }                
            }
    
      

     
    }
  }
?>
<!DOCTYPE html>
<html>

<head>

  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >

<link href="default.css" rel="stylesheet" type="text/css"  >


<script src='jquery-1.11.1.min.js'></script>

<script>
$(document).ready(function() {

var refreshId = setInterval(function() {
$("#reservations").load("checkReservations.php");
}, 1000);
});
</script>


</head>
<body>
<script src='javascript.js'></script>
<div id="page" class="container">

        <div id="header">
		<div id="logo">
			<img src="pic02.jpg" >
			<h1><a>3DP_L@b_APP</a></h1>
			<span>Design by <a>CLIDE89</a></span>
                </div>
                
                                    <div class='menu'>
                                                <ul>
                                                    <li ><a href='index.php' accesskey='1' >Homepage</a></li>
                                                    <li ><a href='signup.php' accesskey='2' >Sign Up</a></li>
                                                    <li class='current_page_item'><a href='login.php' accesskey='3' title=''>Log In</a></li>
                                                 
                                                </ul>
                                    </div>
        </div>
	
	<div id="main">
		<div id="banner">
			<img src="1.jpg"  class="image-full" >
		</div>
               
                <div id="welcome">
			<div class="title">
				<h2>Welcome to Login section</h2>
                                <p>After Log In,you will be able to see and manage your reservation for today,adding new ones and or deleting them.</p>
			</div>	
			
			
		</div>
                
                <div class="signupwrapper">
                         <h1 class="h1signup">Log In Form</h1>
                         <p class="psignup"> Please use valid credentials.</p>

<?php
                        if($suggest!="") echo "<div > $suggest </div><br><br>"; 
                        
                        if($timeout!="") echo "<div > $timeout </div><br><br>"; 
?> 
                
                         <form class="signupform" method="post" action="login.php">
                            
                           
                           
                           
                                <input class="email" type="text" maxlength='32' name='user' placeholder="*Email">
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
                                 
                                 
                                 <input class="passw" type='password' maxlength='16' name='pass'  placeholder="*Password">
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
                                
                                <input type="submit" class="submit" value="Log In">
                        </form>
                </div>
		
                
               
                
                
                
                
                <div id="featured">
			<div class="title">
				<h2>These are today's reservations.</h2>
				
                              
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


    
        
    

  
  
 