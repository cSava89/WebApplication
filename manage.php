<?php 
require_once 'functions.php';
requireHTTPS();//reindirizzo la connessione se non fosse https
destroySessionTimeoutRedirect(); //gestione esplicità dell'inattività.Essendo i post di cancellazione e inserimento di una prenotazione gestiti nella stessa pagina,questa funzione mi garantisce che se l utente effettua
//tali operazioni dopo che siano passati i due minuti relativi al timeout,essi verrano forzati a riloggarsi


if (isset($_SESSION['s232521_user'])) //funzione per impedire ad un utente non loggato di accedere alla pagina
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
                                           
                                            
                                            $FNmember=$_SESSION['s232521_fname'];
                                            $LNmember=$_SESSION['s232521_lname'];
                                            
                                            
                                                
                                        }
                        else {
                                    
                              
                            
                                $connection->close();
                                header('Location: login.php', TRUE, 301);//redirect  password  invalida
                                exit;
                              
                                
            
                                
                        }
                      } else{
                            $res->close();
                           
                            $connection->close();
                            header('Location: login.php', TRUE, 301);//redirect  user non valido
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
var delId = setInterval(function() { //carico asincronamente,ogni secondo,la lista delle prenotazioni cancellabili,in modo da permettere all'utente di vedere le prenotazioni appena immesse ma non ancora cancellabili,e dopo un minuto quelle cancellabili
$("#del").load("deleteList.php");
}, 1000);

var refreshId = setInterval(function() {//carico le prenotazioni relative ai restanti utenti
$("#reservations").load("checkOthers.php");
}, 1000);

});
</script>



</head>

