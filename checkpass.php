<?php 

    require_once 'functions.php';
    requireHTTPS();

  if (isset($_POST['pass']))
  {
  
     if (preg_match("/[^a-zA-Z0-9]/", $_POST['pass']))
      echo "<span >&nbsp;&#x2718; " .
      "Please,no special characters in Password<br>";
    else echo"<span >&nbsp;&#x2714; " .
    " Password acceptable!";
      
  }
   
  


  
?>