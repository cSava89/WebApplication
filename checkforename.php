<?php 

    require_once 'functions.php';
    requireHTTPS();

  if (isset($_POST['forename']))
  {
      $fn=$_POST['forename'];
      
      if (strlen($_POST['forename']) < 2)
      echo  "<span >&nbsp;&#x2718; " .
            "First Name must be at least 2 characters<br>";
    else if (strlen($_POST['forename']) > 16)
      echo  "<span >&nbsp;&#x2718; " .
            "First Name must be at most 16 characters<br>";
     else if (preg_match("/[^a-zA-Z]/", $_POST['forename']))
       echo  "<span >&nbsp;&#x2718; " .
             "Only letters in First Name</span>";
    else if (!ctype_upper($fn[0]) || !ctype_lower(mb_substr($fn, 1)))
    echo  "<span >&nbsp;&#x2718; " .
             "Please,first letter uppercase,lowercase the following ones<br></span>";
  else
   echo "<span >&nbsp;&#x2714; " .
           "Welcome! </span>";
  }
   
  


  
?>