<body>
<div id="page" class="container">

        <div id="header">
		<div id="logo">
			<img src="pic02.jpg" alt="" />
			<h1><a>3DP_L@b_APP</a></h1>
			<span>Design by <a>CLIDE89</a></span>
                </div>
                
                                    <div class='menu'>
                                                <ul>
                                                    <li><a href='members.php' accesskey='1' >Homepage</a></li>
                                                    <li  class='current_page_item'><a href='manage.php' accesskey='2' >Manage</a></li>
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
                                      
				      echo"<h2>Welcome to management page dear $FNmember $LNmember</h2>";
                                ?>
				<span class="byline">Here you can reserve your 3D printer providing us just the starting time of your reservation(format h:m) and its duration(in minutes).
                                In order to perform the simplest model of 3D print with our machines,the duration of your reservation must be at least 5 minutes.  
                                We will provide to fill the other requested fields according to your credentials.As long as removing reservations are concerned,you will be able to delete one reservation after 1 minute from its correct insert.
                                In the "eraseable" list,you will be able to see the ereasable and the "not ereasable yet" reservations.</span>
			</div>
                        
               </div>
                <div class="signupwrapper">
                         <h1 class="h1signup">Reservation Form <br></h1>
                         <p class="psignup">Please use valid credentials.</p>
                                
                                <?php
                                
                                
                                            
                                            
                                            $h=$min=$dur=$errors="";
                        
                        if(isset($_POST['hour']))
                        $h=sanitizeString($_POST['hour']);//sanitizzo gli input del form in modo da prevenire eventuale code,html e sql injection
                        if(isset($_POST['minutes']))
                        $min=sanitizeString($_POST['minutes']);
                        if(isset($_POST['duration']))
                        $dur=sanitizeString($_POST['duration']);
                        
                        $errors=validate_hour($h);//effettuo la validazione lato server dei campi inseriti
                        $errors.=validate_min($min);
                        $errors.=validate_dur($dur);
                        
                       if(isset($_POST['hour'])&& $errors!=""){//In caso di errori li visualizzo
                                        echo <<<_END
                                                    <div><p>Sorry, the following errors were found<br>
                                                            in your form: </p><p>$errors</p>
                                                            </div>
  
_END;
      
      
      }
                        
                            
                        if(isset($_POST['hour'])&& $errors==""){//altrimenti
                    
                                $sTime=$h.":".$min.":00";//calcolo lo starting time come stringa intera in modo da poterlo inserire correttamente nel db
                                $eTime=endingTime($h,$min,$dur);//calcolo il tempo di fine prenotazione,in quanto mi sarà utile nella logica di ricerca delle macchine occupate nel periodo richiesto dall'utente
                                $passInsert=checkPassInsert($h,$min);//verifico se l'utente sta inserendo una prenotazione per un periodo precedente al tempo attuale.Essendo una applicazione di prenotazione giornaliera,
                                //suppongo che l'utente possa prenotare una macchina solo con uno starting time maggiore del tempo attuale.
                                
                                
                                if($eTime =="error"){//gestisco il caso in cui la durata della prenotazione inserita dall'utente porta a superare la mezzanotte,segnalando errore e rifiutandola.
                                                  echo <<<_END
                                                  <div><p>Sorry, the following errors were found<br>
                                                            in your form: </p>
                                                            <div><p> We have notice your reservation may last up to tomorrow,please try again</p></div>
                                                            </div>
                                                   
  

_END;
                                    }
                                    else if($passInsert!=""){//caso in cui l'utente cerca di prenotare una macchina con uno starting time precedente all'ora attuale
                                            echo <<<_END
                                                  <div><p>Sorry, the following errors were found<br>
                                                            in your form: </p>
                                                            <div><p>We apologize,but it is too late for reserving a machine at $sTime</p></div>
                                                            </div>
                                                   
  

_END;
                                        
                                        
                                        
                                        
                                        
                                        
                                        }else{//altrimenti la prenotazione è accettabile
                                        
                                            try{
                                                
                                                    $err=$connection->query("START TRANSACTION");
                                                            if($err==FALSE)
                                                            throw new Exception("Start transaction failure");
        
                                                   
                                                    $res=$connection->query("SELECT machine FROM books WHERE endingTime >= '$sTime' AND startingtime<='$eTime' FOR UPDATE");//in questo modo ottengo le macchine occupate nel periodo richiesto dalla prenotazione
                                                            if($res==FALSE)
                                                            throw new Exception("Query failure");
                                                            if($res->num_rows){//se ci sono risultati,e quindi macchine occupate,calcolo quelle libere
                                                                                
                                                                                $rows=$res->num_rows;
                                                                                $mBusy=array();
                                                                                                
                                                                                for($j=0;$j<$rows;++$j){
                                                                                                        $res->data_seek($j);
                                                                                                        $row=$res->fetch_array(MYSQLI_NUM);
                                                                                                        $mBusy[$j]=$row[0];//salvo le macchine occupate
                                                                                                    
                                                                                                    }
                                                                                                
                                                                                                
                                                                                                $mAvailable=array_diff($machine,$mBusy);//ne faccio la differenza con quelle presenti nell'app per trovare le eventuali disponibili
                                                                                                
                                                                                                if(count($mAvailable)>=1){//se almeno una macchina è libera
                                                                                                                    $choosen=array_rand($mAvailable,1);//la scelgo in modo casuale dalle macchine disponibili
                                                                                                                    $m_to_insert=$machine[$choosen];//assegno quella da inserire
                                                                                             
                                                                                                                    $res->close();
                                                                                             
                                                                                                //a questo punto ho durata, starting time, ending time e macchina.Per la colonna client sfrutto session
                                                                                                                    $Ccode=$_SESSION['s232521_user'];
                                                                                                                    $Tinsert = date("H:i:s");//per tenere traccia del tempo in cui la prenotazione è stata correttamente registrata.Parametro che utilizzerò per calcolare la possibilità di cancellazione dei record inseriti
                                                                                                
                                                                                                                    $res=$connection->query("INSERT INTO books(client,machine,insertTime,startingtime,duration,endingTime) VALUES('$Ccode','$m_to_insert','$Tinsert','$sTime','$dur','$eTime')");
                                                                                             
                                                                                                                    if($res){
                                                                                                        
                                                                                                                            $connection->commit();
                                                                                                                            echo "Congratulations! Your reservation has succesfully been managed! It has been assigned to you an amazing '$m_to_insert' from '$sTime' to '$eTime'.Thank you for choosing us!<br><br>";
                                                                                                            
                                                                                                    
                                                                                                                            }
                                                                                                                            else{
                                                                                                                                    $res->close();
                                                                                                                                    $connection->rollback();
                                                                                                                                    
                                                                                                        
                                                                                                                                }
                                                                                                    
                                                                                                    }else{
                                                                                                        echo <<<_END
                                                                                                        <div><p>Sorry, the following errors were found while inserting your request:<br></p>
                                                                                                        <div><p>We apologize,but no machine is available from '$sTime' to '$eTime'</p></div>
                                                                                                        </div>
                                                   
  

_END;
                                                                                            $res->close();
                                                                                            $connection->rollback();
                                                                                                        
                                                                                                        }
                                                                                                
                                                            }else{ //tutte le macchine sono disponibili,perchè la richiesta non si sovrappone con nessun altra
                                                            
                                                                     
                                                                                                                    $m_to_insert="DeltaWasp2040_1";//assegno di default la prima
                                                                                             
                                                                                                                    $res->close();
                                                                                             
                                                                                                //a questo punto ho durata, starting time, ending time e macchina.Per la colonna client sfrutto session
                                                                                                                    $Ccode=$_SESSION['s232521_user'];
                                                                                                                    $Tinsert = date("H:i:s");
                                                                                                
                                                                                                                    $res=$connection->query("INSERT INTO books(client,machine,insertTime,startingtime,duration,endingTime) VALUES('$Ccode','$m_to_insert','$Tinsert','$sTime','$dur','$eTime')");
                                                                                             
                                                                                                                    if($res){
                                                                                                                            
                                                                                                                            $connection->commit();
                                                                                                                            echo "Congratulations! Your reservation has succesfully been managed! It has been assigned to you an amazing '$m_to_insert' from '$sTime' to '$eTime'.Thank you for choosing us!<br><br>";
                                                                                                            
                                                                                                    
                                                                                                                            }
                                                                                                                            else{
                                                                                                                                    $res->close();
                                                                                                                                    $connection->rollback();
                                                                                                                                    
                                                                                                        
                                                                                                                                }
                                                                
                                                                }                              
                                                           
                                                                                      
                                                                                          
                                
                                                                                  
                                                                                            
                                                                                            
                                                    
                                                    }catch(Exception $e){
                                                                    $connection->rollback();
                                                                    echo "Rollback".$e->getMessage();
                                                    }
                                       
                                        
                                        }
                                
                                
                            }
                                //inserisco campi preinseriti,con caratteristiche disabled e readonly
                      echo <<<_END
                            <form class="signupform" method="post" action="manage.php">
                            
                            <input type="text"  value=$FNmember readonly disabled> 
                            
                            <input type="text"  value=$LNmember readonly disabled>
                            
                            <input type="text"  value=$user readonly disabled>
                             
