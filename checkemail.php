<?php 
  require_once 'functions.php';
  requireHTTPS();

  if (isset($_POST['user']))
  {
      $em=$_POST['user'];
     
   if (!eregi("^([a-z0-9.-])+@(([a-z0-9-])+.)+[a-z.]{2,6}$", trim($em)))
    
       echo  "<span >&nbsp;&#x2718; " .
             "Email is invalid</span>";
   
    else{	
        $user=sanitizeString($_POST['user']);
        
          try{
   
  $res=$connection->query("SELECT* FROM members WHERE user= '$user'");
  if($res==FALSE)
                throw new Exception("Select failure");
       if($res->num_rows){
          echo  "<span >&nbsp;&#x2718; " .
           "This email is invalid</span>";
            $res->close();
       
            $connection->close();
            }else{
          echo 
           "<span >&nbsp;&#x2714; " .
           "This email is valid</span>";
           $res->close();
     
           $connection->close();
          }      
     }catch(Exception $e)
          {
              
              
              $connection->close();
              echo "Error:".$e->getMessage();
              }
        
       

  
  }
  }
  
 
?>