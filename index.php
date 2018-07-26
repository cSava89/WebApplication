<!--Pagina di inizio navigazione.Per rispettare i vincoli strutturali dell'elaborato ho strutturato opportunamente la pagina con una serie di div.Il principale (id='page'), contenente l'intero body della pagina,
è caratterizzato da due div principali:
1) Div "header":caratterizzato dai div "logo",contenente essenzialmente il nome dell'applicazione,e dal div "menu",atto alla navigazione delle varie sezioni dell'applicazione.
Il posizionameto relativo con allineamento a sinistra rispetto al secondo div principale (id= 'main'),è ottenuto tramite gli opportuni settaggi degli attributi nel css esterno (position:relative,float:left) 
2) Div "main".Caratterizzato da una serie di div dove la coppia "banner","welcome" rappresenta l'intestazione richiesta dalla traccia.Semplicemente una foto del set di macchine e un introduzione di benvenuto.
Gli altri div da segnalare sono il div "reservations",in cui tramite JQuery caricherò dinamicamente le prenotazioni,in modo tale che se un utente effettuasse un update,sia esso una cancellazione o una nuova
prenotazione,questa venga correttamente visualizzata.
A fine corpo introduco il div contenente le avvertenze sull'utilizzo di cookie e javascript da parte del sito.
-->


<!DOCTYPE html>
<html>

<head>

<?php require_once 'functions.php';
requireHTTP();//funzione utile a ripristinare http dopo il logout,o se dalla sezione di registrazione o dalla sezione di login si ritornasse su index  



?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<link href="default.css" rel="stylesheet" type="text/css" media="all" > <!-- carico il css esterno -->
<script src='jquery-1.11.1.min.js'></script> <!--carico jquery,che utilizzerò per caricare dinamicamente la pagina relativa alla rappresentazione dello "stato" dell'applicazione -->

<script>
$(document).ready(function() { //quando l'intero documento è stato caricato

var refreshId = setInterval(function() { //richiamo in maniera asincrona la pagina contenente le prenotazioni dell'applicazione,in modo tale che una modifica effettuata da una altro utente,venga correttamente visualizzata
$("#reservations").load("checkReservations.php");
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
                
                                    <div class='menu'> <!--menu-->
                                                <ul>
                                                    <li class='current_page_item'><a href='index.php' accesskey='1' >Homepage</a></li>
                                                    <li><a href='signup.php' accesskey='2' >Sign Up</a></li>
                                                    <li><a href='login.php' accesskey='3' >Log In</a></li>
                                                 
                                                </ul>
                                    </div>
        </div>
	<!-- header costituito da foto e titolo -->
	<div id="main">
		<div id="banner">
			<img src="1.jpg"  class="image-full" >
		</div>
                 
               
		<div id="welcome">
			<div class="title">
				<h2>Welcome to 3DP_L@b_APP</h2>
				<span class="byline">Our goal is allowing our students to learn more about 3D modeling, providing them the best 3D print technology as possible.<br> </span>
                                
			</div>
			<p>This is <strong>3DP_L@b_APP</strong>, a web application which provides our students reservations of 3D printers,in particular the "DeltaWasp2040" ones.Our system automatically assigns a machine according to our availability,and reservations are performed day by day so...do not think about tomorrow,reserve your machine today!</p>
			
		</div>
		<div id="featured">
			<div class="title">
				<h2>These are our reservation for today.</h2>
				<span class="byline">The only way to reserve your own,is joining us!</span>
                              
			</div>
			
                        <div id="reservations">
                        <!-- qui caricherò le prenotazioni presenti grazie a JQuery -->
                        </div>
                                        
                                        
                                
                                
                            
                        </ul>
		</div>
		<div id="copyright">
			<span>&copy; Untitled. All rights are not reserved. | Photos by Google Image</a></span>
			<span>Design by <a >CLIDE89</a>.</span>
		</div>
                <button id='slidetoggle'>Cookies and Javascript Policy</button>
    <div id='para' style='background:#def'>
      
      <p>We use cookies in order to customize our contents,and to offer  more safety during the experience. 
      Navigating our website you accept our cookie policy.Furthermore,we use Javascript in order to improve our users' experience.If you disable them,the site will not work properly.Enjoy!</p>
    </div>
    <script>
      
      $('#slidetoggle').click(function() { $('#para').slideToggle('slow') }) <!-- sfrutto l effetto toogle per avvisare l'utente dell'utilizzo di cookie e javascript -->
    </script>
	</div>
                
</div>
</body>
</html>