_END;
?>
                            <div id="st-help"> </div>
                            <div>
                            <select name="hour" class="reserveHour" > 
                           <option value="00">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                </select>
                                <script >
                              $(".reserveHour").focus(function(){//help per la scrittura dei campi
                                    $("#st-help").html("Please, enter the hour of reservation start time." );}).blur(function(){
                                    $("#st-help").html("");});
                                
                           </script>
                        
                            
                          
                         
                         
                                       
                       
                         
                         
                         <select name="minutes" class="reserveMin" > 
                                        <option value="00">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                        <option value="32">32</option>
                                        <option value="33">33</option>
                                        <option value="34">34</option>
                                        <option value="35">35</option>
                                        <option value="36">36</option>
                                        <option value="37">37</option>
                                        <option value="38">38</option>
                                        <option value="39">39</option>
                                        <option value="40">40</option>
                                        <option value="41">41</option>
                                        <option value="42">42</option>
                                        <option value="43">43</option>
                                        <option value="44">44</option>
                                        <option value="45">45</option>
                                        <option value="46">46</option>
                                        <option value="47">47</option>
                                        <option value="48">48</option>
                                        <option value="49">49</option>
                                        <option value="50">50</option>
                                        <option value="51">51</option>
                                        <option value="52">52</option>
                                        <option value="53">53</option>
                                        <option value="54">54</option>
                                        <option value="55">55</option>
                                        <option value="56">56</option>
                                        <option value="57">57</option>
                                        <option value="58">58</option>
                                        <option value="59">59</option>
                                        
                                </select>
                           
                            
                         
                            <script >
                              $(".reserveMin").focus(function(){
                                    $("#st-help").html("Please, enter the minute of reservation start time." );}).blur(function(){
                                    $("#st-help").html("");});
                           </script>
                            
                        </div>
                         
                        
                         <div>
                         
                         <input type="number" name="duration" min="1" max="1440" class="reserveDur" placeholder="*Duration">
                          <div>
                                    <p class="dur-help">Please enter reservation's duration.</p>
                             </div>
                            
                           
                            <script >
                              $(".reserveDur").focus(function(){
                                    $(".dur-help").slideDown(500);}).blur(function(){
                                    $(".dur-help").slideUp(500);});
                           </script>
                         
                         </div>
                         <input type="submit"  value="Reserve" >
                        </form>
                         
                </div>   
                
                <div id="featured">
                
                <?php
               
                if(isset($_POST['delete'])&& isset($_POST['bcode'])){//se la prenotazione è cancellabile e l'utente vuole cancellarla,accedo al capo nascosto bcode per poter cancellare il record
                                                 
                                                 $rcode=sanitizeString($_POST['bcode']);//sanitizzo per evitare sql injection
                                                   
                                                $err=$connection->query("START TRANSACTION");
                                                            if($err==FALSE)
                                                            throw new Exception("Start transaction failure");
                                                $resS=$connection->query("SELECT bcode FROM books WHERE bcode='$rcode' FOR UPDATE");
                                                
                                                $resD=$connection->query("DELETE FROM books WHERE bcode='$rcode'");
                                                if(!$resD){
                                                        //echo"Delete failure";
                                                        $connection->rollback();
                                                        }
                                                else
                                                $connection->commit();
                                                        
                                                       
                                                        
                                                }
                
                ?>
                        
                        <div class="title">
				<h2>These is your "Eraseable List": </h2>
                        </div>
                        
                        <div id="del">
                        
                        </div>
                
               
			<div class="title">
				<h2>These are today's reservations of other users</h2>
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
                
                
   
           