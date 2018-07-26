<?php
 session_start();
require_once 'functions.php';
requireHTTPS();
  
                
                 if(isset($_SESSION['s232521_user'])){
                                           $client=$_SESSION['s232521_user'];
                                          echo"<ul class='style1'>";
                                          $day=explode(':',date("M:d"));
                                          $res = $connection->query( "SELECT client, machine, startingtime, duration FROM books WHERE client NOT LIKE '$client' ORDER BY startingtime");//le prenotazioni non relative all'utente
                                          
                                          if($res->num_rows){
                                          $rows=$res->num_rows;
                                          for($j=0;$j<$rows;++$j){
                                          $res->data_seek($j);
                                          $row=$res->fetch_array(MYSQLI_NUM);
                                          $temp=explode('@',$row[0]);//utilizzo la funzione explode per ottenere visualizzare solo la parte della mail precendete la @,in modo da non visualizzare dati sensibili all'atto del listing delle prenotazioni
                                         
                                          echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b><a>$row[2]</a></a></p>".
                                                "<h3>Our dear $temp[0]'s reservation:</h3>".
                                                "<p><a>machine:'$row[1]',duration:'$row[3]' minutes </a></p>".
                                              "</li>";
                                        
                                        
                                        }
                                      
                                        echo" </ul>";
                                        $res->close();
                                        } else{
                                            
                                            echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b></a></p>".
                                                "<h3>Our dear students have:</h3>".
                                                "<p><a>No reserved machine </a></p>".
                                              "</li>";
                                              echo" </ul>";
                                            
                                            
                                            
                                            }
$connection->close();
}
?> 