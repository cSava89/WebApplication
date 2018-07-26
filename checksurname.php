<?php 

    require_once 'functions.php';
    requireHTTPS();

  if (isset($_POST['surename']))
  {
      $ln=$_POST['surename'];
       if (strlen($_POST['surename']) < 2)
      echo  "<span >&nbsp;&#x2718; " .
            "Last Name must be at least 2 characters<br>";
    else if (strlen($_POST['surename']) > 16)
      echo  "<span >&nbsp;&#x2718; " .
            "Last Name must be at most 16 characters<br>";
    else if (preg_match("/[^a-zA-Z]/", $_POST['surename']))
       echo  "<span >&nbsp;&#x2718; " .
             "Only letters in Last Name</span>";
    else if (!ctype_upper($ln[0]) || !ctype_lower(mb_substr($ln, 1)))
    echo  "<span >&nbsp;&#x2718; " .
             "Please,first letter uppercase,lowercase the following ones<br></span>";
 
  else
   echo "<span >&nbsp;&#x2714; " .
           "Welcome! </span>";
  }
  
  


  
?>